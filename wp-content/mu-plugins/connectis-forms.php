<?php
/**
 * Plugin Name: Connectis — Formulaires (Contact Form 7)
 * Description: Crée les formulaires de devis, contact et candidature, puis branche leurs shortcodes dans les pages. Idempotent, tolérant aux erreurs. Dépend de Contact Form 7.
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Crée les 3 formulaires et retourne leurs identifiants ['devis'=>id, 'contact'=>id, 'candidature'=>id].
 * Retourne [] si Contact Form 7 n'est pas chargé.
 */
function connectis_forms_create() {
    $form_ids = [];

    if (class_exists('WPCF7_ContactForm')) {

        // --- Formulaire Devis ---
        $devis = WPCF7_ContactForm::get_template(['title' => 'Demande de devis']);
        $props = $devis->get_properties();
        $props['form'] = <<<'FORM'
<p><label>Nom et prénom (obligatoire)<br />
[text* your-name] </label></p>

<p><label>E-mail (obligatoire)<br />
[email* your-email] </label></p>

<p><label>Téléphone<br />
[tel your-tel] </label></p>

<p><label>Entreprise<br />
[text your-company] </label></p>

<p><label>Service concerné (obligatoire)<br />
[select* your-service "Vidéosurveillance" "Téléphonie & standard" "Internet & Fibre optique" "Matériel informatique & télécom" "Abonnements & forfaits" "Services & maintenance" "Autre / plusieurs services"] </label></p>

<p><label>Décrivez votre besoin<br />
[textarea your-message] </label></p>

[submit "Envoyer ma demande de devis"]
FORM;
        $props['mail']['subject'] = 'Nouvelle demande de devis — [your-service]';
        $props['mail']['sender'] = '[your-name] <wordpress@connectis-solutions.fr>';
        $props['mail']['body'] = "Nouvelle demande de devis depuis le site connectis-solutions.fr\n\n" .
            "Nom : [your-name]\nE-mail : [your-email]\nTéléphone : [your-tel]\nEntreprise : [your-company]\n" .
            "Service concerné : [your-service]\n\nMessage :\n[your-message]";
        $props['mail']['recipient'] = get_option('admin_email');
        $props['mail']['additional_headers'] = "Reply-To: [your-email]";
        $devis->set_properties($props);
        $devis->set_locale('fr_FR');
        $devis->save();
        $form_ids['devis'] = $devis->id();

        // --- Formulaire Contact ---
        $contact = WPCF7_ContactForm::get_template(['title' => 'Contact']);
        $props = $contact->get_properties();
        $props['form'] = <<<'FORM'
<p><label>Nom et prénom (obligatoire)<br />
[text* your-name] </label></p>

<p><label>E-mail (obligatoire)<br />
[email* your-email] </label></p>

<p><label>Objet<br />
[text your-subject] </label></p>

<p><label>Message (obligatoire)<br />
[textarea* your-message] </label></p>

[submit "Envoyer"]
FORM;
        $props['mail']['subject'] = 'Nouveau message — [your-subject]';
        $props['mail']['sender'] = '[your-name] <wordpress@connectis-solutions.fr>';
        $props['mail']['body'] = "Nouveau message depuis le formulaire de contact\n\n" .
            "Nom : [your-name]\nE-mail : [your-email]\nObjet : [your-subject]\n\nMessage :\n[your-message]";
        $props['mail']['recipient'] = get_option('admin_email');
        $props['mail']['additional_headers'] = "Reply-To: [your-email]";
        $contact->set_properties($props);
        $contact->set_locale('fr_FR');
        $contact->save();
        $form_ids['contact'] = $contact->id();

        // --- Formulaire Candidature ---
        $candidature = WPCF7_ContactForm::get_template(['title' => 'Candidature']);
        $props = $candidature->get_properties();
        $props['form'] = <<<'FORM'
<p><label>Nom et prénom (obligatoire)<br />
[text* your-name] </label></p>

<p><label>E-mail (obligatoire)<br />
[email* your-email] </label></p>

<p><label>Téléphone<br />
[tel your-tel] </label></p>

<p><label>Poste souhaité<br />
[text your-poste] </label></p>

<p><label>Votre CV (PDF)<br />
[file your-cv limit:5mb filetypes:pdf] </label></p>

<p><label>Message<br />
[textarea your-message] </label></p>

[submit "Envoyer ma candidature"]
FORM;
        $props['mail']['subject'] = 'Nouvelle candidature — [your-poste]';
        $props['mail']['sender'] = '[your-name] <wordpress@connectis-solutions.fr>';
        $props['mail']['body'] = "Nouvelle candidature depuis le site\n\nNom : [your-name]\nE-mail : [your-email]\n" .
            "Téléphone : [your-tel]\nPoste souhaité : [your-poste]\n\nMessage :\n[your-message]";
        $props['mail']['recipient'] = get_option('admin_email');
        $props['mail']['additional_headers'] = "Reply-To: [your-email]";
        $props['mail']['attachments'] = '[your-cv]';
        $candidature->set_properties($props);
        $candidature->set_locale('fr_FR');
        $candidature->save();
        $form_ids['candidature'] = $candidature->id();
    }

    return $form_ids;
}

