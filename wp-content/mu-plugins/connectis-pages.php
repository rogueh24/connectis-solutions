<?php
/**
 * Plugin Name: Connectis — Pages du site (contenu versionné)
 * Description: Génère le contenu des pages publiques (accueil, offres, devis, à propos, recrutement) à partir de composants (icônes animées, cartes, étapes, bloc de confiance) et corrige les mentions légales. Réappliqué quand CONNECTIS_PAGES_VERSION change : les modifications faites à la main dans l'éditeur sur ces pages sont alors écrasées.
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// v5 : recrutement enrichi (métiers), bloc de confiance raccourci, visuel recrutement.
const CONNECTIS_PAGES_VERSION = 5;

/* ───────────────────────── Icônes (shortcode [cn_icon name="wifi"]) ───────────────────────── */

function connectis_icons() {
    return [
        'monitor'  => '<rect x="3" y="4" width="18" height="12" rx="2"/><path class="cn-d" d="M7 9h6M7 12h9"/><path d="M8 20h8M12 16v4"/>',
        'server'   => '<rect x="3" y="4" width="18" height="6" rx="1.5"/><rect x="3" y="14" width="18" height="6" rx="1.5"/><circle class="cn-led" cx="7" cy="7" r=".9"/><circle class="cn-led cn-led2" cx="7" cy="17" r=".9"/><path d="M11 7h6M11 17h6"/>',
        'network'  => '<circle class="cn-pulse" cx="12" cy="5" r="2"/><circle cx="5" cy="19" r="2"/><circle cx="19" cy="19" r="2"/><path d="M12 7v5M12 12l-6 5M12 12l6 5"/>',
        'camera'   => '<rect x="3" y="7" width="13" height="10" rx="2"/><path d="M16 11l5-3v8l-5-3"/><circle class="cn-led" cx="6.5" cy="10.5" r=".9"/>',
        'bell'     => '<g class="cn-swing"><path d="M6 16v-5a6 6 0 1112 0v5l2 2H4z"/><path d="M10 20a2 2 0 004 0"/></g>',
        'lock'     => '<rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 118 0v3"/><circle cx="12" cy="15.5" r="1"/>',
        'phone'    => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a2 2 0 01-2 2A16 16 0 013 6a2 2 0 012-2z"/><path class="cn-wave1" d="M15 4.5a4.5 4.5 0 014.5 4.5"/><path class="cn-wave2" d="M15 2a7 7 0 017 7"/>',
        'headset'  => '<path d="M4 14v-2a8 8 0 0116 0v2"/><rect x="3" y="14" width="4" height="6" rx="1.5"/><rect x="17" y="14" width="4" height="6" rx="1.5"/><path d="M19 20a4 4 0 01-4 2h-2"/>',
        'mobile'   => '<rect x="7" y="3" width="10" height="18" rx="2"/><path d="M11 18h2"/><path class="cn-d" d="M10 7h4"/>',
        'wifi'     => '<path class="cn-arc3" d="M2 9a15 15 0 0120 0"/><path class="cn-arc2" d="M5 12.5a10.5 10.5 0 0114 0"/><path class="cn-arc1" d="M8.5 16a5.5 5.5 0 017 0"/><circle cx="12" cy="19.5" r="1"/>',
        'fibre'    => '<path class="cn-flow" d="M3 17c4 0 4-10 9-10s5 10 9 10"/><circle cx="3" cy="17" r="1.6"/><circle cx="21" cy="17" r="1.6"/>',
        'cable'    => '<path d="M4 8h5v8H4zM15 8h5v8h-5zM9 12h6"/>',
        'hdd'      => '<rect x="3" y="8" width="18" height="8" rx="2"/><circle class="cn-led" cx="7" cy="12" r=".9"/><path d="M11 12h6"/>',
        'wrench'   => '<path d="M14.7 6.3a4 4 0 00-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 005.4-5.4l-2.8 2.8-2.2-.6-.6-2.2z"/>',
        'sliders'  => '<path d="M4 6h9M17 6h3M4 12h3M11 12h9M4 18h11M19 18h1"/><circle cx="15" cy="6" r="2"/><circle cx="9" cy="12" r="2"/><circle cx="17" cy="18" r="2"/>',
        'bolt'     => '<path d="M13 2L4 14h7l-1 8 9-12h-7z"/>',
        'doc'      => '<path d="M7 3h7l5 5v13H7z"/><path d="M14 3v5h5M10 13h6M10 17h6"/>',
        'coins'    => '<circle cx="12" cy="12" r="9"/><path d="M15 9a4 4 0 100 6M8 11h5M8 13h5"/>',
        'check'    => '<circle cx="12" cy="12" r="9"/><path class="cn-tick" d="M8 12.5l3 3 5-6"/>',
        'pin'      => '<path d="M12 21s7-6.2 7-11a7 7 0 10-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/>',
        'mail'     => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
        'building' => '<path d="M4 21V5l8-2v18M12 9h8v12M7 9h2M7 13h2M7 17h2M15 13h2M15 17h2"/>',
        'users'    => '<circle cx="9" cy="8" r="3"/><path d="M3 20a6 6 0 0112 0M16 5a3 3 0 010 6M18 20a5 5 0 00-3-4.6"/>',
        'clock'    => '<circle cx="12" cy="12" r="9"/><path class="cn-hand" d="M12 7v5l3 2"/>',
    ];
}

