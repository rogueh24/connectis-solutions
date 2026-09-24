<?php
/**
 * Plugin Name: Connectis — Identité visuelle (import du logo)
 * Description: Importe une fois le logo complet et le favicon dans la médiathèque (fichiers déployés, aucun appel réseau) et les assigne comme logo/icône du site ; injecte la charte graphique CSS. Idempotent, tolérant aux erreurs.
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function () {
    if (get_option('connectis_branding_v1')) {
        return;
    }

    try {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $dir = __DIR__ . '/connectis-maintenance/';

        $import = function ($file, $title) use ($dir) {
            $path = $dir . $file;
            if (!is_readable($path)) {
                throw new RuntimeException('Fichier introuvable : ' . $file);
            }
            $upload = wp_upload_bits('connectis-' . $file, null, file_get_contents($path));
            if (!empty($upload['error'])) {
                throw new RuntimeException($upload['error']);
            }
            $id = wp_insert_attachment([
                'post_mime_type' => 'image/png',
                'post_title'     => $title,
                'post_content'   => '',
                'post_status'    => 'inherit',
            ], $upload['file']);
            if (is_wp_error($id) || !$id) {
                throw new RuntimeException('Création de la pièce jointe impossible : ' . $file);
            }
            wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $upload['file']));
            update_post_meta($id, '_wp_attachment_image_alt', $title);
            return (int) $id;
        };

        $logo = $import('logo-full.png', 'Connectis Solutions');
        $icon = $import('favicon.png', 'Connectis Solutions — icône');

        set_theme_mod('custom_logo', $logo);
        update_option('site_icon', $icon);
        update_option('connectis_branding_ids', ['logo' => $logo, 'icon' => $icon]);
    } catch (\Throwable $e) {
        update_option('connectis_branding_error', $e->getMessage());
    }

    // Une seule tentative : pour rejouer, incrémenter la version de l'option.
    update_option('connectis_branding_v1', 1);
}, 40);

// v2 : logo horizontal (symbole à gauche, nom sur deux lignes à droite) pour l'en-tête.
add_action('init', function () {
    if (get_option('connectis_branding_v2') || !get_option('connectis_branding_v1')) {
        return;
    }

    try {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $path = __DIR__ . '/connectis-branding/logo-horizontal.png';
        if (!is_readable($path)) {
            throw new RuntimeException('Fichier introuvable : logo-horizontal.png');
        }
        $upload = wp_upload_bits('connectis-logo-horizontal.png', null, file_get_contents($path));
        if (!empty($upload['error'])) {
            throw new RuntimeException($upload['error']);
        }
        $id = wp_insert_attachment([
            'post_mime_type' => 'image/png',
            'post_title'     => 'Connectis Solutions',
            'post_content'   => '',
            'post_status'    => 'inherit',
        ], $upload['file']);
        if (is_wp_error($id) || !$id) {
            throw new RuntimeException('Création de la pièce jointe impossible : logo horizontal');
        }
        wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $upload['file']));
        update_post_meta($id, '_wp_attachment_image_alt', 'Connectis Solutions');

        set_theme_mod('custom_logo', (int) $id);
        $ids = (array) get_option('connectis_branding_ids', []);
        $ids['logo_horizontal'] = (int) $id;
        update_option('connectis_branding_ids', $ids);
        update_option('connectis_branding_v2_error', null);
        update_option('connectis_branding_v2', 1);
        do_action('litespeed_purge_all');
    } catch (\Throwable $e) {
        update_option('connectis_branding_v2_error', $e->getMessage());
        update_option('connectis_branding_v2', 1);   // une seule tentative
    }
}, 41);

// Charte graphique : injecte connectis-branding/custom.css dans <head>, après les styles du thème.
add_action('wp_head', function () {
    $file = __DIR__ . '/connectis-branding/custom.css';
    if (is_readable($file)) {
        echo '<style id="connectis-branding">' . "\n" . file_get_contents($file) . "\n</style>\n";
    }
}, 100);

// Barre d'accès rapide au-dessus du menu (métiers, contact, devis). L'identité légale (SAS, SIREN, RCS) est dans le pied de page.
add_action('wp_body_open', function () {
    echo '<div class="cn-topbar"><div class="cn-topbar-in">'
        . '<span><strong>Informatique</strong> · <strong>Vidéosurveillance</strong> · <strong>Téléphonie</strong></span>'
        . '<span class="cn-tb-right"><a href="mailto:contact@connectis-solutions.fr">contact@connectis-solutions.fr</a> · <a href="/devis-contact/">Devis gratuit</a></span>'
        . '</div></div>';
});

// Apparition douce des blocs au défilement. Sans JavaScript, tout reste visible.
add_action('wp_footer', function () {
    ?>
<script>
(function () {
  if (!('IntersectionObserver' in window)) { return; }
  var d = document, h = d.documentElement;
  h.classList.add('cn-js');
  var els = d.querySelectorAll('.cn-card,.cn-step,.cn-feature,.cn-strip,.cn-finance,.cn-trust-id,.cn-trust-commit,.cn-cta,.cn-form-card,.cn-faq,.cn-mini');
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (!e.isIntersecting) { return; }
      e.target.classList.add('in');
      io.unobserve(e.target);
      setTimeout(function () { e.target.style.transitionDelay = ''; }, 900);
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });
  els.forEach(function (el, i) {
    el.classList.add('cn-reveal');
    el.style.transitionDelay = ((i % 4) * 70) + 'ms';
    io.observe(el);
  });
  // Filet de sécurité : rien ne doit rester masqué (capture, impression, défilement rapide).
  setTimeout(function () {
    d.querySelectorAll('.cn-reveal:not(.in)').forEach(function (el) { el.classList.add('in'); });
  }, 4000);
})();
</script>
    <?php
}, 100);

// Menu : le logo remplace l'entrée « Accueil » (cliquable, ramène à l'accueil), ordre stable, « Devis & Contact » en appel à l'action.
add_filter('wp_nav_menu_objects', function ($items) {
    $front = (int) get_option('page_on_front');
    // « Accueil » peut être un lien personnalisé vers la racine du site ou la page d'accueil.
    $is_home = function ($item) use ($front) {
        if ((int) $item->menu_item_parent) {
            return false;
        }
        if ($item->object === 'page' && (int) $item->object_id === $front) {
            return true;
        }
        return rtrim((string) $item->url, '/') === rtrim(home_url(), '/');
    };
    $order = ['nos-solutions' => 2, 'a-propos' => 3, 'recrutement' => 4, 'devis-contact' => 5];

    $top = [];
    $children = [];
    foreach ($items as $item) {
        if ((int) $item->menu_item_parent) {
            $children[(int) $item->menu_item_parent][] = $item;
        } else {
            $top[] = $item;
        }
    }
    if (!$top) {
        return $items;
    }

    $slug_of = function ($item) {
        $page = ($item->object === 'page') ? get_post((int) $item->object_id) : null;
        return $page ? $page->post_name : '';
    };
    usort($top, function ($a, $b) use ($order, $slug_of, $is_home) {
        $pa = $is_home($a) ? 1 : ($order[$slug_of($a)] ?? 50);
        $pb = $is_home($b) ? 1 : ($order[$slug_of($b)] ?? 50);
        return $pa <=> $pb;
    });

    $sorted = [];
    $i = 1;
    $append = function ($item) use (&$sorted, &$i, &$append, $children, $slug_of, $is_home) {
        $item->menu_order = $i++;
        if ($is_home($item)) {
            $item->classes[] = 'cn-menu-logo';
            $item->attr_title = 'Connectis Solutions — accueil';
        }
        if ($slug_of($item) === 'a-propos') {
            $item->title = 'Connectis';
        }
        if ($slug_of($item) === 'nos-solutions' && !(int) $item->menu_item_parent) {
            $item->title = 'Solutions';
        }
        if ($slug_of($item) === 'devis-contact' && !(int) $item->menu_item_parent) {
            $item->classes[] = 'cn-menu-cta';
        }
        $sorted[] = $item;
        foreach ($children[(int) $item->ID] ?? [] as $child) {
            $append($child);
        }
    };
    foreach ($top as $item) {
        $append($item);
    }
    return $sorted;
}, 20);
