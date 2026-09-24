<?php
/**
 * Plugin Name: Connectis — Administration et durcissement
 * Description: Réglages du site appliqués une fois (commentaires fermés, page de confidentialité…), durcissement (énumération des comptes, XML-RPC, en-têtes HTTP), tableau de bord épuré, page de connexion à l'image du site.
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Le contenu est du code versionné : pas d'édition de fichiers depuis l'admin, révisions limitées.
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}
if (!defined('WP_POST_REVISIONS')) {
    define('WP_POST_REVISIONS', 5);
}

// v1 : commentaires fermés, référencement autorisé, page de confidentialité.
const CONNECTIS_ADMIN_VERSION = 1;

/* ───────────────────────── Réglages appliqués une fois ───────────────────────── */

add_action('init', function () {
    if ((int) get_option('connectis_admin_version', 0) >= CONNECTIS_ADMIN_VERSION) {
        return;
    }

    try {
        update_option('default_comment_status', 'closed');
        update_option('default_ping_status', 'closed');
        update_option('default_pingback_flag', 0);
        update_option('use_smilies', 0);
        update_option('users_can_register', 0);
        update_option('blog_public', 1);

        global $wpdb;
        $wpdb->query("UPDATE {$wpdb->posts} SET comment_status = 'closed', ping_status = 'closed' WHERE post_type IN ('page', 'post')");

        $privacy = get_page_by_path('confidentialite');
        if ($privacy) {
            update_option('wp_page_for_privacy_policy', (int) $privacy->ID);
        }

        // Le site sort de la page en construction : on repart d'un cache propre.
        do_action('litespeed_purge_all');

        update_option('connectis_admin_error', null);
        update_option('connectis_admin_version', CONNECTIS_ADMIN_VERSION);
    } catch (\Throwable $e) {
        update_option('connectis_admin_error', $e->getMessage());
    }
}, 80);

/* ───────────────────────── Durcissement ───────────────────────── */

// Commentaires et rétroliens : inutiles sur un site vitrine.
add_filter('comments_open', '__return_false', 20);
add_filter('pings_open', '__return_false', 20);
add_filter('xmlrpc_enabled', '__return_false');

add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});
add_action('admin_bar_menu', function ($bar) {
    $bar->remove_node('comments');
}, 999);

// Ne pas exposer l'identifiant de connexion : liste des comptes en REST et archives d'auteur.
add_filter('rest_endpoints', function ($endpoints) {
    if (!is_user_logged_in()) {
        unset($endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)']);
    }
    return $endpoints;
});

add_action('template_redirect', function () {
    if (is_author() || isset($_GET['author'])) {
        wp_safe_redirect(home_url('/'), 301);
        exit;
    }
});

// Message de connexion neutre (ne révèle pas si l'identifiant existe).
add_filter('login_errors', function () {
    return 'Identifiants incorrects.';
});

// Flux inutiles (aucun blog) et éléments d'en-tête qui n'apportent rien.
foreach (['do_feed', 'do_feed_rdf', 'do_feed_rss', 'do_feed_rss2', 'do_feed_atom'] as $hook) {
    add_action($hook, function () {
        wp_safe_redirect(home_url('/'), 301);
        exit;
    }, 1);
}
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// En-têtes de sécurité (sans risque pour un site vitrine).
add_action('send_headers', function () {
    if (headers_sent()) {
        return;
    }
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()');
    if (is_ssl()) {
        header('Strict-Transport-Security: max-age=15552000');
    }
});

/* ───────────────────────── Tableau de bord ───────────────────────── */

add_filter('show_welcome_panel', '__return_false');

add_action('wp_dashboard_setup', function () {
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
    // Publicité de l'extension anti-spam.
    foreach (['wpa_dashboard_widget', 'wp_armour_dashboard_widget', 'wpa_spam_stats'] as $id) {
        remove_meta_box($id, 'dashboard', 'normal');
        remove_meta_box($id, 'dashboard', 'side');
    }

    wp_add_dashboard_widget('connectis_memo', 'Connectis — mémo du site', function () {
        echo '<p><strong>Le contenu des pages est géré dans le code</strong> (dépôt GitHub <em>rogueh24/connectis-solutions</em>) : '
            . 'une modification faite ici sur une page d\'offre, l\'accueil ou les pages légales est écrasée à la prochaine mise à jour du contenu.</p>';
        echo '<ul style="list-style:disc;margin-left:18px">'
            . '<li>Demandes de devis et messages : reçus par e-mail (formulaires Contact Form 7).</li>'
            . '<li>Référencement : SEOPress + fichier <code>connectis-seo.php</code>.</li>'
            . '<li>Diagnostic technique : <a href="' . esc_url(rest_url('connectis/v1/status')) . '" target="_blank" rel="noopener">état du site</a>.</li>'
            . '</ul>';
    });
});

add_filter('admin_footer_text', function () {
    return 'Site Connectis Solutions — connectis-solutions.fr';
});

/* ───────────────────────── Page de connexion ───────────────────────── */

add_filter('login_headerurl', function () {
    return home_url('/');
});
add_filter('login_headertext', function () {
    return 'Connectis Solutions';
});

add_action('login_enqueue_scripts', function () {
    $logo = esc_url(content_url('mu-plugins/connectis-maintenance/logo-full.png'));
    ?>
<style>
  body.login { background: radial-gradient(1200px 600px at 50% -10%, #123a8c 0%, #070d1f 60%); }
  .login h1 a { background: url('<?php echo $logo; ?>') center / contain no-repeat; width: 240px; height: 120px; }
  .login form { border: 1px solid rgba(255,255,255,.12); border-radius: 16px; background: rgba(255,255,255,.06); box-shadow: 0 20px 50px rgba(0,0,0,.35); }
  .login label, .login #nav a, .login #backtoblog a, .login .privacy-policy-page-link a { color: #dbe4f3; }
  .login #nav a:hover, .login #backtoblog a:hover { color: #33d0e8; }
  .login form .input, .login input[type=text], .login input[type=password] { border-radius: 10px; }
  .wp-core-ui .button-primary { background: #1b63e6; border-color: #1b63e6; border-radius: 10px; }
  .wp-core-ui .button-primary:hover { background: #123a8c; border-color: #123a8c; }
</style>
    <?php
});