// Réparation : les pages « Devis & Contact » et « Recrutement » ont été générées alors que
// Contact Form 7 était inactif (marqueur « Formulaire indisponible »). Une fois CF7 actif,
// on crée les formulaires et on remplace ces marqueurs par les vrais shortcodes.
add_action('init', function () {
    if (get_option('connectis_forms_v1') || !class_exists('WPCF7_ContactForm') || !post_type_exists('wpcf7_contact_form')) {
        return;
    }

    try {
        $ids = connectis_forms_create();
        if (count($ids) !== 3) {
            update_option('connectis_forms_error', 'Formulaires incomplets : ' . wp_json_encode($ids));
            return;
        }

        $shortcode = function ($key, $title) use ($ids) {
            return '[contact-form-7 id="' . intval($ids[$key]) . '" title="' . esc_attr($title) . '"]';
        };
        $placeholder = '<p><em>Formulaire indisponible (Contact Form 7 non actif).</em></p>';

        // Page → liste ordonnée des formulaires attendus à la place des marqueurs.
        $pages = [
            'devis-contact' => [['devis', 'Demande de devis'], ['contact', 'Contact']],
            'recrutement'   => [['candidature', 'Candidature']],
        ];

        foreach ($pages as $slug => $forms) {
            $page = get_page_by_path($slug);
            if (!$page) {
                continue;
            }
            $content = $page->post_content;
            foreach ($forms as $form) {
                $pos = strpos($content, $placeholder);
                if ($pos === false) {
                    break; // page déjà modifiée à la main : on ne touche à rien
                }
                $content = substr_replace($content, $shortcode($form[0], $form[1]), $pos, strlen($placeholder));
            }
            if ($content !== $page->post_content) {
                wp_update_post(['ID' => $page->ID, 'post_content' => $content]);
            }
        }

        update_option('connectis_forms_ids', $ids);
        update_option('connectis_forms_v1', 1);
    } catch (\Throwable $e) {
        update_option('connectis_forms_error', $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    }
}, 30);

/* ───────── Mise en page des formulaires : une seule colonne, tuiles de choix, consentement RGPD ───────── */

function connectis_forms_layouts() {
    $consent = '<p class="cn-consent">[acceptance your-consent] J\'accepte que mes données soient utilisées pour traiter ma demande, conformément à la <a href="/confidentialite/">politique de confidentialité</a>. [/acceptance]</p>';

    $devis = <<<'FORM'
<div class="cn-fstep"><span class="cn-num">1</span><h3>Votre besoin</h3></div>
[radio your-service use_label_element default:1 "Informatique et matériel" "Vidéosurveillance" "Téléphonie et VoIP" "Internet et fibre optique" "Abonnements et forfaits" "Services et maintenance" "Plusieurs services / autre"]

<div class="cn-fstep"><span class="cn-num">2</span><h3>Vos coordonnées</h3></div>
<p class="cn-field"><label>Nom et prénom *<br>[text* your-name autocomplete:name]</label></p>
<p class="cn-field"><label>E-mail *<br>[email* your-email autocomplete:email]</label></p>
<p class="cn-field"><label>Téléphone<br>[tel your-tel autocomplete:tel]</label></p>
<p class="cn-field"><label>Entreprise<br>[text your-company autocomplete:organization]</label></p>

<div class="cn-fstep"><span class="cn-num">3</span><h3>Votre projet</h3></div>
<p class="cn-field"><label>Décrivez brièvement votre besoin<br>[textarea your-message x6 placeholder "Nombre de postes, de caméras ou de lignes, délais souhaités, financement envisagé…"]</label></p>
{{CONSENT}}
[submit "Envoyer ma demande de devis"]
FORM;

    $contact = <<<'FORM'
<p class="cn-field"><label>Nom et prénom *<br>[text* your-name autocomplete:name]</label></p>
<p class="cn-field"><label>E-mail *<br>[email* your-email autocomplete:email]</label></p>
<p class="cn-field"><label>Objet<br>[text your-subject]</label></p>
<p class="cn-field"><label>Message *<br>[textarea* your-message x5]</label></p>
{{CONSENT}}
[submit "Envoyer"]
FORM;

    $candidature = <<<'FORM'
<p class="cn-field"><label>Nom et prénom *<br>[text* your-name autocomplete:name]</label></p>
<p class="cn-field"><label>E-mail *<br>[email* your-email autocomplete:email]</label></p>
<p class="cn-field"><label>Téléphone<br>[tel your-tel autocomplete:tel]</label></p>
<p class="cn-field"><label>Poste souhaité<br>[text your-poste]</label></p>
<p class="cn-field"><label>Votre CV (PDF, 5 Mo maximum)<br>[file your-cv limit:5mb filetypes:pdf]</label></p>
<p class="cn-field"><label>Message<br>[textarea your-message x5]</label></p>
{{CONSENT}}
[submit "Envoyer ma candidature"]
FORM;

    return [
        'devis'       => str_replace('{{CONSENT}}', $consent, $devis),
        'contact'     => str_replace('{{CONSENT}}', $consent, $contact),
        'candidature' => str_replace('{{CONSENT}}', $consent, $candidature),
    ];
}

add_action('init', function () {
    if (get_option('connectis_forms_layout_v1') || !get_option('connectis_forms_v1') || !class_exists('WPCF7_ContactForm') || !post_type_exists('wpcf7_contact_form')) {
        return;
    }
    try {
        $ids = (array) get_option('connectis_forms_ids', []);
        foreach (connectis_forms_layouts() as $key => $template) {
            if (empty($ids[$key])) {
                continue;
            }
            $form = WPCF7_ContactForm::get_instance((int) $ids[$key]);
            if (!$form) {
                continue;
            }
            $props = $form->get_properties();
            $props['form'] = $template;
            $form->set_properties($props);
            $form->save();
        }
        update_option('connectis_forms_layout_error', null);
    } catch (\Throwable $e) {
        update_option('connectis_forms_layout_error', $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    }
    update_option('connectis_forms_layout_v1', 1);
}, 31);
