<?php
/**
 * Plugin Name: Connectis — Réglages LiteSpeed Cache
 * Description: Réglages de performance de LiteSpeed Cache, appliqués par le code (versionnés) : cache navigateur, minification HTML/CSS/JS. Réappliqué quand CONNECTIS_CACHE_VERSION change.
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// v3 : page jamais mise en cache pour un visiteur porteur d'un cookie Site Kit ou de connexion WordPress.
const CONNECTIS_CACHE_VERSION = 3;

/**
 * Réglages voulus. Non activés volontairement : la combinaison CSS/JS (risque de casse), le chargement différé
 * des images (déjà natif dans Blocksy), le mode invité, la file d'exploration et le cache d'objet (services
 * ou modules serveur externes).
 */
function connectis_cache_settings() {
    // Le script de connexion Google (bouton « Se connecter avec Google », One Tap) doit s'exécuter tel quel :
    // le minifier, le combiner, le différer ou le reporter en mode invité l'empêche de fonctionner.
    $google_gsi = ['accounts.google.com/gsi/client'];
    return [
        'cache-browser'     => 1,
        'cache-ttl_browser' => 2592000, // 30 jours : les logos et images statiques changent rarement
        'optm-html_min'     => 1,
        'optm-css_min'      => 1,
        'optm-js_min'       => 1,
        'optm-emoji_rm'     => 1,
        'optm-js_exc'       => $google_gsi,
        'optm-js_defer_exc' => $google_gsi,
        'optm-gm_js_exc'    => $google_gsi,
        // Un visiteur porteur d'un de ces cookies (venant de se connecter avec Google, ou déjà connecté à
        // WordPress) ne doit jamais recevoir une page mise en cache pour quelqu'un d'autre.
        'cache-exc_cookies' => ['googlesitekit', 'wordpress_logged_in'],
    ];
}

add_action('init', function () {
    if ((int) get_option('connectis_cache_version', 0) >= CONNECTIS_CACHE_VERSION) {
        return;
    }
    if (!class_exists('\LiteSpeed\Conf')) {
        return; // extension absente ou inactive : on réessaiera à la requête suivante
    }

    try {
        \LiteSpeed\Conf::cls()->update_confs(connectis_cache_settings());
        do_action('litespeed_purge_all');
        update_option('connectis_cache_error', null);
        update_option('connectis_cache_version', CONNECTIS_CACHE_VERSION);
    } catch (\Throwable $e) {
        update_option('connectis_cache_error', $e->getMessage());
        update_option('connectis_cache_version', CONNECTIS_CACHE_VERSION); // une seule tentative
    }
}, 95);
