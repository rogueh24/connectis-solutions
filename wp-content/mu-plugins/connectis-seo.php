<?php
/**
 * Plugin Name: Connectis — Référencement (SEO)
 * Description: Titres et descriptions de chaque page (métadonnées SEOPress), plan du site, données structurées « entreprise locale ». Réappliqué quand CONNECTIS_SEO_VERSION change.
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// v3 : mots-clés cibles, plan du site sans taxonomies, image de partage, pièces jointes désactivées, fil d'Ariane et services (JSON-LD).
const CONNECTIS_SEO_VERSION = 3;

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

/**
 * Mot-clé principal par page (analyse SEOPress ; les « meta keywords » n'existent plus, Google les ignore).
 */
function connectis_seo_keywords() {
    return [
        'accueil'                          => 'informatique vidéosurveillance téléphonie Nancy',
        'nos-solutions'                    => 'solutions informatique vidéosurveillance téléphonie entreprise',
        'nos-solutions/materiel'           => 'matériel informatique entreprise',
        'nos-solutions/videosurveillance'  => 'vidéosurveillance professionnelle',
        'nos-solutions/telephonie'         => 'téléphonie entreprise standard VoIP',
        'nos-solutions/fibre'              => 'fibre optique entreprise',
        'nos-solutions/abonnements'        => 'abonnements internet téléphonie professionnels',
        'nos-solutions/services'           => 'maintenance informatique installation',
        'a-propos'                         => 'Connectis Solutions Nancy',
        'devis-contact'                    => 'devis informatique vidéosurveillance téléphonie',
        'recrutement'                      => 'recrutement technicien commercial informatique Nancy',
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

        foreach (connectis_seo_keywords() as $path => $keyword) {
            $page = get_page_by_path($path);
            if ($page) {
                update_post_meta($page->ID, '_seopress_analysis_target_kw', $keyword);
            }
        }

        // Plan du site : pas de taxonomies (aucun article), pages seulement.
        $sitemap = (array) get_option('seopress_xml_sitemap_option_name', []);
        $sitemap['seopress_xml_sitemap_taxonomies_list'] = ['category' => ['include' => ''], 'post_tag' => ['include' => '']];
        update_option('seopress_xml_sitemap_option_name', $sitemap);

        // Partage sur les réseaux : image par défaut 1200×630, cartes Twitter/X en grand format.
        $og = content_url('mu-plugins/connectis-branding/og-image.jpg');
        $social = (array) get_option('seopress_social_option_name', []);
        $social['seopress_social_facebook_og']       = '1';
        $social['seopress_social_facebook_img']      = $og;
        $social['seopress_social_twitter_card']      = '1';
        $social['seopress_social_twitter_card_og']   = '1';
        $social['seopress_social_twitter_card_img']  = $og;
        $social['seopress_social_twitter_card_img_size'] = 'large';
        update_option('seopress_social_option_name', $social);

        // Pas de pages « pièce jointe » indexables.
        update_option('wp_attachment_pages_enabled', 0);

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

// Données structurées des pages intérieures : fil d'Ariane, et « Service » pour les six offres.
add_action('wp_head', function () {
    if (!is_page() || is_front_page()) {
        return;
    }
    $page = get_queried_object();
    if (!$page) {
        return;
    }

    $trail = [['name' => 'Accueil', 'url' => home_url('/')]];
    $ancestors = array_reverse(get_post_ancestors($page));
    foreach ($ancestors as $id) {
        $trail[] = ['name' => get_the_title($id), 'url' => get_permalink($id)];
    }
    $trail[] = ['name' => get_the_title($page), 'url' => get_permalink($page)];

    $items = [];
    foreach ($trail as $i => $crumb) {
        $items[] = ['@type' => 'ListItem', 'position' => $i + 1, 'name' => wp_strip_all_tags($crumb['name']), 'item' => $crumb['url']];
    }
    $graph = [['@type' => 'BreadcrumbList', 'itemListElement' => $items]];

    if (strpos(get_page_uri($page), 'nos-solutions/') === 0) {
        $graph[] = [
            '@type'       => 'Service',
            'name'        => wp_strip_all_tags(get_the_title($page)),
            'description' => (string) get_post_meta($page->ID, '_seopress_titles_desc', true),
            'url'         => get_permalink($page),
            'provider'    => ['@id' => home_url('/#entreprise')],
            'areaServed'  => 'FR',
            'audience'    => ['@type' => 'BusinessAudience', 'audienceType' => 'Professionnels'],
        ];
    }

    echo '<script type="application/ld+json">' . wp_json_encode(['@context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</script>
";
}, 20);
