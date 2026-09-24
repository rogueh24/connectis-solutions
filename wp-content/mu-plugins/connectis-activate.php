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

    if (get_option('connectis_activated_v3')) {
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
    // SEOPress volontairement exclu : fatale sur PHP 8+ (référence à la constante
    // SITE_ID_CURRENT_SITE, réservée au multisite, sans garde defined()) dans
    // sp-core.php. Cassait le site entier à chaque requête (pas seulement à
    // l'activation, impossible à intercepter via try/catch ici). À réactiver une
    // fois une version corrigée disponible, ou remplacé par une autre extension SEO.
    $plugins = [
        'litespeed-cache/litespeed-cache.php',
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
    update_option('connectis_activated_v3', 1);
}, 1);

// Filet de sécurité : enregistre la dernière erreur fatale PHP (d'où qu'elle vienne)
// dans une option, lisible via l'endpoint de diagnostic ci-dessous. Ne bloque rien,
// sert uniquement à pouvoir diagnostiquer à distance sans accès SSH/FTP.
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR], true)) {
        update_option('connectis_last_fatal_error', [
            'message' => $error['message'],
            'file'    => $error['file'],
            'line'    => $error['line'],
            'time'    => current_time('mysql'),
        ]);
    }
});

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
                'content_seed_error' => get_option('connectis_content_seed_error', null),
                'last_fatal_error'   => get_option('connectis_last_fatal_error', null),
            ];
        },
        'permission_callback' => '__return_true',
    ]);
});
