<?php
/**
 * Plugin Name: Connectis — Référencement (SEO)
 * Description: Titres et descriptions de chaque page (métadonnées SEOPress), plan du site, données structurées « entreprise locale ». Réappliqué quand CONNECTIS_SEO_VERSION change.
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// v2 : descriptions sans « gratuit » (devis détaillé, financement).
const CONNECTIS_SEO_VERSION = 2;

/**
 * Métadonnées par page : chemin => [titre (≈60 car.), description (≈155 car.)].
 */
function connectis_seo_definitions() {
    return [
        'accueil' => [
            'Informatique, vidéosurveillance, téléphonie à Nancy | Connectis',
            "Connectis Solutions installe et maintient l'informatique, la vidéosurveillance et la téléphonie des professionnels à Nancy. Devis détaillé et solutions de financement.",
        ],
        'nos-solutions' => [
            'Nos solutions pour professionnels | Connectis Solutions',
            "Informatique, vidéosurveillance, téléphonie, fibre, abonnements et maintenance : découvrez les solutions Connectis Solutions pour votre entreprise.",
        ],
        'nos-solutions/materiel' => [
            'Informatique et matériel pour entreprises | Connectis',
            "Postes de travail, réseau, serveurs et périphériques : fourniture, installation et configuration par notre équipe à Nancy. Devis détaillé et solutions de financement.",
        ],
        'nos-solutions/videosurveillance' => [
            'Vidéosurveillance professionnelle | Connectis Solutions',
            "Caméras IP, enregistreurs, accès à distance, alarme et contrôle d'accès pour vos locaux professionnels. Installation sur site et devis détaillé.",
        ],
        'nos-solutions/telephonie' => [
            "Téléphonie d'entreprise, standard et VoIP | Connectis",
            "Standard téléphonique, téléphonie IP, lignes fixes et mobiles pour professionnels : installation, paramétrage et suivi par Connectis Solutions.",
        ],
        'nos-solutions/fibre' => [
            'Internet et fibre optique pour entreprises | Connectis',
            "Raccordement fibre professionnel, Wi-Fi, box entreprise et solutions de secours : Connectis Solutions s'occupe de la connexion de vos locaux.",
        ],
        'nos-solutions/abonnements' => [
            'Abonnements et forfaits professionnels | Connectis',
            "Formules d'abonnement internet, téléphonie et vidéosurveillance adaptées à votre activité, avec un interlocuteur unique du devis au suivi.",
        ],
        'nos-solutions/services' => [
            'Installation et maintenance informatique | Connectis',
            "Installation sur site, maintenance préventive, dépannage réactif et contrats SAV pour vos équipements, par l'équipe Connectis Solutions.",
        ],
        'a-propos' => [
            'À propos de Connectis Solutions | Nancy',
            "Connectis Solutions, SAS basée à Nancy : conseil, installation et maintenance en informatique, vidéosurveillance et téléphonie pour les professionnels.",
        ],
        'devis-contact' => [
            'Devis, financement et contact | Connectis Solutions',
            "Décrivez votre projet informatique, vidéosurveillance ou téléphonie : réponse rapide, devis détaillé et solutions de financement de Connectis Solutions à Nancy.",
        ],
        'recrutement' => [
            'Recrutement | Rejoindre Connectis Solutions',
            "Connectis Solutions recrute une équipe commerciale et technique de terrain à Nancy. Envoyez votre candidature, même spontanée.",
        ],
        'mentions-legales' => [
            'Mentions légales | Connectis Solutions',
            "Mentions légales du site connectis-solutions.fr : éditeur, hébergeur, propriété intellectuelle, données personnelles et droit applicable.",
        ],
        'cgv' => [
            'Conditions générales de vente | Connectis Solutions',
            "Conditions générales de vente de Connectis Solutions applicables aux professionnels : devis, prix, paiement, livraison, garanties et maintenance.",
        ],
        'confidentialite' => [
            'Politique de confidentialité | Connectis Solutions',
            "Comment Connectis Solutions utilise vos données personnelles (devis, contact, candidature), durées de conservation et exercice de vos droits.",
        ],
    ];
}

add_action('init', function () {
    if ((int) get_option('connectis_seo_version', 0) >= CONNECTIS_SEO_VERSION) {
        return;
    }

    try {
        $done = 0;
        foreach (connectis_seo_definitions() as $path => $meta) {
            $page = get_page_by_path($path);
            if (!$page) {
                continue;
            }
            update_post_meta($page->ID, '_seopress_titles_title', $meta[0]);
            update_post_meta($page->ID, '_seopress_titles_desc', $meta[1]);
            $done++;
        }

        // Plan du site XML de SEOPress (pages incluses), sans écraser les autres réglages.
        $toggle = (array) get_option('seopress_toggle', []);
        $toggle['toggle-xml-sitemap'] = '1';
        update_option('seopress_toggle', $toggle);

        $sitemap = (array) get_option('seopress_xml_sitemap_option_name', []);
        $sitemap['seopress_xml_sitemap_general_enable'] = '1';
        $sitemap['seopress_xml_sitemap_post_types_list'] = ['page' => ['include' => '1']];
        update_option('seopress_xml_sitemap_option_name', $sitemap);

        update_option('connectis_seo_pages_done', $done);
        update_option('connectis_seo_error', null);
        update_option('connectis_seo_version', CONNECTIS_SEO_VERSION);
    } catch (\Throwable $e) {
        update_option('connectis_seo_error', $e->getMessage());
    }
}, 70);

// Données structurées : entreprise locale (page d'accueil uniquement).
add_action('wp_head', function () {
    if (!is_front_page()) {
        return;
    }

    $data = [
        '@context'    => 'https://schema.org',
        '@type'       => 'LocalBusiness',
        '@id'         => home_url('/#entreprise'),
        'name'        => 'Connectis Solutions',
        'legalName'   => 'CONNECTIS SOLUTIONS',
        'url'         => home_url('/'),
        'logo'        => content_url('mu-plugins/connectis-maintenance/logo-full.png'),
        'image'       => content_url('mu-plugins/connectis-content-seed/images/materiel.jpg'),
        'email'       => 'contact@connectis-solutions.fr',
        'description' => "Installation et maintenance d'informatique, de vidéosurveillance et de téléphonie pour les professionnels.",
        'address'     => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => "110 boulevard d'Austrasie",
            'postalCode'      => '54000',
            'addressLocality' => 'Nancy',
            'addressCountry'  => 'FR',
        ],
        'identifier'  => [
            ['@type' => 'PropertyValue', 'propertyID' => 'SIREN', 'value' => '103370557'],
            ['@type' => 'PropertyValue', 'propertyID' => 'SIRET', 'value' => '10337055700011'],
        ],
        'knowsAbout'  => ['Informatique', 'Vidéosurveillance', 'Téléphonie', 'Fibre optique', 'Maintenance informatique'],
    ];

    echo '<script type="application/ld+json">' . wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</script>\n";
}, 20);
