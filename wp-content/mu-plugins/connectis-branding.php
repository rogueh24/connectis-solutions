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

// Charte graphique : injecte connectis-branding/custom.css dans <head>, après les styles du thème.
add_action('wp_head', function () {
    $file = __DIR__ . '/connectis-branding/custom.css';
    if (is_readable($file)) {
        echo '<style id="connectis-branding">' . "\n" . file_get_contents($file) . "\n</style>\n";
    }
}, 100);

// Barre d'identité vérifiable, visible sur toutes les pages (confiance clients et organismes de financement).
add_action('wp_body_open', function () {
    echo '<div class="cn-topbar"><div class="cn-topbar-in">'
        . '<span><strong>Connectis Solutions</strong> · SAS · SIREN 103 370 557 · RCS Nancy</span>'
        . '<span class="cn-tb-right"><a href="mailto:contact@connectis-solutions.fr">contact@connectis-solutions.fr</a> · Devis gratuit</span>'
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
