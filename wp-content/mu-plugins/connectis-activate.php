<?php
/**
 * Plugin Name: Connectis — Activation automatique
 * Description: Active le thème Blocksy et les extensions déployées par le pipeline SFTP (déposer les fichiers ne les active pas). Idempotent, tolérant aux erreurs (une extension cassée ne doit jamais faire tomber le site entier).
 * Version: 1.1
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('plugins_loaded', function () {

    if (get_option('connectis_activated_v2')) {
        return;
    }

    $errors = [];

    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    require_once ABSPATH . 'wp-admin/includes/theme.php';

    // --- Thème ---
    try {
        $current = wp_get_theme();
        if ($current->get_stylesheet() !== 'blocksy' && wp_get_theme('blocksy')->exists()) {
            switch_theme('blocksy');
        }
    } catch (\Throwable $e) {
        $errors['theme:blocksy'] = $e->getMessage();
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
        if (in_array($plugin, $active, true) || !file_exists(WP_PLUGIN_DIR . '/' . $plugin)) {
            continue;
        }
        try {
            $result = activate_plugin($plugin);
            if (is_wp_error($result)) {
                $errors[$plugin] = $result->get_error_message();
            } else {
                $active[] = $plugin;
            }
        } catch (\Throwable $e) {
            $errors[$plugin] = $e->getMessage();
            // Une extension a pu s'inclure partiellement avant de planter : on la
            // retire explicitement de la liste active pour ne pas la re-déclencher
            // en boucle à chaque requête suivante.
            $active = array_values(array_diff((array) get_option('active_plugins', []), [$plugin]));
            update_option('active_plugins', $active);
        }
    }

    update_option('connectis_activation_errors', $errors);
    update_option('connectis_activated_v2', 1);
}, 1);

// Petit endpoit de diagnostic public (lecture seule, aucune donnée sensible) pour
// vérifier l'état d'activation sans avoir besoin d'accès à wp-admin.
add_action('rest_api_init', function () {
    register_rest_route('connectis/v1', '/status', [
        'methods'  => 'GET',
        'callback' => function () {
            return [
                'wp_version'  => get_bloginfo('version'),
                'php_version' => phpversion(),
                'theme'       => wp_get_theme()->get_stylesheet(),
                'active_plugins' => get_option('active_plugins', []),
                'activation_errors' => get_option('connectis_activation_errors', []),
                'content_seeded'    => (bool) get_option('connectis_content_seeded_v2'),
            ];
        },
        'permission_callback' => '__return_true',
    ]);
});