add_shortcode('cn_icon', function ($atts) {
    $name  = isset($atts['name']) ? sanitize_key($atts['name']) : 'check';
    $icons = connectis_icons();
    if (!isset($icons[$name])) {
        return '';
    }
    return '<span class="cn-ico" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false">' . $icons[$name] . '</svg></span>';
});

// Les pages construites ici portent le marqueur <!--cn--> : on désactive l'auto-paragraphe de WordPress,
// qui insérait des </p> parasites autour des images et des icônes.
add_filter('the_content', function ($content) {
    if (strpos($content, '<!--cn-->') !== false) {
        remove_filter('the_content', 'wpautop');
    }
    return $content;
}, 9);
add_filter('the_content', function ($content) {
    if (!has_filter('the_content', 'wpautop')) {
        add_filter('the_content', 'wpautop');
    }
    return $content;
}, 12);

/* ───────────────────────── Composants HTML ───────────────────────── */

function cn_i($name) {
    return '[cn_icon name="' . $name . '"]';
}

function cn_card($icon, $title, $text, $href = '', $more = 'En savoir plus') {
    $link = $href ? "<a class='cn-more' href='" . $href . "'>" . $more . " &rarr;</a>" : '';
    return "<div class='cn-card'>" . cn_i($icon) . "<h3>" . $title . "</h3><p>" . $text . "</p>" . $link . "</div>";
}

function cn_grid(array $cards) {
    return "<div class='cn-grid'>" . implode('', $cards) . "</div>";
}

function cn_steps() {
    $steps = [
        ['Échange sur site', "Nous nous déplaçons chez vous pour comprendre votre besoin et vos locaux."],
        ['Devis détaillé', "Un devis gratuit, clair et chiffré poste par poste."],
        ['Installation', "Livraison, installation et configuration par notre équipe."],
        ['Suivi & SAV', "Prise en main, maintenance et assistance : un interlocuteur unique."],
    ];
    $html = "<div class='cn-steps'>";
    foreach ($steps as $i => $s) {
        $html .= "<div class='cn-step'><span class='cn-num'>" . ($i + 1) . "</span><h3>" . $s[0] . "</h3><p>" . $s[1] . "</p></div>";
    }
    return $html . "</div>";
}

function cn_cta($title, $text = "Décrivez-nous votre projet : devis gratuit et détaillé.") {
    return "<div class='cn-cta'><h2>" . $title . "</h2><p>" . $text . "</p><p><a class='cn-btn' href='/devis-contact/'>Demander un devis</a></p></div>";
}

