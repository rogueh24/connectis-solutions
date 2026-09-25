<?php
/**
 * Plugin Name: Connectis — Configuration du thème (design)
 * Description: Applique la configuration Blocksy du site : en-tête minimaliste (logo à gauche, recherche + connexion + menu hamburger à droite, sur ordinateur comme sur mobile), palette de couleurs du logo, pied de page. Versionné dans Git ; réappliqué quand THEME_CONFIG_VERSION change. Les réglages Blocksy ne sont pas accessibles par l'API REST, d'où ce mu-plugin.
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

const CONNECTIS_THEME_CONFIG_VERSION = 1;

// Pied de page : copyright, e-mail + liens réglementaires (l'identité légale complète est dans les mentions légales) (appliqué au rendu tant qu'aucun texte
// personnalisé n'a été saisi dans le Customizer).
add_filter('blocksy:footer:copyright:default-value', function () {
    return '&copy; {current_year} Connectis Solutions<br>'
        . '<a href="/mentions-legales/">Mentions légales</a> · <a href="/cgv/">CGV</a> · '
        . '<a href="/confidentialite/">Politique de confidentialité</a>';
});

add_action('init', function () {
    if ((int) get_option('connectis_theme_config_version', 0) >= CONNECTIS_THEME_CONFIG_VERSION) {
        return;
    }
    if (get_stylesheet() !== 'blocksy') {
        return; // les mods sont propres à chaque thème : on attend que Blocksy soit actif
    }

    try {
        // --- En-tête : structure « placements » de Blocksy ---
        $bar = function ($id, array $items = [], $secondary = true) {
            $placements = [['id' => 'start', 'items' => $items['start'] ?? []]];
            if ($secondary) {
                foreach (['middle', 'end', 'start-middle', 'end-middle'] as $slot) {
                    $placements[] = ['id' => $slot, 'items' => $items[$slot] ?? []];
                }
            }
            return ['id' => $id, 'placements' => $placements];
        };

        // Même en-tête sur ordinateur et mobile : logo | recherche · connexion · hamburger.
        // Le panneau latéral (offcanvas) contient le menu principal.
        $device = function () use ($bar) {
            return [
                $bar('top-row'),
                $bar('middle-row', ['start' => ['logo'], 'end' => ['search', 'account', 'trigger']]),
                $bar('bottom-row'),
                $bar('offcanvas', ['start' => ['mobile-menu']], false),
            ];
        };

        set_theme_mod('header_placements', [
            'current_section' => 'type-1',
            'sections'        => [[
                'id'       => 'type-1',
                'mode'     => 'placements',
                'items'    => [],
                'settings' => [],
                'desktop'  => $device(),
                'mobile'   => $device(),
            ]],
        ]);

        // --- Palette : couleurs du logo Connectis ---
        set_theme_mod('colorPalette', [
            'color1' => ['color' => '#1b63e6'], // bleu principal (liens, boutons)
            'color2' => ['color' => '#123a8c'], // bleu foncé (survol)
            'color3' => ['color' => '#4a5568'], // texte courant
            'color4' => ['color' => '#070d1f'], // titres, marine
            'color5' => ['color' => '#cfd8e8'], // bordures
            'color6' => ['color' => '#eef4fd'], // fond doux
            'color7' => ['color' => '#f7f9fc'], // fond de page
            'color8' => ['color' => '#ffffff'],
        ]);

        update_option('connectis_theme_config_error', null);
        update_option('connectis_theme_config_version', CONNECTIS_THEME_CONFIG_VERSION);
    } catch (\Throwable $e) {
        update_option('connectis_theme_config_error', $e->getMessage());
        update_option('connectis_theme_config_version', CONNECTIS_THEME_CONFIG_VERSION);
    }
}, 50);
