<?php
/**
 * Plugin Name: Connectis — Identité visuelle (import du logo)
 * Description: Importe une fois le logo complet et le favicon dans la médiathèque à partir des fichiers déployés (aucun appel réseau), puis les assigne comme logo du thème et icône du site. Idempotent, tolérant aux erreurs.
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