function cn_finance() {
    return "<div class='cn-finance'>" . cn_i('coins')
        . "<div><h3>Financez votre équipement</h3><p>Vous préférez étaler l'investissement ? Nous établissons des devis détaillés, poste par poste, "
        . "utilisables pour une demande de financement en location (leasing, LOA, LLD) auprès d'un de nos partenaires financiers.</p></div>"
        . "<a class='cn-btn cn-btn-ghost' href='/devis-contact/'>Parler financement</a></div>";
}

function cn_trust() {
    $facts = [
        ['Dénomination', 'CONNECTIS SOLUTIONS (SAS)'],
        ['SIREN', '103 370 557'],
        ['SIRET (siège)', '103 370 557 00011'],
        ['Siège social', "110 boulevard d'Austrasie, 54000 Nancy"],
        ['Immatriculation', 'RCS Nancy'],
        ['Activité (APE)', '62.02A'],
    ];
    $li = '';
    foreach ($facts as $f) {
        $li .= "<li><span>" . $f[0] . "</span><strong>" . $f[1] . "</strong></li>";
    }
    $commit = [
        "Devis détaillé et gratuit",
        "Installation et configuration par notre équipe",
        "Matériel couvert par la garantie constructeur",
        "Un interlocuteur unique, du devis au SAV",
    ];
    $c = '';
    foreach ($commit as $t) {
        $c .= "<li>" . cn_i('check') . "<span>" . $t . "</span></li>";
    }
    return "<div class='cn-trust'><div class='cn-trust-id'><div class='cn-trust-head'>" . cn_i('building')
        . "<div><h3>Une entreprise vérifiable</h3><p>Nos informations légales sont publiques : vous pouvez les contrôler à tout moment.</p></div></div>"
        . "<ul class='cn-facts'>" . $li . "</ul>"
        . "<a class='cn-btn cn-btn-ghost' href='https://annuaire-entreprises.data.gouv.fr/entreprise/connectis-solutions-103370557' target='_blank' rel='noopener'>Vérifier sur l'Annuaire des Entreprises</a></div>"
        . "<div class='cn-trust-commit'><h3>Nos engagements</h3><ul>" . $c . "</ul></div></div>";
}

function cn_strip() {
    $items = [
        ['building', "Société immatriculée", "RCS Nancy · SIREN 103 370 557"],
        ['wrench', "Installation par nos équipes", "Sur site, clé en main"],
        ['check', "Garantie constructeur", "Sur le matériel installé"],
        ['users', "Interlocuteur unique", "Du devis au SAV"],
    ];
    $html = "<div class='cn-strip'>";
    foreach ($items as $i) {
        $html .= "<div class='cn-strip-item'>" . cn_i($i[0]) . "<span><strong>" . $i[1] . "</strong>" . $i[2] . "</span></div>";
    }
    return $html . "</div>";
}

function cn_faq(array $items) {
    $html = "<div class='cn-faqlist'>";
    foreach ($items as $qa) {
        $html .= "<details class='cn-faq'><summary>" . $qa[0] . "</summary><p>" . $qa[1] . "</p></details>";
    }
    return $html . "</div>";
}

function cn_faq_common() {
    return [
        ["Comment obtenir un devis ?", "Remplissez le formulaire ou écrivez-nous : nous revenons vers vous rapidement, puis nous nous déplaçons pour étudier votre besoin et établir un devis détaillé et gratuit."],
        ["Intervenez-vous directement chez nous ?", "Oui. Notre équipe se déplace dans vos locaux pour le conseil, l'installation et la mise en service."],
        ["Puis-je financer l'équipement en location (leasing) ?", "Oui, c'est possible : nous établissons des devis détaillés, poste par poste, utilisables pour une demande de financement auprès d'un de nos partenaires financiers. Nos informations légales (SIREN, RCS) sont publiques, et un extrait Kbis peut être communiqué sur demande."],
        ["Quelles garanties sur le matériel ?", "Le matériel installé bénéficie de la garantie constructeur. Un contrat de maintenance peut être souscrit séparément pour un suivi dans la durée."],
    ];
}

