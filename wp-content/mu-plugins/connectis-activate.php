<?php
/**
 * Plugin Name: Connectis — Activation automatique
 * Description: Active le thème Blocksy et les extensions déployées par le pipeline SFTP (déposer les fichiers ne les active pas). Idempotent.
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('plugins_loaded', function () {

    if (get_option('connectis_activated_v1')) {
        return;
    }

    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    require_once ABSPATH . 'wp-admin/includes/theme.php';

    // --- Thème ---
    $current = wp_get_theme();
    if ($current->get_stylesheet() !== 'blocksy' && wp_get_theme('blocksy')->exists()) {
        switch_theme('blocksy');
    }

    // --- Extensions ---
    $plugins = [
        'litespeed-cache/litespeed-cache.php',
        'seopress/seopress.php',
        'suremails/suremails.php',
        'honeypot/wp-armour.php',
        'blocksy-companion/blocksy-companion.php',
        'abilities-api/abilities-api.php',
        'contact-form-7/wp-contact-form-7.php',
    ];

    $active = (array) get_option('active_plugins', []);

    foreach ($plugins as $plugin) {
        if (!in_array($plugin, $active, true) && file_exists(WP_PLUGIN_DIR . '/' . $plugin)) {
            $result = activate_plugin($plugin);
            if (!is_wp_error($result)) {
                $active[] = $plugin;
            }
        }
    }

    update_option('connectis_activated_v1', 1);
}, 1);
