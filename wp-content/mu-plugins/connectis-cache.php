<?php
/**
 * Plugin Name: Connectis — Réglages LiteSpeed Cache
 * Description: Réglages de performance de LiteSpeed Cache, appliqués par le code (versionnés) : cache navigateur, minification HTML/CSS/JS. Réappliqué quand CONNECTIS_CACHE_VERSION change.
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// v1 : cache navigateur (30 jours), minification HTML/CSS/JS, emojis retirés.
const CONNECTIS_CACHE_VERSION = 1;

/**
 * Réglages voulus. Non activés volontairement : la combinaison CSS/JS (risque de casse), le chargement différé
 * des images (déjà natif dans Blocksy), le mode invité, la file d'exploration et le cache d'objet (services
 * ou modules serveur externes).
 */
function connectis_cache_settings() {
    return [
        'cache-browser'     => 1,
        'cache-ttl_browser' => 2592000, // 30 jours : les logos et images statiques changent rarement
        'optm-html_min'     => 1,
        'optm-css_min'      => 1,
        'optm-js_min'       => 1,
        'optm-emoji_rm'     => 1,
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