function cn_img($name) {
    return content_url('mu-plugins/connectis-content-seed/images/' . $name . '.jpg?v=' . CONNECTIS_PAGES_VERSION);
}

function cn_hero($image, $alt) {
    return "<figure class='cn-hero'><img src='" . cn_img($image) . "' alt='" . $alt . "' loading='lazy'></figure>";
}

/* ───────────────────────── Contenu des pages ───────────────────────── */

function connectis_pages_definitions() {
    $forms = (array) get_option('connectis_forms_ids', []);
    $f_devis   = (int) ($forms['devis'] ?? 0);
    $f_contact = (int) ($forms['contact'] ?? 0);
    $f_cand    = (int) ($forms['candidature'] ?? 0);
    $shortcode = function ($id, $title) {
        return $id ? '[contact-form-7 id="' . $id . '" title="' . $title . '"]' : '<p><em>Formulaire momentanément indisponible.</em></p>';
    };

    $services = [
        'materiel' => [
            'path' => 'nos-solutions/materiel', 'title' => 'Informatique & matériel', 'image' => 'materiel', 'alt' => 'Informatique',
            'intro' => "Postes de travail, réseau, serveurs, périphériques : nous fournissons, installons et configurons le matériel informatique dont votre entreprise a besoin, avec un seul interlocuteur du choix du matériel à sa mise en service.",
            'cards' => [
                ['monitor', 'Postes de travail', "Ordinateurs fixes et portables professionnels, écrans et périphériques, livrés configurés."],
                ['network', 'Réseau & Wi-Fi', "Routeurs, switchs et points d'accès : un réseau fiable, câblé et paramétré."],
                ['server', 'Serveurs & sauvegarde', "Stockage, sauvegardes automatiques et continuité de votre activité."],
                ['phone', 'Téléphonie', "Postes, casques et matériel de téléphonie IP pour vos équipes."],
                ['camera', 'Vidéosurveillance', "Caméras, enregistreurs et accessoires compatibles avec votre installation."],
                ['wrench', 'Mise en service incluse', "Installation, configuration et prise en main par notre équipe technique."],
            ],
            'finance' => true, 'cta' => "Un besoin en informatique ?",
        ],
        'videosurveillance' => [
            'path' => 'nos-solutions/videosurveillance', 'title' => 'Vidéosurveillance', 'image' => 'videosurveillance', 'alt' => 'Vidéosurveillance',
            'intro' => "Protégez vos locaux professionnels avec des solutions de vidéosurveillance fiables et évolutives, dimensionnées selon la taille de votre site et vos contraintes réglementaires.",
            'cards' => [
                ['camera', 'Caméras IP professionnelles', "Intérieur et extérieur, vision nocturne, haute définition."],
                ['hdd', 'Enregistrement & stockage', "Enregistreurs (NVR) et conservation sécurisée des images."],
                ['mobile', 'Accès à distance', "Consultez vos caméras depuis votre smartphone, votre tablette ou votre ordinateur."],
                ['bell', 'Alarme & détection', "Détection de mouvement et alarme intrusion, avec alertes en temps réel."],
                ['lock', "Contrôle d'accès", "Badges, interphonie et gestion des accès à vos locaux."],
                ['doc', 'Respect de la réglementation', "Affichage, durée de conservation et périmètre de filmage : nous vous conseillons."],
            ],
            'finance' => true, 'cta' => "Un besoin en vidéosurveillance ?",
        ],
        'telephonie' => [
            'path' => 'nos-solutions/telephonie', 'title' => 'Téléphonie & standard', 'image' => 'telephonie', 'alt' => 'Téléphonie',
            'intro' => "Modernisez votre communication d'entreprise avec des solutions de téléphonie fixe, mobile et VoIP, adaptées aux TPE comme aux structures multi-sites.",
            'cards' => [
                ['headset', 'Standard téléphonique', "Accueil, renvois, files d'attente : une image professionnelle pour vos appels."],
                ['phone', 'Téléphonie IP / VoIP', "Lignes et appels via internet, plus souples et plus économiques."],
                ['mobile', 'Téléphonie mobile pro', "Forfaits et terminaux pour vos équipes nomades."],
                ['users', 'Postes & casques', "Matériel adapté à l'open space, au bureau ou au télétravail."],
                ['sliders', 'Paramétrage complet', "Configuration du standard, des groupes d'appel et des messages d'accueil."],
                ['wrench', 'Installation & prise en main', "Mise en service sur site et accompagnement de vos équipes."],
            ],
            'finance' => true, 'cta' => "Un besoin en téléphonie ?",
        ],
        'fibre' => [
            'path' => 'nos-solutions/fibre', 'title' => 'Internet & Fibre optique', 'image' => 'fibre', 'alt' => 'Fibre optique',
            'intro' => "Une connexion internet stable et rapide est indispensable à l'activité de votre entreprise. Nous nous occupons du raccordement, de l'équipement et du réglage de votre réseau.",
            'cards' => [
                ['fibre', 'Raccordement fibre', "Accès fibre optique professionnel pour vos locaux."],
                ['wifi', 'Wi-Fi professionnel', "Couverture multi-salles, sécurisée et configurée pour vos usages."],
                ['network', 'Box entreprise & secours', "Box adaptées aux professionnels et solutions de secours 4G/5G."],
                ['cable', 'Câblage réseau', "Prises, baies et brassage propres et documentés."],
                ['wrench', 'Mise en service', "Tests, réglages et validation avec vous."],
                ['pin', 'Étude sur site', "Analyse de vos locaux et de vos usages avant toute proposition."],
            ],
            'finance' => false, 'cta' => "Un besoin en connexion ?",
        ],
        'abonnements' => [
            'path' => 'nos-solutions/abonnements', 'title' => 'Abonnements & forfaits', 'image' => 'abonnements', 'alt' => 'Abonnements',
            'intro' => "Des formules simples et transparentes pour votre internet, votre téléphonie ou votre vidéosurveillance, adaptées à la taille de votre structure.",
            'cards' => [
                ['wifi', 'Internet & fibre', "Forfaits entreprise adaptés à vos besoins."],
                ['phone', 'Téléphonie', "Forfaits fixes, mobiles et lignes IP."],
                ['camera', 'Vidéosurveillance', "Abonnements de stockage et de suivi."],
                ['doc', 'Contrats clairs', "Durée, prix et conditions détaillés dans votre devis."],
                ['coins', 'Facturation claire', "Une facture lisible et un seul interlocuteur."],
                ['sliders', 'Formules adaptées', "Une offre dimensionnée selon la taille de votre structure."],
            ],
            'finance' => true, 'cta' => "Un projet d'abonnement ?",
        ],
        'services' => [
            'path' => 'nos-solutions/services', 'title' => 'Services & maintenance', 'image' => 'services', 'alt' => 'Services et maintenance',
            'intro' => "Notre équipe intervient directement chez vous pour l'installation de vos équipements, puis assure leur maintenance dans la durée pour limiter les interruptions d'activité.",
            'cards' => [
                ['wrench', 'Installation sur site', "Pose, câblage et configuration par notre équipe technique."],
                ['sliders', 'Maintenance préventive', "Contrôles réguliers pour éviter les pannes."],
                ['bolt', 'Dépannage réactif', "Une intervention rapide quand un équipement s'arrête."],
                ['headset', 'Contrats SAV', "Un suivi contractuel et une assistance dédiée."],
                ['clock', 'Suivi dans la durée', "Un interlocuteur qui connaît votre dossier."],
                ['doc', 'Devis détaillé', "Chaque intervention ou contrat est chiffré clairement avant de démarrer."],
            ],
            'finance' => false, 'cta' => "Un besoin de maintenance ?",
        ],
    ];

    $pages = [];

    $faq_extra = [
        'materiel' => ["Le matériel est-il configuré avant la livraison ?", "La configuration et la mise en service sont incluses : vos équipements sont installés prêts à l'emploi."],
        'videosurveillance' => ["La vidéosurveillance est-elle encadrée par la loi ?", "Oui : l'information des personnes, la durée de conservation des images et le périmètre de filmage sont réglementés. Nous vous conseillons lors de l'étude de votre site."],
        'telephonie' => ["La téléphonie IP demande-t-elle une bonne connexion ?", "Oui, une connexion internet stable est nécessaire. Nous pouvons vérifier votre accès et, si besoin, le fiabiliser (fibre, secours 4G/5G)."],
        'fibre' => ["Quelle connexion choisir pour mon entreprise ?", "Cela dépend de vos usages et de votre local : nous étudions votre besoin sur site avant de vous proposer une solution."],
        'abonnements' => ["Comment la durée et les conditions sont-elles précisées ?", "Chaque devis et chaque contrat indiquent clairement la durée, le prix et les conditions de résiliation."],
        'services' => ["Comment demander une intervention ?", "Contactez-nous par le formulaire ou par e-mail en décrivant l'équipement concerné : nous vous répondons rapidement."],
    ];

    foreach ($services as $key => $s) {
        $cards = [];
        foreach ($s['cards'] as $c) {
            $cards[] = cn_card($c[0], $c[1], $c[2]);
        }
        $pages[$s['path']] = [
            'title'   => $s['title'],
            'content' => "<!--cn-->" . cn_hero($s['image'], $s['alt']) . cn_strip()
                . "<p class='cn-lead'>" . $s['intro'] . "</p>"
                . "<h2>Ce que nous proposons</h2>" . cn_grid($cards)
                . "<h2>Comment ça se passe</h2>" . cn_steps()
                . ($s['finance'] ? cn_finance() : '')
                . "<h2>Questions fréquentes</h2>" . cn_faq(array_merge([$faq_extra[$key]], cn_faq_common()))
                . cn_cta($s['cta']),
        ];
    }

    // Accueil
    $expertises = "<div class='cn-features'>"
        . "<div class='cn-feature'><figure><img src='" . cn_img('materiel') . "' alt='Informatique' loading='lazy'></figure><div class='cn-feature-body'><h3>Informatique</h3><p>Postes de travail, réseau, serveurs et périphériques : fourniture, installation et configuration incluses.</p><a href='/nos-solutions/materiel/'>Découvrir &rarr;</a></div></div>"
        . "<div class='cn-feature'><figure><img src='" . cn_img('videosurveillance') . "' alt='Vidéosurveillance' loading='lazy'></figure><div class='cn-feature-body'><h3>Vidéosurveillance</h3><p>Caméras IP, enregistreurs, consultation à distance, alarme et contrôle d'accès pour vos locaux.</p><a href='/nos-solutions/videosurveillance/'>Découvrir &rarr;</a></div></div>"
        . "<div class='cn-feature'><figure><img src='" . cn_img('telephonie') . "' alt='Téléphonie' loading='lazy'></figure><div class='cn-feature-body'><h3>Téléphonie</h3><p>Standard, VoIP, lignes fixes et mobiles : une communication claire pour toute l'équipe.</p><a href='/nos-solutions/telephonie/'>Découvrir &rarr;</a></div></div>"
        . "</div>";
    $also = cn_grid([
        cn_card('fibre', 'Internet & Fibre optique', "Raccordement fibre professionnel, box internet, Wi-Fi pro.", '/nos-solutions/fibre/'),
        cn_card('doc', 'Abonnements & forfaits', "Formules d'abonnement adaptées à votre activité.", '/nos-solutions/abonnements/'),
        cn_card('wrench', 'Services & maintenance', "Installation, maintenance, dépannage, contrats SAV.", '/nos-solutions/services/'),
    ]);
    $why = cn_grid([
        cn_card('pin', 'Proximité', "Une équipe commerciale qui se déplace directement dans vos locaux, où que vous soyez."),
        cn_card('clock', 'Réactivité', "Des rendez-vous et des interventions sous délai court, sans centre d'appels national."),
        cn_card('sliders', 'Sur mesure', "Des solutions adaptées à votre activité, pas un forfait imposé."),
        cn_card('users', 'Guichet unique', "Informatique, vidéosurveillance et téléphonie : un seul interlocuteur pour tout gérer."),
        cn_card('doc', 'Devis clair', "Un chiffrage détaillé poste par poste, pour savoir précisément ce que vous financez."),
        cn_card('headset', 'Suivi après installation', "Maintenance, dépannage et assistance : nous restons votre interlocuteur une fois le projet livré."),
    ]);
    $pages['accueil'] = [
        'title'   => 'Accueil',
        'content' => "<!--cn-->"
            . "<div class='cn-hero-home'><span class='cn-kicker'>Informatique · Vidéosurveillance · Téléphonie</span>"
            . "<h1>Informatique, vidéosurveillance et téléphonie pour votre entreprise</h1>"
            . "<p>Un seul interlocuteur local : nous conseillons, installons et assurons le suivi directement chez vous.</p>"
            . "<p><a class='cn-btn' href='/devis-contact/'>Demander un devis</a> <a class='cn-btn cn-btn-ghost' href='/nos-solutions/'>Découvrir nos solutions</a></p></div>"
            . cn_strip()
            . "<h2>Nos expertises</h2>" . $expertises
            . "<h2>Et aussi</h2>" . $also
            . "<h2>Comment ça se passe</h2>" . cn_steps()
            . "<h2>Pourquoi Connectis Solutions</h2>" . $why
            . cn_finance()
            . cn_trust()
            . cn_cta("Un projet ? Parlons-en."),
    ];

    // Nos solutions
    $pages['nos-solutions'] = [
        'title'   => 'Nos solutions',
        'content' => "<!--cn-->"
            . "<p class='cn-lead'>Connectis Solutions accompagne les professionnels sur trois métiers principaux — l'<strong>informatique</strong>, la <strong>vidéosurveillance</strong> et la <strong>téléphonie</strong> — complétés par la fibre, les abonnements et la maintenance.</p>"
            . "<h2>Nos expertises</h2>" . $expertises
            . "<h2>Et aussi</h2>" . $also
            . cn_finance()
            . cn_cta("Un projet ? Parlons-en."),
    ];

    // À propos
    $pages['a-propos'] = [
        'title'   => 'À propos',
        'content' => "<!--cn-->" . cn_hero('a-propos', 'Poste de travail informatique moderne')
            . "<p class='cn-lead'>Connectis Solutions est une entreprise basée à Nancy, dédiée aux professionnels. Nous concevons, installons et maintenons leurs solutions d'<strong>informatique</strong>, de <strong>vidéosurveillance</strong> et de <strong>téléphonie</strong>, avec la fibre optique, le matériel et les abonnements associés.</p>"
            . "<p>Notre équipe commerciale se déplace directement chez vous pour le conseil, le devis et l'installation — pas de centre d'appels, pas de parcours standardisé : un interlocuteur qui connaît votre dossier du premier contact au suivi après installation.</p>"
            . "<h2>Nos valeurs</h2>" . cn_grid([
                cn_card('pin', 'Proximité', "Une équipe locale, disponible et joignable."),
                cn_card('clock', 'Réactivité', "Des délais courts, sans passer par un centre d'appels national."),
                cn_card('sliders', 'Sur mesure', "Des solutions pensées pour votre activité, pas un forfait imposé."),
            ])
            . cn_trust()
            . cn_cta("Travaillons ensemble."),
    ];

    // Devis & Contact (une seule colonne)
    $pages['devis-contact'] = [
        'title'   => 'Devis & Contact',
        'content' => "<!--cn--><div class='cn-narrow'>" . cn_strip()
            . "<p class='cn-lead'>Une question, un projet ? Décrivez-nous votre besoin : notre équipe vous répond rapidement avec un devis clair et gratuit.</p>"
            . "<ul class='cn-chips'><li>" . cn_i('check') . "Devis gratuit et détaillé</li><li>" . cn_i('clock') . "Réponse rapide</li><li>" . cn_i('users') . "Interlocuteur unique</li></ul>"
            . "<div class='cn-form-card'><h2>Demande de devis</h2>" . $shortcode($f_devis, 'Demande de devis') . "<div class='cn-secure'><div class='cn-secure-in'>" . cn_i('lock') . "<span class='cn-secure-t'>Vos données servent uniquement à traiter votre demande. Aucune revente, aucune newsletter non sollicitée.</span></div></div></div>"
            . "<h2>Nous contacter directement</h2><div class='cn-contact-row'>"
            . "<a class='cn-mini' href='mailto:contact@connectis-solutions.fr'>" . cn_i('mail') . "<span><strong>E-mail</strong>contact@connectis-solutions.fr</span></a>"
            . "<div class='cn-mini'>" . cn_i('pin') . "<span><strong>Adresse</strong>110 boulevard d'Austrasie, 54000 Nancy</span></div>"
            . "<div class='cn-mini'>" . cn_i('wrench') . "<span><strong>Sur site</strong>Nous nous déplaçons dans vos locaux</span></div></div>"
            . "<details class='cn-faq'><summary>Une question générale ou un SAV ?</summary><div class='cn-form-card'>" . $shortcode($f_contact, 'Contact') . "</div></details>"
            . cn_trust()
            . "</div>",
    ];

    // Recrutement
    $pages['recrutement'] = [
        'title'   => 'Recrutement',
        'content' => "<!--cn-->" . cn_hero('recrutement', 'Poste de travail informatique')
            . "<p class='cn-lead'>Connectis Solutions recrute une équipe commerciale et technique de terrain, animée par la proximité et la réactivité. Envoyez-nous votre candidature, même spontanée.</p>"
            . "<h2>Les métiers</h2>" . cn_grid([
                cn_card('pin', 'Conseil commercial de terrain', "Rencontrer les entreprises, comprendre leur besoin et établir des devis détaillés."),
                cn_card('wrench', 'Installation et configuration', "Installer, câbler et configurer le matériel chez nos clients."),
                cn_card('users', 'Candidature spontanée', "Aucune offre ne correspond ? Présentez-vous : nous étudions chaque profil."),
            ])
            . "<div class='cn-narrow'><div class='cn-form-card'><h2>Candidater</h2>" . $shortcode($f_cand, 'Candidature') . "</div></div>",
    ];

    if (function_exists('connectis_legal_definitions')) {
        $pages = array_merge($pages, connectis_legal_definitions());
    }

    return $pages;
}

/* ───────────────────────── Application (une fois par version) ───────────────────────── */

add_action('init', function () {
    if ((int) get_option('connectis_pages_version', 0) >= CONNECTIS_PAGES_VERSION) {
        return;
    }
    // Les identifiants de formulaires doivent exister (créés par connectis-forms.php à l'init 30).
    if (!get_option('connectis_forms_v1')) {
        return;
    }

    try {
        // Sans utilisateur connecté, WordPress filtre le HTML enregistré (kses) : ce contenu est le nôtre.
        kses_remove_filters();

        foreach (connectis_pages_definitions() as $path => $def) {
            $page = get_page_by_path($path);
            if (!$page) {
                continue;
            }
            wp_update_post(['ID' => $page->ID, 'post_title' => $def['title'], 'post_content' => $def['content']]);
        }

        kses_init();
        update_option('connectis_pages_error', null);
    } catch (\Throwable $e) {
        kses_init();
        update_option('connectis_pages_error', $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    }
    update_option('connectis_pages_version', CONNECTIS_PAGES_VERSION);
}, 60);
