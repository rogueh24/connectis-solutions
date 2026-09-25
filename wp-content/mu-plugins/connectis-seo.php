<?php
/**
 * Plugin Name: Connectis — Référencement (SEO)
 * Description: Titres et descriptions de chaque page (métadonnées SEOPress), plan du site, données structurées « entreprise locale ». Réappliqué quand CONNECTIS_SEO_VERSION change.
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// v4 : titres ≤ 60 caractères, descriptions 120-155, mots-clés courts présents dans titre/description/H1.
const CONNECTIS_SEO_VERSION = 4;

/**
 * Métadonnées par page : chemin => [titre (≈60 car.), description (≈155 car.)].
 */
function connectis_seo_definitions() {
    return [
        'accueil' => [
            "Connectis – Informatique, vidéosurveillance, téléphonie",
            "Connectis Solutions installe et maintient l'informatique, la vidéosurveillance et la téléphonie des professionnels à Nancy. Solutions de financement.",
        ],
        'nos-solutions' => [
            "Solutions informatique, vidéosurveillance, téléphonie",
            "Toutes nos solutions pour les professionnels : informatique, vidéosurveillance, téléphonie, fibre, abonnements et maintenance. Devis et financement.",
        ],
        'nos-solutions/materiel' => [
            "Informatique et matériel pour entreprises | Connectis",
            "Informatique et matériel : postes de travail, réseau, serveurs et périphériques, installés et configurés par notre équipe. Devis détaillé et financement.",
        ],
        'nos-solutions/videosurveillance' => [
            "Vidéosurveillance professionnelle et caméras IP",
            "Vidéosurveillance professionnelle : caméras IP, enregistreurs, accès à distance, alarme et contrôle d'accès. Installation sur site et devis détaillé.",
        ],
        'nos-solutions/telephonie' => [
            "Téléphonie d'entreprise : standard et VoIP | Connectis",
            "Téléphonie d'entreprise : standard téléphonique, VoIP, lignes fixes et mobiles. Installation, paramétrage et suivi par Connectis Solutions.",
        ],
        'nos-solutions/fibre' => [
            "Internet et fibre optique pour entreprises | Connectis",
            "Internet et fibre optique pour les professionnels : raccordement, Wi-Fi, box entreprise et solutions de secours. Étude sur site et mise en service.",
        ],
        'nos-solutions/abonnements' => [
            "Abonnements et forfaits professionnels | Connectis",
            "Abonnements et forfaits professionnels : internet, téléphonie et vidéosurveillance adaptés à votre activité, avec un seul interlocuteur.",
        ],
        'nos-solutions/services' => [
            "Maintenance et services informatiques | Connectis",
            "Maintenance et services : installation sur site, maintenance préventive, dépannage réactif et contrats SAV pour vos équipements informatiques et télécoms.",
        ],
        'a-propos' => [
            "Connectis Solutions à Nancy : qui sommes-nous ?",
            "Connectis Solutions, SAS basée à Nancy : conseil, installation et maintenance en informatique, vidéosurveillance et téléphonie pour les professionnels.",
        ],
        'devis-contact' => [
            "Devis, financement et contact | Connectis Solutions",
            "Demandez un devis détaillé pour votre projet informatique, vidéosurveillance ou téléphonie, avec solutions de financement. Contact : réponse rapide.",
        ],
        'recrutement' => [
            "Recrutement et candidature | Connectis Solutions",
            "Recrutement : Connectis Solutions cherche une équipe commerciale et technique de terrain à Nancy. Envoyez votre candidature, même spontanée.",
        ],
        'mentions-legales' => [
            "Mentions légales | Connectis Solutions",
            "Mentions légales du site connectis-solutions.fr : éditeur, hébergeur, propriété intellectuelle, données personnelles et droit applicable.",
        ],
        'cgv' => [
            "Conditions générales de vente | Connectis Solutions",
            "Conditions générales de vente de Connectis Solutions applicables aux professionnels : devis, prix, paiement, livraison, garanties et maintenance.",
        ],
        'confidentialite' => [
            "Politique de confidentialité | Connectis Solutions",
            "Comment Connectis Solutions utilise vos données personnelles (devis, contact, candidature), durées de conservation et exercice de vos droits.",
        ],
    ];
}

/**
 * Mots-clés cibles par page, séparés par des virgules (analyse SEOPress ; les « meta keywords » n'existent plus, Google les ignore).
 * Courts et présents tels quels dans le titre, la description et le H1, comme l'analyse les cherche.
 */
function connectis_seo_keywords() {
    return [
        'accueil' => "informatique, vidéosurveillance, téléphonie, connectis",
        'nos-solutions' => "solutions, informatique, vidéosurveillance, téléphonie",
        'nos-solutions/materiel' => "informatique, matériel",
        'nos-solutions/videosurveillance' => "vidéosurveillance, caméras",
        'nos-solutions/telephonie' => "téléphonie, standard",
        'nos-solutions/fibre' => "fibre optique, internet",
        'nos-solutions/abonnements' => "abonnements, forfaits",
        'nos-solutions/services' => "maintenance, services",
        'a-propos' => "connectis, nancy",
        'devis-contact' => "devis, contact",
        'recrutement' => "recrutement, candidature",
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

// Pages en double issues des premières exécutions du contenu de départ (ex. « videosurveillance-2 ») :
// redirigées en 301 vers la page officielle et exclues de l'indexation, tant qu'elles n'ont pas été mises à la corbeille.
add_action('template_redirect', function () {
    if (!is_page()) {
        return;
    }
    $page = get_queried_object();
    if ($page && preg_match('#^nos-solutions/(materiel|videosurveillance|telephonie|fibre|abonnements|services)-\d+$#', get_page_uri($page), $m)) {
        wp_safe_redirect(home_url('/nos-solutions/' . $m[1] . '/'), 301);
        exit;
    }
}, 1);

add_action('init', function () {
    if (get_option('connectis_seo_dupes_v1')) {
        return;
    }
    $dupes = get_posts([
        'post_type'   => 'page',
        'post_status' => 'publish',
        'numberposts' => 50,
        'fields'      => 'ids',
    ]);
    foreach ($dupes as $id) {
        if (preg_match('#^nos-solutions/(materiel|videosurveillance|telephonie|fibre|abonnements|services)-\d+$#', get_page_uri($id))) {
            update_post_meta($id, '_seopress_robots_index', 'yes'); // « yes » = noindex dans SEOPress
        }
    }
    update_option('connectis_seo_dupes_v1', 1);
}, 90);
