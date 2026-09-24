<?php
/**
 * Plugin Name: Connectis — Contenu initial du site
 * Description: Crée automatiquement les pages, le menu et les formulaires de devis/contact/recrutement du site vitrine, à partir du cahier des charges. Ne s'exécute qu'une seule fois (idempotent).
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function () {

    if (get_option('connectis_content_seeded_v2')) {
        return;
    }

    try {

    // Le CMS doit être prêt (types de contenu + Contact Form 7 enregistrés).
    if (!post_type_exists('page')) {
        return;
    }

    $img = function ($name) {
        return content_url('mu-plugins/connectis-content-seed/images/' . $name . '.jpg');
    };

    // ---------------------------------------------------------------
    // 1. Formulaires Contact Form 7 (créés avant les pages qui les référencent)
    // ---------------------------------------------------------------

    $form_ids = function_exists('connectis_forms_create') ? connectis_forms_create() : [];

    $cf7 = function ($key, $title) use ($form_ids) {
        if (empty($form_ids[$key])) {
            return '<p><em>Formulaire indisponible (Contact Form 7 non actif).</em></p>';
        }
        return '[contact-form-7 id="' . intval($form_ids[$key]) . '" title="' . esc_attr($title) . '"]';
    };

    // ---------------------------------------------------------------
    // 2. Contenu réutilisable
    // ---------------------------------------------------------------
    $solutions = [
        'videosurveillance' => [
            'title' => 'Vidéosurveillance',
            'excerpt' => 'Caméras IP professionnelles, enregistreurs, contrôle d\'accès.',
            'intro' => "Protégez vos locaux professionnels avec des solutions de vidéosurveillance fiables et évolutives, dimensionnées selon la taille de votre site et vos contraintes réglementaires.",
            'points' => [
                'Caméras IP professionnelles (intérieur / extérieur, vision nocturne)',
                'Enregistreurs (NVR) et stockage sécurisé',
                'Vidéosurveillance et consultation à distance depuis smartphone ou ordinateur',
                'Alarme intrusion et détection de mouvement',
                'Contrôle d\'accès (badges, interphonie)',
            ],
        ],
        'telephonie' => [
            'title' => 'Téléphonie & standard',
            'excerpt' => 'Téléphonie fixe, VoIP/IP, standard pour entreprises, mobile.',
            'intro' => "Modernisez votre communication d'entreprise avec des solutions de téléphonie fixe, mobile et VoIP, adaptées aux TPE comme aux structures multi-sites.",
            'points' => [
                'Téléphonie fixe et VoIP/IP',
                'Standard téléphonique pour entreprises (accueil, renvoi, files d\'attente)',
                'Téléphonie mobile professionnelle',
                'Postes et combinés adaptés à l\'open space ou au télétravail',
            ],
        ],
        'fibre' => [
            'title' => 'Internet & Fibre optique',
            'excerpt' => 'Raccordement fibre professionnel, box internet, Wi-Fi pro.',
            'intro' => "Une connexion internet stable et rapide est indispensable à l'activité de votre entreprise. Nous nous occupons du raccordement, de l'équipement et du réglage de votre réseau.",
            'points' => [
                'Raccordement fibre optique professionnel',
                'Box internet entreprise et solutions de secours (4G/5G)',
                'Wi-Fi professionnel sécurisé, couverture multi-salles',
                'Câblage réseau et mise en service',
            ],
        ],
        'materiel' => [
            'title' => 'Matériel informatique & télécom',
            'excerpt' => 'Vente et installation de matériel, configuration incluse.',
            'intro' => "Nous fournissons, installons et configurons le matériel informatique et télécom dont votre entreprise a besoin, avec un seul interlocuteur du choix du matériel à sa mise en service.",
            'points' => [
                'Ordinateurs, postes de travail et périphériques professionnels',
                'Matériel réseau (routeurs, switchs, points d\'accès Wi-Fi)',
                'Matériel de téléphonie et de vidéosurveillance',
                'Configuration et mise en service incluses',
            ],
        ],
        'abonnements' => [
            'title' => 'Abonnements & forfaits',
            'excerpt' => 'Formules d\'abonnement adaptées à votre activité.',
            'intro' => "Des formules simples et transparentes pour votre internet, votre téléphonie ou votre vidéosurveillance, adaptées à la taille de votre structure.",
            'points' => [
                'Forfaits internet et téléphonie entreprise',
                'Abonnements vidéosurveillance (stockage cloud, télésurveillance)',
                'Contrats clairs : durée et conditions détaillées dans votre devis',
                'Facturation claire, un seul interlocuteur',
            ],
        ],
        'services' => [
            'title' => 'Services & maintenance',
            'excerpt' => 'Installation, maintenance, dépannage, contrats SAV.',
            'intro' => "Notre équipe intervient directement chez vous pour l'installation de vos équipements, puis assure leur maintenance dans la durée pour limiter les interruptions d'activité.",
            'points' => [
                'Installation sur site par notre équipe technique',
                'Maintenance préventive et corrective',
                'Dépannage réactif',
                'Contrats SAV et suivi dans la durée',
            ],
        ],
    ];

    $service_cards_html = '';
    foreach ($solutions as $slug => $s) {
        $service_cards_html .= '<div style="flex:1 1 280px;border:1px solid #e2e8f0;border-radius:10px;padding:24px;">' .
            '<h3 style="margin-top:0;">' . esc_html($s['title']) . '</h3>' .
            '<p>' . esc_html($s['excerpt']) . '</p>' .
            '<p><a href="/nos-solutions/' . esc_attr($slug) . '/">En savoir plus &rarr;</a></p>' .
            '</div>';
    }

    // ---------------------------------------------------------------
    // 3. Pages
    // ---------------------------------------------------------------

    // Helper : crée la page si elle n'existe pas déjà, retourne son ID.
    $make_page = function ($slug, $title, $content, $parent_id = 0) {
        $existing = get_page_by_path($slug);
        $data = [
            'post_title'   => $title,
            'post_name'    => $slug,
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_parent'  => $parent_id,
            'comment_status' => 'closed',
        ];
        if ($existing) {
            $data['ID'] = $existing->ID;
            return wp_update_post($data);
        }
        return wp_insert_post($data);
    };

    // --- Accueil ---
    $accueil_content = '
<div style="background:linear-gradient(135deg,#070d1f 0%,#123a8c 100%);color:#fff;padding:64px 32px;border-radius:12px;text-align:center;">
  <h1 style="color:#fff;font-size:2.2rem;margin-top:0;">Sécurité, connectivité et équipement pour votre entreprise</h1>
  <p style="font-size:1.15rem;color:#cfd8e8;max-width:640px;margin:0 auto 24px;">Vidéosurveillance, téléphonie, fibre optique, matériel informatique : un seul interlocuteur, une équipe qui se déplace chez vous.</p>
  <p><a href="/devis-contact/" style="background:#1b63e6;color:#fff;padding:14px 28px;border-radius:8px;text-decoration:none;font-weight:600;">Demander un devis</a></p>
</div>

<h2 style="margin-top:48px;">Nos solutions</h2>
<div style="display:flex;flex-wrap:wrap;gap:20px;">' . $service_cards_html . '</div>

<h2 style="margin-top:48px;">Pourquoi Connectis Solutions</h2>
<div style="display:flex;flex-wrap:wrap;gap:24px;">
  <div style="flex:1 1 220px;"><h3>Proximité</h3><p>Une équipe commerciale qui se déplace directement dans vos locaux, où que vous soyez.</p></div>
  <div style="flex:1 1 220px;"><h3>Réactivité</h3><p>Des rendez-vous et des interventions sous délai court, sans centre d\'appels national.</p></div>
  <div style="flex:1 1 220px;"><h3>Sur mesure</h3><p>Des solutions adaptées à votre activité, pas un forfait imposé.</p></div>
  <div style="flex:1 1 220px;"><h3>Guichet unique</h3><p>Sécurité, connectivité et matériel : un seul interlocuteur pour tout gérer.</p></div>
</div>

<div style="background:#eef4fd;border-radius:12px;padding:32px;text-align:center;margin-top:48px;">
  <h2 style="margin-top:0;">Un projet ? Parlons-en.</h2>
  <p><a href="/devis-contact/" style="background:#1b63e6;color:#fff;padding:14px 28px;border-radius:8px;text-decoration:none;font-weight:600;">Demander un devis gratuit</a></p>
</div>
';
    $accueil_id = $make_page('accueil', 'Accueil', $accueil_content);

    // --- Nos solutions (page pilier) ---
    $solutions_content = '<p>Connectis Solutions accompagne les professionnels autour de six familles de solutions, du conseil à la maintenance.</p>' .
        '<div style="display:flex;flex-wrap:wrap;gap:20px;margin-top:24px;">' . $service_cards_html . '</div>';
    $solutions_id = $make_page('nos-solutions', 'Nos solutions', $solutions_content);

    foreach ($solutions as $slug => $s) {
        $points_html = '<ul>';
        foreach ($s['points'] as $p) {
            $points_html .= '<li>' . esc_html($p) . '</li>';
        }
        $points_html .= '</ul>';

        $content = '<img src="' . esc_url($img($slug)) . '" alt="' . esc_attr($s['title']) . '" style="width:100%;max-height:380px;object-fit:cover;border-radius:12px;margin-bottom:24px;" />' .
            '<p style="font-size:1.1rem;">' . esc_html($s['intro']) . '</p>' .
            '<h2>Ce que nous proposons</h2>' . $points_html .
            '<div style="background:#eef4fd;border-radius:12px;padding:28px;text-align:center;margin-top:32px;">' .
            '<p style="margin-top:0;"><strong>Un besoin en ' . esc_html(mb_strtolower($s['title'])) . ' ?</strong></p>' .
            '<p><a href="/devis-contact/" style="background:#1b63e6;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;">Demander un devis</a></p>' .
            '</div>';

        $make_page($slug, $s['title'], $content, $solutions_id);
    }

    // --- À propos ---
    $apropos_content = '
<img src="' . esc_url($img('a-propos')) . '" alt="Équipe Connectis Solutions" style="width:100%;max-height:380px;object-fit:cover;border-radius:12px;margin-bottom:24px;" />
<p style="font-size:1.1rem;">Connectis Solutions est une entreprise de solutions informatiques et télécoms dédiée aux professionnels, basée à Nancy. Nous accompagnons les entreprises dans la sécurisation, la connectivité et l\'équipement de leurs locaux : vidéosurveillance, téléphonie, internet et fibre optique, vente de matériel et abonnements associés.</p>
<p>Notre équipe commerciale se déplace directement chez vous pour le conseil, le devis et l\'installation — pas de centre d\'appels, pas de parcours standardisé : un interlocuteur qui connaît votre dossier du premier contact au suivi après installation.</p>

<h2>Nos valeurs</h2>
<div style="display:flex;flex-wrap:wrap;gap:24px;">
  <div style="flex:1 1 220px;"><h3>Proximité</h3><p>Une équipe locale, disponible et joignable.</p></div>
  <div style="flex:1 1 220px;"><h3>Réactivité</h3><p>Des délais courts, sans passer par un centre d\'appels national.</p></div>
  <div style="flex:1 1 220px;"><h3>Sur mesure</h3><p>Des solutions pensées pour votre activité, pas un forfait imposé.</p></div>
</div>
';
    $make_page('a-propos', 'À propos', $apropos_content);

    // --- Devis & Contact ---
    $devis_content = '
<p style="font-size:1.1rem;">Une question, un projet ? Décrivez-nous votre besoin, notre équipe commerciale vous recontacte rapidement.</p>
<div style="display:flex;flex-wrap:wrap;gap:40px;margin-top:24px;">
  <div style="flex:2 1 380px;">
    <h2>Demande de devis</h2>
    ' . $cf7('devis', 'Demande de devis') . '
  </div>
  <div style="flex:1 1 260px;">
    <h2>Nos coordonnées</h2>
    <p><strong>E-mail :</strong> <a href="mailto:contact@connectis-solutions.fr">contact@connectis-solutions.fr</a></p>
    <p><strong>Adresse :</strong><br />110 Boulevard d\'Austrasie<br />54000 Nancy</p>
  </div>
</div>
<hr style="margin:48px 0;" />
<h2>Une question générale ou un SAV ?</h2>
' . $cf7('contact', 'Contact') . '
';
    $make_page('devis-contact', 'Devis & Contact', $devis_content);

    // --- Recrutement ---
    $recrutement_content = '
<img src="' . esc_url($img('recrutement')) . '" alt="Rejoindre Connectis Solutions" style="width:100%;max-height:380px;object-fit:cover;border-radius:12px;margin-bottom:24px;" />
<p style="font-size:1.1rem;">Connectis Solutions recrute une équipe commerciale et technique de terrain, animée par la proximité et la réactivité. Envoyez-nous votre candidature, même spontanée.</p>
<h2>Candidater</h2>
' . $cf7('candidature', 'Candidature') . '
';
    $make_page('recrutement', 'Recrutement', $recrutement_content);

    // --- Mentions légales ---
    $mentions_content = '
<h2>Éditeur du site</h2>
<p>
CONNECTIS SOLUTIONS — Société par actions simplifiée (SAS)<br />
Siège social : 110 Boulevard d\'Austrasie, 54000 Nancy<br />
SIREN : 103 370 557 — SIRET (siège) : 103 370 557 00011<br />
RCS Nancy<br />
Code APE : 62.02A — Conseil en systèmes et logiciels informatiques<br />
Président : M. Arno Dessalle<br />
Contact : <a href="mailto:contact@connectis-solutions.fr">contact@connectis-solutions.fr</a>
</p>

<h2>Hébergement</h2>
<p>Le site connectis-solutions.fr est hébergé par PlanetHoster. <em>(Coordonnées complètes de l\'hébergeur à vérifier et compléter auprès de PlanetHoster avant mise en ligne définitive.)</em></p>

<h2>Directeur de la publication</h2>
<p>M. Arno Dessalle, Président de CONNECTIS SOLUTIONS.</p>

<h2>Propriété intellectuelle</h2>
<p>L\'ensemble des contenus présents sur ce site (textes, images, logos) est la propriété de CONNECTIS SOLUTIONS, sauf mention contraire, et ne peut être reproduit sans autorisation préalable.</p>

<p><em>Document généré automatiquement à partir des données publiques du Registre national des entreprises — à faire relire par un professionnel avant publication définitive.</em></p>
';
    $make_page('mentions-legales', 'Mentions légales', $mentions_content);

    // --- CGV ---
    $cgv_content = '
<p><em>Modèle de trame à faire valider par un professionnel du droit avant publication définitive.</em></p>

<h2>Article 1 — Objet</h2>
<p>Les présentes conditions générales de vente régissent les relations contractuelles entre CONNECTIS SOLUTIONS et ses clients professionnels dans le cadre de la vente de matériel, d\'abonnements et de prestations de services (installation, maintenance) présentés sur le site connectis-solutions.fr ou proposés par son équipe commerciale.</p>

<h2>Article 2 — Devis et commande</h2>
<p>Toute prestation fait l\'objet d\'un devis préalable, gratuit, détaillant la nature des produits ou services, leur prix et les délais prévisionnels. La commande n\'est considérée comme ferme qu\'après acceptation écrite du devis par le client.</p>

<h2>Article 3 — Prix et paiement</h2>
<p>Les prix sont exprimés en euros. Les modalités de paiement (comptant, échelonné, abonnement) sont précisées sur chaque devis.</p>

<h2>Article 4 — Installation et délais</h2>
<p>Les délais d\'installation sont communiqués à titre indicatif lors du devis et confirmés à la commande.</p>

<h2>Article 5 — Garantie et SAV</h2>
<p>Le matériel installé bénéficie des garanties constructeur en vigueur. Un contrat de maintenance peut être souscrit séparément.</p>

<h2>Article 6 — Résiliation des abonnements</h2>
<p>Les conditions de durée et de résiliation sont précisées sur chaque contrat d\'abonnement.</p>

<h2>Article 7 — Litiges</h2>
<p>En cas de litige, une solution amiable sera recherchée en priorité. À défaut, les tribunaux compétents seront ceux du ressort du siège social de CONNECTIS SOLUTIONS.</p>
';
    $make_page('cgv', 'Conditions générales de vente', $cgv_content);

    // --- Politique de confidentialité ---
    $confidentialite_content = '
<p><em>Document de référence à adapter à mesure que de nouveaux outils (statistiques, cookies tiers...) seront ajoutés au site.</em></p>

<h2>Responsable du traitement</h2>
<p>CONNECTIS SOLUTIONS, 110 Boulevard d\'Austrasie, 54000 Nancy — <a href="mailto:contact@connectis-solutions.fr">contact@connectis-solutions.fr</a></p>

<h2>Données collectées</h2>
<p>Les formulaires du site (demande de devis, contact, candidature) collectent : nom, e-mail, téléphone, et selon le formulaire, l\'entreprise, le service concerné, un message libre, ou un CV.</p>

<h2>Finalité</h2>
<p>Ces données sont utilisées exclusivement pour répondre à votre demande (devis, prise de contact, recrutement) et assurer le suivi commercial associé. Elles ne sont ni vendues, ni transmises à des tiers à des fins commerciales.</p>

<h2>Durée de conservation</h2>
<p>Les données sont conservées pendant la durée nécessaire au traitement de la demande, puis archivées ou supprimées conformément aux obligations légales.</p>

<h2>Vos droits</h2>
<p>Conformément au RGPD, vous disposez d\'un droit d\'accès, de rectification, d\'effacement et d\'opposition sur vos données. Pour l\'exercer, contactez <a href="mailto:contact@connectis-solutions.fr">contact@connectis-solutions.fr</a>.</p>

<h2>Cookies</h2>
<p>Le site n\'utilise à ce jour aucun cookie de suivi ou de mesure d\'audience tiers. Cette page sera mise à jour, avec bandeau de consentement, si de tels outils sont ajoutés.</p>
';
    $make_page('confidentialite', 'Politique de confidentialité', $confidentialite_content);

    // ---------------------------------------------------------------
    // 4. Page d'accueil statique + menu
    // ---------------------------------------------------------------
    if ($accueil_id) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $accueil_id);
    }

    if (!wp_get_nav_menu_object('Menu principal')) {
        $menu_id = wp_create_nav_menu('Menu principal');

        $nav_items = [
            ['title' => 'Accueil', 'slug' => 'accueil'],
            ['title' => 'Nos solutions', 'slug' => 'nos-solutions'],
            ['title' => 'À propos', 'slug' => 'a-propos'],
            ['title' => 'Devis & Contact', 'slug' => 'devis-contact'],
            ['title' => 'Recrutement', 'slug' => 'recrutement'],
        ];

        foreach ($nav_items as $item) {
            $page = get_page_by_path($item['slug']);
            if ($page) {
                wp_update_nav_menu_item($menu_id, 0, [
                    'menu-item-title'     => $item['title'],
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $page->ID,
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ]);
            }
        }

        $locations = get_theme_mod('nav_menu_locations', []);
        $locations['menu_1'] = $menu_id;
        $locations['menu_mobile'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    if (!wp_get_nav_menu_object('Menu pied de page')) {
        $footer_menu_id = wp_create_nav_menu('Menu pied de page');
        $footer_items = [
            ['title' => 'Mentions légales', 'slug' => 'mentions-legales'],
            ['title' => 'CGV', 'slug' => 'cgv'],
            ['title' => 'Politique de confidentialité', 'slug' => 'confidentialite'],
        ];
        foreach ($footer_items as $item) {
            $page = get_page_by_path($item['slug']);
            if ($page) {
                wp_update_nav_menu_item($footer_menu_id, 0, [
                    'menu-item-title'     => $item['title'],
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $page->ID,
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ]);
            }
        }
        $locations = get_theme_mod('nav_menu_locations', []);
        $locations['footer'] = $footer_menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    // Nettoyage du contenu de démonstration WordPress par défaut.
    $sample = get_page_by_path('sample-page');
    if ($sample) {
        wp_delete_post($sample->ID, true);
    }
    $hello_posts = get_posts(['post_type' => 'post', 'title' => 'Hello world!', 'numberposts' => 1, 'post_status' => 'any']);
    if (!empty($hello_posts)) {
        wp_delete_post($hello_posts[0]->ID, true);
    }

        update_option('connectis_content_seeded_v2', 1);
    } catch (\Throwable $e) {
        update_option('connectis_content_seed_error', $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    }
}, 20);
