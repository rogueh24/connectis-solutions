<?php
/**
 * Plugin Name: Connectis — Pages légales (contenu versionné)
 * Description: Contenu des mentions légales, des conditions générales de vente et de la politique de confidentialité. Appliqué par connectis-pages.php.
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

function connectis_legal_definitions() {

    $mentions = <<<'HTML'
<!--cn-->
<div class='cn-narrow cn-legal'>
<p class='cn-lead'>Conformément à la loi pour la confiance dans l'économie numérique (LCEN), voici les informations légales relatives au site <strong>connectis-solutions.fr</strong> et à son éditeur.</p>
<nav class='cn-toc' aria-label='Sommaire'><a href='#editeur'>Éditeur</a><a href='#publication'>Publication</a><a href='#hebergeur'>Hébergeur</a><a href='#propriete'>Propriété intellectuelle</a><a href='#donnees'>Données personnelles</a><a href='#responsabilite'>Responsabilité</a><a href='#droit'>Droit applicable</a></nav>

<section id='editeur' class='cn-legal-sec'>
<h2>Éditeur du site</h2>
<ul class='cn-facts'>
<li><span>Dénomination sociale</span><strong>CONNECTIS SOLUTIONS</strong></li>
<li><span>Forme juridique</span><strong>Société par actions simplifiée (SAS)</strong></li>
<li><span>SIREN</span><strong>103 370 557</strong></li>
<li><span>SIRET (siège)</span><strong>103 370 557 00011</strong></li>
<li><span>Immatriculation</span><strong>RCS Nancy</strong></li>
<li><span>Code APE / NAF</span><strong>62.02A — Conseil en systèmes et logiciels informatiques</strong></li>
<li><span>Siège social</span><strong>110 boulevard d'Austrasie, 54000 Nancy</strong></li>
<li><span>Président</span><strong>Arno Dessalle</strong></li>
<li><span>Contact</span><strong><a href='mailto:contact@connectis-solutions.fr'>contact@connectis-solutions.fr</a></strong></li>
</ul>
<p>Activité : conseil, fourniture, installation et maintenance de solutions informatiques, de vidéosurveillance et de téléphonie pour les professionnels.</p>
<p><a class='cn-btn cn-btn-ghost' href='https://annuaire-entreprises.data.gouv.fr/entreprise/connectis-solutions-103370557' target='_blank' rel='noopener'>Vérifier ces informations sur l'Annuaire des Entreprises</a></p>
</section>

<section id='publication' class='cn-legal-sec'>
<h2>Directeur de la publication</h2>
<p>Le directeur de la publication est <strong>Arno Dessalle</strong>, en sa qualité de Président de CONNECTIS SOLUTIONS.</p>
</section>

<section id='hebergeur' class='cn-legal-sec'>
<h2>Hébergement</h2>
<p>Le site est hébergé par <strong>PlanetHoster</strong>, 4416 rue Louis B. Mayer, Laval (Québec) H7P 0G1, Canada — <a href='https://www.planethoster.com' target='_blank' rel='noopener'>www.planethoster.com</a>.</p>
</section>

<section id='propriete' class='cn-legal-sec'>
<h2>Propriété intellectuelle</h2>
<p>La structure du site, les textes, le logo, les graphismes et les éléments de marque de CONNECTIS SOLUTIONS sont protégés par le droit de la propriété intellectuelle. Toute reproduction, représentation ou adaptation, totale ou partielle, sans autorisation écrite préalable est interdite.</p>
<p>Les photographies d'illustration sont issues de la banque d'images libres de droits <a href='https://www.pexels.com' target='_blank' rel='noopener'>Pexels</a>, utilisées conformément à sa licence.</p>
</section>

<section id='donnees' class='cn-legal-sec'>
<h2>Données personnelles et cookies</h2>
<p>Le traitement des données personnelles collectées via les formulaires du site est décrit dans notre <a href='/confidentialite/'>politique de confidentialité</a>. Vous y trouverez la nature des données, leur durée de conservation et l'exercice de vos droits. Le site ne dépose aucun cookie publicitaire ni de mesure d'audience.</p>
</section>

<section id='responsabilite' class='cn-legal-sec'>
<h2>Responsabilité</h2>
<p>Nous nous efforçons de fournir des informations exactes et à jour, sans pouvoir garantir l'absence totale d'erreur ou d'omission. Les descriptions d'offres n'ont pas de valeur contractuelle : seul le devis accepté engage CONNECTIS SOLUTIONS. Le site peut contenir des liens vers des sites tiers dont nous ne maîtrisons pas le contenu.</p>
</section>

<section id='droit' class='cn-legal-sec'>
<h2>Droit applicable</h2>
<p>Le présent site et ses mentions légales sont soumis au droit français. Pour toute question, vous pouvez nous écrire à <a href='mailto:contact@connectis-solutions.fr'>contact@connectis-solutions.fr</a>.</p>
</section>
<p class='cn-updated'>Dernière mise à jour : septembre 2026.</p>
</div>
HTML;

    $cgv = <<<'HTML'
<!--cn-->
<div class='cn-narrow cn-legal'>
<p class='cn-lead'>Les présentes conditions générales de vente (CGV) encadrent les relations entre <strong>CONNECTIS SOLUTIONS</strong> (« le Prestataire ») et ses clients professionnels (« le Client ») pour la vente de matériels, d'abonnements et de prestations d'installation et de maintenance.</p>
<nav class='cn-toc' aria-label='Sommaire'><a href='#art-1'>Objet</a><a href='#art-2'>Devis et commande</a><a href='#art-3'>Prix</a><a href='#art-4'>Paiement</a><a href='#art-5'>Livraison et installation</a><a href='#art-6'>Réception</a><a href='#art-7'>Garanties</a><a href='#art-8'>Maintenance</a><a href='#art-9'>Abonnements</a><a href='#art-10'>Propriété et financement</a><a href='#art-11'>Responsabilité</a><a href='#art-12'>Données</a><a href='#art-13'>Force majeure</a><a href='#art-14'>Litiges</a></nav>

<section id='art-1' class='cn-legal-sec'>
<h2>Article 1 — Objet et champ d'application</h2>
<p>Les CGV s'appliquent à toute commande de produits (matériel informatique, de vidéosurveillance, de téléphonie), d'abonnements et de services (installation, configuration, maintenance, dépannage) passée auprès du Prestataire par un client agissant à titre professionnel. Elles prévalent sur toute condition d'achat du Client, sauf accord écrit et signé des deux parties. Le fait de passer commande emporte adhésion sans réserve aux CGV.</p>
</section>

<section id='art-2' class='cn-legal-sec'>
<h2>Article 2 — Devis et commande</h2>
<p>Toute prestation fait l'objet d'un devis préalable, gratuit, détaillant la nature des produits et services, leurs prix et les délais prévisionnels. Sauf mention contraire, le devis est valable trente (30) jours. La commande est ferme après acceptation écrite du devis (signature ou accord écrit) par le Client, accompagnée le cas échéant de l'acompte prévu.</p>
<p>Toute modification demandée par le Client après acceptation fait l'objet d'un devis complémentaire.</p>
</section>

<section id='art-3' class='cn-legal-sec'>
<h2>Article 3 — Prix</h2>
<p>Les prix sont exprimés en euros hors taxes ; la TVA en vigueur est ajoutée. Les frais éventuels de déplacement, de livraison ou de mise en service sont indiqués au devis. Les abonnements et services récurrents sont facturés selon la périodicité prévue au contrat.</p>
</section>

<section id='art-4' class='cn-legal-sec'>
<h2>Article 4 — Facturation et paiement</h2>
<p>Les conditions de règlement (acompte, échéances) sont précisées au devis. À défaut de stipulation contraire, les factures sont payables à trente (30) jours à compter de leur date d'émission. Aucun escompte n'est accordé pour paiement anticipé.</p>
<p>En cas de retard de paiement, des pénalités sont exigibles de plein droit, sans rappel, à un taux égal à trois fois le taux d'intérêt légal, ainsi qu'une indemnité forfaitaire de quarante (40) euros pour frais de recouvrement (articles L.441-10 et D.441-5 du Code de commerce), sans préjudice de tout complément si les frais engagés sont supérieurs.</p>
</section>

<section id='art-5' class='cn-legal-sec'>
<h2>Article 5 — Livraison, installation et obligations du Client</h2>
<p>Les délais de livraison et d'installation sont communiqués à titre indicatif lors du devis et confirmés à la commande ; leur dépassement n'ouvre droit ni à annulation ni à indemnité, sauf faute prouvée du Prestataire.</p>
<p>Le Client garantit l'accès aux locaux, une alimentation électrique et, le cas échéant, un accès internet conformes aux prérequis communiqués. Il effectue les sauvegardes de ses données avant toute intervention. Pour la vidéosurveillance, le Client reste responsable de la conformité de l'exploitation du système (information des personnes, durée de conservation des images, formalités éventuelles) ; le Prestataire le conseille lors de l'étude du site.</p>
</section>

<section id='art-6' class='cn-legal-sec'>
<h2>Article 6 — Réception</h2>
<p>La réception intervient à l'issue de l'installation et de la mise en service, le cas échéant par la signature d'un procès-verbal. Toute anomalie apparente ou non-conformité doit être signalée par écrit dans un délai de huit (8) jours suivant la réception ; à défaut, la prestation est réputée acceptée.</p>
</section>

<section id='art-7' class='cn-legal-sec'>
<h2>Article 7 — Garanties</h2>
<p>Le matériel installé bénéficie de la garantie du constructeur, dans les conditions et durées propres à chaque produit, ainsi que de la garantie légale des vices cachés (articles 1641 et suivants du Code civil). Sont exclus de la garantie : l'usure normale, les dommages résultant d'une mauvaise utilisation, d'une intervention de tiers non autorisés, d'un défaut d'alimentation électrique, de la foudre ou de tout événement extérieur.</p>
</section>

<section id='art-8' class='cn-legal-sec'>
<h2>Article 8 — Maintenance et assistance</h2>
<p>La maintenance préventive ou corrective, le dépannage et l'assistance font l'objet d'un contrat séparé qui précise le périmètre, les délais d'intervention et le prix. En l'absence de contrat, les interventions sont réalisées sur demande et facturées selon le devis.</p>
</section>

<section id='art-9' class='cn-legal-sec'>
<h2>Article 9 — Abonnements et services récurrents</h2>
<p>La durée, le prix, les conditions de renouvellement et de résiliation de chaque abonnement (internet, téléphonie, vidéosurveillance et services associés) sont précisés au devis et au contrat. Les services fournis par des opérateurs ou éditeurs tiers sont en outre soumis à leurs propres conditions, dont le Client reconnaît avoir pris connaissance.</p>
</section>

<section id='art-10' class='cn-legal-sec'>
<h2>Article 10 — Propriété du matériel et financement en location</h2>
<p>Le matériel reste la propriété du Prestataire jusqu'au paiement intégral du prix. Lorsque le Client finance l'équipement par un contrat de location (crédit-bail, location avec option d'achat ou location longue durée), le Prestataire établit sur demande les documents usuels (devis détaillé, facture, procès-verbal de livraison et de conformité) ; le transfert de propriété au profit de l'organisme de financement s'opère dans les conditions du contrat de vente conclu avec lui. L'accord de financement peut être stipulé condition de la commande.</p>
</section>

<section id='art-11' class='cn-legal-sec'>
<h2>Article 11 — Responsabilité</h2>
<p>La responsabilité du Prestataire est limitée aux dommages directs et prévisibles résultant d'un manquement à ses obligations, et ne saurait excéder le montant hors taxes payé par le Client au titre de la prestation concernée. Le Prestataire n'est pas responsable des dommages indirects (perte d'exploitation, de données, de chiffre d'affaires, atteinte à l'image).</p>
</section>

<section id='art-12' class='cn-legal-sec'>
<h2>Article 12 — Données personnelles</h2>
<p>Les données personnelles collectées dans le cadre de la relation commerciale sont traitées conformément à la <a href='/confidentialite/'>politique de confidentialité</a>.</p>
</section>

<section id='art-13' class='cn-legal-sec'>
<h2>Article 13 — Force majeure</h2>
<p>Aucune des parties n'est responsable d'un manquement dû à un cas de force majeure au sens de l'article 1218 du Code civil (notamment panne d'opérateur, coupure d'énergie, grève, intempéries, rupture d'approvisionnement du constructeur). L'exécution est suspendue pendant la durée de l'événement.</p>
</section>

<section id='art-14' class='cn-legal-sec'>
<h2>Article 14 — Droit applicable et litiges</h2>
<p>Les CGV sont soumises au droit français. En cas de différend, les parties recherchent d'abord une solution amiable. À défaut, le <strong>tribunal de commerce compétent du ressort de Nancy</strong> est seul compétent, y compris en cas de pluralité de défendeurs ou d'appel en garantie.</p>
</section>
<p class='cn-updated'>Version en vigueur : septembre 2026.</p>
</div>
HTML;

    $privacy = <<<'HTML'
<!--cn-->
<div class='cn-narrow cn-legal'>
<p class='cn-lead'>CONNECTIS SOLUTIONS protège les données personnelles de ses clients, prospects et candidats. Cette page explique quelles données nous collectons, pourquoi, combien de temps nous les conservons et comment exercer vos droits (RGPD).</p>
<nav class='cn-toc' aria-label='Sommaire'><a href='#responsable'>Responsable</a><a href='#donnees'>Données et finalités</a><a href='#destinataires'>Destinataires</a><a href='#cookies'>Cookies</a><a href='#droits'>Vos droits</a><a href='#securite'>Sécurité</a></nav>

<section id='responsable' class='cn-legal-sec'>
<h2>Responsable du traitement</h2>
<p><strong>CONNECTIS SOLUTIONS</strong> (SAS), 110 boulevard d'Austrasie, 54000 Nancy — SIREN 103 370 557. Contact pour toute question relative aux données : <a href='mailto:contact@connectis-solutions.fr'>contact@connectis-solutions.fr</a>.</p>
</section>

<section id='donnees' class='cn-legal-sec'>
<h2>Données collectées, finalités et durées de conservation</h2>
<div class='cn-table-wrap'>
<table class='cn-table'>
<thead><tr><th>Formulaire</th><th>Données collectées</th><th>Finalité</th><th>Base légale</th><th>Conservation</th></tr></thead>
<tbody>
<tr><td>Demande de devis</td><td>Nom, e-mail, téléphone, entreprise, besoin, message</td><td>Répondre à la demande, établir un devis, suivi commercial</td><td>Mesures précontractuelles, intérêt légitime</td><td>3 ans après le dernier contact</td></tr>
<tr><td>Contact / SAV</td><td>Nom, e-mail, objet, message</td><td>Répondre à votre message</td><td>Intérêt légitime</td><td>3 ans après le dernier contact</td></tr>
<tr><td>Candidature</td><td>Nom, e-mail, téléphone, poste, CV, message</td><td>Étudier votre candidature</td><td>Mesures précontractuelles</td><td>2 ans maximum après le dernier contact</td></tr>
</tbody>
</table>
</div>
<p>Vos données ne sont ni vendues, ni cédées à des tiers à des fins commerciales. Les journaux techniques du serveur (adresse IP, date de connexion) sont conservés par l'hébergeur pour la sécurité du site et conformément à la loi.</p>
</section>

<section id='destinataires' class='cn-legal-sec'>
<h2>Destinataires</h2>
<p>Vos données sont destinées aux seules personnes habilitées de CONNECTIS SOLUTIONS. Elles peuvent être traitées par nos prestataires techniques strictement nécessaires au fonctionnement du site et de la messagerie, notamment notre hébergeur PlanetHoster (voir les <a href='/mentions-legales/'>mentions légales</a>).</p>
</section>

<section id='cookies' class='cn-legal-sec'>
<h2>Cookies</h2>
<p>Ce site n'utilise que les cookies techniques nécessaires à son fonctionnement et à sa sécurité. Aucun cookie publicitaire ni de mesure d'audience n'est déposé. Si de tels outils étaient ajoutés, un bandeau vous permettrait d'accepter ou de refuser avant tout dépôt.</p>
</section>

<section id='droits' class='cn-legal-sec'>
<h2>Vos droits</h2>
<p>Vous disposez d'un droit d'accès, de rectification, d'effacement, d'opposition, de limitation et de portabilité de vos données. Pour l'exercer, écrivez à <a href='mailto:contact@connectis-solutions.fr'>contact@connectis-solutions.fr</a> en joignant, si nécessaire, un justificatif d'identité ; nous répondons dans un délai d'un mois.</p>
<p>Si vous estimez, après nous avoir contactés, que vos droits ne sont pas respectés, vous pouvez introduire une réclamation auprès de la CNIL : <a href='https://www.cnil.fr' target='_blank' rel='noopener'>www.cnil.fr</a> — 3 place de Fontenoy, TSA 80715, 75334 Paris Cedex 07.</p>
</section>

<section id='securite' class='cn-legal-sec'>
<h2>Sécurité</h2>
<p>Le site est servi en HTTPS, protégé contre le spam par des mesures techniques, et l'accès à l'administration est restreint. Les demandes reçues par formulaire sont transmises par e-mail à l'équipe de CONNECTIS SOLUTIONS.</p>
</section>
<p class='cn-updated'>Dernière mise à jour : septembre 2026.</p>
</div>
HTML;

    return [
        'mentions-legales' => ['title' => 'Mentions légales', 'content' => $mentions],
        'cgv'              => ['title' => 'Conditions générales de vente', 'content' => $cgv],
        'confidentialite'  => ['title' => 'Politique de confidentialité', 'content' => $privacy],
    ];
}
