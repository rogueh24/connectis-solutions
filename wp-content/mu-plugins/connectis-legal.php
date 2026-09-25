<?php
/**
 * Plugin Name: Connectis — Pages légales (contenu versionné)
 * Description: Contenu des mentions légales, des conditions générales de vente et de la politique de confidentialité. Appliqué par connectis-pages.php.
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Capital social (mention obligatoire pour une SAS) : à renseigner d'après le Kbis, ex. '1 000 €'.
// Tant que la valeur est vide, la ligne n'est pas affichée.
const CONNECTIS_CAPITAL_SOCIAL = '';

function connectis_legal_definitions() {

    $mentions = <<<'HTML'
<!--cn-->
<div class='cn-narrow cn-legal'>
<p class='cn-lead'>Conformément aux articles 6-III et 19 de la loi n° 2004-575 du 21 juin 2004 pour la confiance dans l'économie numérique (LCEN), voici les informations légales relatives au site <strong>connectis-solutions.fr</strong> et à son éditeur.</p>
<nav class='cn-toc' aria-label='Sommaire'><a href='#editeur'>Éditeur</a><a href='#publication'>Publication</a><a href='#hebergeur'>Hébergeur</a><a href='#propriete'>Propriété intellectuelle</a><a href='#liens'>Liens</a><a href='#donnees'>Données personnelles</a><a href='#responsabilite'>Responsabilité</a><a href='#signalement'>Signalement</a><a href='#droit'>Droit applicable</a></nav>

<section id='editeur' class='cn-legal-sec'>
<h2>1. Éditeur du site</h2>
<ul class='cn-facts'>
<li><span>Dénomination sociale</span><strong>CONNECTIS SOLUTIONS</strong></li>
<li><span>Forme juridique</span><strong>Société par actions simplifiée (SAS)</strong></li>
{{CAPITAL_LI}}<li><span>Siège social</span><strong>110 boulevard d'Austrasie, 54000 Nancy, France</strong></li>
<li><span>SIREN</span><strong>103 370 557</strong></li>
<li><span>SIRET (siège)</span><strong>103 370 557 00011</strong></li>
<li><span>Immatriculation</span><strong>Registre du commerce et des sociétés de Nancy</strong></li>
<li><span>TVA intracommunautaire</span><strong>FR64 103 370 557</strong></li>
<li><span>Code APE / NAF</span><strong>62.02A — Conseil en systèmes et logiciels informatiques</strong></li>
<li><span>Date de création</span><strong>26 mars 2026</strong></li>
<li><span>Président</span><strong>Arno Dessalle</strong></li>
<li><span>Contact</span><strong><a href='mailto:contact@connectis-solutions.fr'>contact@connectis-solutions.fr</a></strong></li>
</ul>
<p>Activité : conseil, fourniture, installation et maintenance de solutions informatiques, de vidéosurveillance et de téléphonie à destination des professionnels. L'activité n'est soumise à aucune autorisation administrative particulière ni à aucun ordre professionnel.</p>
<p>Ces informations sont publiques et vérifiables à tout moment auprès des registres officiels.</p>
<p><a class='cn-btn cn-btn-ghost' href='https://annuaire-entreprises.data.gouv.fr/entreprise/connectis-solutions-103370557' target='_blank' rel='noopener'>Vérifier ces informations sur l'Annuaire des Entreprises</a></p>
</section>

<section id='publication' class='cn-legal-sec'>
<h2>2. Direction de la publication</h2>
<p>Le directeur de la publication du site est <strong>Arno Dessalle</strong>, Président de CONNECTIS SOLUTIONS. Il peut être joint à l'adresse <a href='mailto:contact@connectis-solutions.fr'>contact@connectis-solutions.fr</a>.</p>
</section>

<section id='hebergeur' class='cn-legal-sec'>
<h2>3. Hébergement</h2>
<p>Le site est hébergé par <strong>PlanetHoster</strong>, 4416 rue Louis B. Mayer, Laval (Québec) H7P 0G1, Canada — <a href='https://www.planethoster.com' target='_blank' rel='noopener'>www.planethoster.com</a>.</p>
</section>

<section id='propriete' class='cn-legal-sec'>
<h2>4. Propriété intellectuelle</h2>
<p><span class='cn-cl'>4.1</span> L'ensemble des éléments du site — structure, architecture, textes, charte graphique, logo, dénomination « Connectis Solutions », bases de données, code et éléments de marque — est protégé par le Code de la propriété intellectuelle et demeure la propriété exclusive de CONNECTIS SOLUTIONS ou de ses concédants.</p>
<p><span class='cn-cl'>4.2</span> Toute reproduction, représentation, extraction, adaptation ou diffusion, totale ou partielle, par quelque procédé que ce soit, sans l'autorisation écrite préalable de CONNECTIS SOLUTIONS, est interdite et constitue une contrefaçon sanctionnée par les articles L.335-2 et suivants du Code de la propriété intellectuelle.</p>
<p><span class='cn-cl'>4.3</span> Les photographies d'illustration proviennent de la banque d'images libres de droits <a href='https://www.pexels.com' target='_blank' rel='noopener'>Pexels</a> et sont utilisées conformément à sa licence. Les marques et logos de tiers éventuellement mentionnés appartiennent à leurs titulaires respectifs.</p>
</section>

<section id='liens' class='cn-legal-sec'>
<h2>5. Liens hypertextes</h2>
<p><span class='cn-cl'>5.1</span> Le site peut renvoyer vers des sites tiers (par exemple l'Annuaire des Entreprises de l'État). CONNECTIS SOLUTIONS n'exerce aucun contrôle sur leur contenu et décline toute responsabilité quant aux informations, produits ou services qui y sont proposés.</p>
<p><span class='cn-cl'>5.2</span> Un lien vers la page d'accueil du site est autorisé à condition qu'il ne porte pas atteinte à l'image de CONNECTIS SOLUTIONS, n'induise pas en erreur sur l'origine du contenu et n'emploie pas de technique de cadrage (framing). Toute autre utilisation nécessite une autorisation écrite.</p>
</section>

<section id='donnees' class='cn-legal-sec'>
<h2>6. Données personnelles et cookies</h2>
<p><span class='cn-cl'>6.1</span> Les données personnelles collectées par les formulaires du site (devis, contact, candidature) sont traitées par CONNECTIS SOLUTIONS, responsable du traitement, conformément au règlement (UE) 2016/679 (RGPD) et à la loi « Informatique et Libertés » du 6 janvier 1978 modifiée. La nature des données, les finalités, les durées de conservation et les modalités d'exercice de vos droits sont décrites dans la <a href='/confidentialite/'>politique de confidentialité</a>.</p>
<p><span class='cn-cl'>6.2</span> Vous disposez d'un droit d'accès, de rectification, d'effacement, d'opposition, de limitation et de portabilité, exerçable à l'adresse <a href='mailto:contact@connectis-solutions.fr'>contact@connectis-solutions.fr</a>. Vous pouvez également saisir la CNIL (<a href='https://www.cnil.fr' target='_blank' rel='noopener'>www.cnil.fr</a>).</p>
<p><span class='cn-cl'>6.3</span> Le site n'utilise que des cookies strictement nécessaires à son fonctionnement et à sa sécurité, exemptés de consentement. Il ne dépose aucun cookie publicitaire ni traceur de mesure d'audience.</p>
</section>

<section id='responsabilite' class='cn-legal-sec'>
<h2>7. Responsabilité</h2>
<p><span class='cn-cl'>7.1</span> CONNECTIS SOLUTIONS s'efforce de fournir des informations exactes et à jour mais ne peut garantir l'absence d'erreur, d'omission ou de retard de mise à jour. Les informations et descriptions d'offres présentées sur le site sont données à titre indicatif et n'ont pas de valeur contractuelle : seuls le devis accepté et les <a href='/cgv/'>conditions générales de vente</a> engagent CONNECTIS SOLUTIONS.</p>
<p><span class='cn-cl'>7.2</span> L'accès au site peut être interrompu, notamment pour maintenance, sans préavis et sans que cela ouvre droit à indemnité. CONNECTIS SOLUTIONS ne saurait être tenue responsable des dommages résultant de l'accès au site ou de son utilisation, notamment d'un virus, d'une intrusion frauduleuse ou d'une défaillance du réseau.</p>
</section>

<section id='signalement' class='cn-legal-sec'>
<h2>8. Signalement d'un contenu illicite</h2>
<p>Tout contenu manifestement illicite peut être signalé à <a href='mailto:contact@connectis-solutions.fr'>contact@connectis-solutions.fr</a> en précisant l'adresse de la page concernée et les motifs du signalement (article 6-I-5 de la LCEN).</p>
</section>

<section id='droit' class='cn-legal-sec'>
<h2>9. Droit applicable et juridiction</h2>
<p>Le présent site et les présentes mentions légales sont soumis au droit français. À défaut de résolution amiable, tout litige relève des tribunaux français compétents.</p>
</section>
<p class='cn-updated'>Dernière mise à jour : 25 septembre 2026.</p>
</div>
HTML;

    $cgv = <<<'HTML'
<!--cn-->
<div class='cn-narrow cn-legal'>
<p class='cn-lead'>Conditions générales de vente de <strong>CONNECTIS SOLUTIONS</strong> applicables aux clients professionnels pour la vente de matériels, la fourniture d'abonnements et les prestations d'installation et de maintenance en informatique, vidéosurveillance et téléphonie.</p>
<nav class='cn-toc' aria-label='Sommaire'><a href='#art-1'>Art. 1</a><a href='#art-2'>Art. 2</a><a href='#art-3'>Art. 3</a><a href='#art-4'>Art. 4</a><a href='#art-5'>Art. 5</a><a href='#art-6'>Art. 6</a><a href='#art-7'>Art. 7</a><a href='#art-8'>Art. 8</a><a href='#art-9'>Art. 9</a><a href='#art-10'>Art. 10</a><a href='#art-11'>Art. 11</a><a href='#art-12'>Art. 12</a><a href='#art-13'>Art. 13</a><a href='#art-14'>Art. 14</a><a href='#art-15'>Art. 15</a><a href='#art-16'>Art. 16</a><a href='#art-17'>Art. 17</a><a href='#art-18'>Art. 18</a><a href='#art-19'>Art. 19</a></nav>

<section id='art-1' class='cn-legal-sec'>
<h2>Article 1 — Objet, champ d'application et définitions</h2>
<p><span class='cn-cl'>1.1</span> Les présentes conditions générales de vente (les « CGV ») régissent les relations contractuelles entre la société <strong>CONNECTIS SOLUTIONS</strong>, société par actions simplifiée dont le siège social est situé 110 boulevard d'Austrasie, 54000 Nancy, immatriculée au RCS de Nancy sous le numéro 103 370 557 (le « Prestataire »), et toute personne physique ou morale agissant à titre professionnel (le « Client ») qui lui commande des produits ou des services.</p>
<p><span class='cn-cl'>1.2</span> Elles s'appliquent à la vente de matériels informatiques, de vidéosurveillance et de téléphonie (les « Produits »), et à la fourniture de prestations d'étude, d'installation, de configuration, de maintenance, d'assistance, ainsi que d'abonnements et services récurrents (les « Services »).</p>
<p><span class='cn-cl'>1.3</span> Les CGV sont destinées exclusivement aux professionnels. Elles constituent le socle unique de la négociation commerciale et sont communiquées à tout Client qui en fait la demande, conformément à l'article L.441-1 du Code de commerce. Elles prévalent sur toute condition générale d'achat du Client, sauf dérogation expresse et écrite du Prestataire.</p>
</section>

<section id='art-2' class='cn-legal-sec'>
<h2>Article 2 — Documents contractuels et acceptation</h2>
<p><span class='cn-cl'>2.1</span> Le contrat est constitué, par ordre de priorité décroissant : (i) le devis accepté et ses annexes, (ii) le cas échéant, le contrat de maintenance ou d'abonnement, (iii) les présentes CGV.</p>
<p><span class='cn-cl'>2.2</span> La signature du devis ou toute commande passée par écrit (y compris par courrier électronique) emporte adhésion pleine et entière aux CGV, dont le Client reconnaît avoir pris connaissance avant la commande. Le Prestataire peut modifier ses CGV ; les CGV applicables sont celles en vigueur à la date de la commande.</p>
<p><span class='cn-cl'>2.3</span> Les échanges par courrier électronique ont valeur d'écrit ; les registres informatisés du Prestataire font foi entre les parties, sauf preuve contraire.</p>
</section>

<section id='art-3' class='cn-legal-sec'>
<h2>Article 3 — Devis, commande et formation du contrat</h2>
<p><span class='cn-cl'>3.1</span> Toute prestation est précédée d'un devis détaillant la nature et les quantités de Produits et de Services, les prix, les délais prévisionnels et, le cas échéant, l'acompte exigé. Sauf mention contraire, le devis est valable <strong>trente (30) jours</strong> à compter de son émission.</p>
<p><span class='cn-cl'>3.2</span> Le contrat est formé à la réception, par le Prestataire, du devis daté et signé par le Client (ou de son acceptation écrite non équivoque), accompagné de l'acompte éventuellement prévu. Le Prestataire peut refuser ou subordonner une commande, notamment en cas d'incident de paiement antérieur ou de solvabilité insuffisante.</p>
<p><span class='cn-cl'>3.3</span> Toute modification de la commande demandée par le Client après acceptation fait l'objet d'un devis complémentaire ; elle peut entraîner une révision des délais et des prix.</p>
<p><span class='cn-cl'>3.4</span> En cas d'annulation de la commande à l'initiative du Client, le Prestataire est en droit de conserver l'acompte versé et de facturer les Produits spécialement commandés, les Services déjà réalisés et les frais engagés, sans préjudice de tous dommages et intérêts.</p>
<p><span class='cn-cl'>3.5</span> Le Client déclare que sa commande est en rapport direct avec son activité professionnelle. Si, en application de l'article L.221-3 du Code de la consommation, le Client employant cinq salariés au plus contracte hors établissement pour un objet qui n'entre pas dans le champ de son activité principale, il bénéficie du droit de rétractation de quatorze (14) jours prévu par la loi, qu'il doit exercer par une notification écrite non équivoque.</p>
</section>

<section id='art-4' class='cn-legal-sec'>
<h2>Article 4 — Prix</h2>
<p><span class='cn-cl'>4.1</span> Les prix sont exprimés en euros, <strong>hors taxes</strong> ; la TVA au taux en vigueur à la date de facturation est ajoutée. Les frais de déplacement, de livraison, de mise en service ou de transport spécifique sont indiqués au devis.</p>
<p><span class='cn-cl'>4.2</span> Les prix des Produits sont ceux du devis. En cas de variation significative des tarifs du constructeur ou du fournisseur intervenant entre l'émission du devis et la livraison d'une commande dont le délai excède trois mois, le Prestataire peut ajuster le prix, après en avoir informé le Client, qui peut alors annuler la commande sans indemnité si l'ajustement excède 10 %.</p>
<p><span class='cn-cl'>4.3</span> Les prix des abonnements et services récurrents sont ceux du contrat ; ils peuvent être révisés à chaque échéance annuelle, ou à tout moment en cas de répercussion d'une hausse tarifaire imposée par un opérateur ou un éditeur tiers, sous réserve d'un préavis de trente (30) jours.</p>
</section>

<section id='art-5' class='cn-legal-sec'>
<h2>Article 5 — Facturation et conditions de paiement</h2>
<p><span class='cn-cl'>5.1</span> Le devis précise l'éventuel acompte et les échéances. À défaut de stipulation contraire, les factures sont payables par virement <strong>à trente (30) jours</strong> à compter de leur date d'émission. Aucun escompte n'est accordé pour paiement anticipé. Les factures peuvent être émises sous forme électronique, y compris par l'intermédiaire d'une plateforme agréée, conformément à la réglementation en vigueur.</p>
<p><span class='cn-cl'>5.2</span> Tout retard de paiement entraîne, de plein droit et sans mise en demeure préalable, (i) l'exigibilité immédiate de toutes les sommes dues, (ii) des pénalités de retard calculées sur le montant TTC restant dû à un taux égal à <strong>trois fois le taux d'intérêt légal</strong> en vigueur, et (iii) une <strong>indemnité forfaitaire de quarante (40) euros</strong> pour frais de recouvrement, conformément aux articles L.441-10 et D.441-5 du Code de commerce. Lorsque les frais de recouvrement exposés sont supérieurs à ce montant, le Prestataire peut demander une indemnisation complémentaire sur justificatifs.</p>
<p><span class='cn-cl'>5.3</span> En cas de défaut de paiement, le Prestataire peut suspendre l'exécution des commandes et Services en cours, sans préjudice de son droit de résilier le contrat dans les conditions de l'article 17. Le Client ne peut opérer aucune compensation ni retenue unilatérale sur les sommes dues au titre d'une créance qu'il prétendrait détenir sur le Prestataire, sauf accord écrit préalable.</p>
</section>

<section id='art-6' class='cn-legal-sec'>
<h2>Article 6 — Livraison, installation et obligations du Client</h2>
<p><span class='cn-cl'>6.1</span> Les délais de livraison et d'installation sont donnés à titre indicatif ; ils dépendent notamment de la disponibilité des Produits auprès des constructeurs et distributeurs. Leur dépassement n'ouvre droit ni à annulation, ni à pénalité, ni à dommages et intérêts, sauf faute lourde du Prestataire, et sous réserve d'une mise en demeure restée sans effet pendant un délai raisonnable.</p>
<p><span class='cn-cl'>6.2</span> Le <strong>transfert des risques</strong> sur les Produits intervient à la livraison ou à la mise à disposition sur le site du Client, quelle que soit la date du transfert de propriété. Le Client assure les Produits contre le vol, l'incendie et les dégâts des eaux à compter de cette date.</p>
<p><span class='cn-cl'>6.3</span> Le Client s'engage à coopérer de bonne foi : accès aux locaux et aux équipements, alimentation électrique conforme, accès internet le cas échéant, désignation d'un interlocuteur habilité, communication des informations nécessaires et respect des prérequis techniques indiqués par le Prestataire. Les retards, surcoûts ou immobilisations imputables au Client (accès impossible, prérequis non remplis, indisponibilité) sont facturés en sus au tarif du Prestataire.</p>
<p><span class='cn-cl'>6.4</span> Le Client effectue, sous sa seule responsabilité, la sauvegarde de ses données et de ses configurations avant toute intervention. Il garantit qu'il détient les droits et licences nécessaires sur les logiciels et systèmes sur lesquels le Prestataire intervient.</p>
</section>

<section id='art-7' class='cn-legal-sec'>
<h2>Article 7 — Réception et conformité</h2>
<p><span class='cn-cl'>7.1</span> La réception intervient à l'issue de l'installation et de la mise en service, le cas échéant par la signature d'un procès-verbal de livraison et de conformité, qui vaut réception sans réserve des éléments qu'il mentionne.</p>
<p><span class='cn-cl'>7.2</span> Toute non-conformité apparente ou anomalie doit être notifiée par écrit au Prestataire, avec description précise, dans un délai de <strong>huit (8) jours</strong> suivant la réception. À défaut, les Produits et Services sont réputés conformes et acceptés. Aucun retour de Produits n'est accepté sans l'accord préalable écrit du Prestataire.</p>
</section>

<section id='art-8' class='cn-legal-sec'>
<h2>Article 8 — Garanties</h2>
<p><span class='cn-cl'>8.1</span> Les Produits neufs bénéficient de la <strong>garantie légale des vices cachés</strong> (articles 1641 et suivants du Code civil) et de la <strong>garantie commerciale du constructeur</strong>, dans les conditions et pour la durée propres à chaque Produit, que le Prestataire transmet au Client. Le Prestataire fait ses meilleurs efforts pour faciliter la mise en œuvre de la garantie constructeur.</p>
<p><span class='cn-cl'>8.2</span> Sont exclus de la garantie : l'usure normale, les dommages résultant d'un usage non conforme ou d'une négligence, d'une modification ou d'une intervention de tiers non agréés, d'une surtension, d'un défaut d'alimentation ou de refroidissement, de la foudre, d'un dégât des eaux, d'un acte de vandalisme, ainsi que les consommables. La garantie ne couvre ni les logiciels, systèmes d'exploitation et services de tiers, ni la perte de données.</p>
<p><span class='cn-cl'>8.3</span> Concernant les Services, le Prestataire est tenu d'une obligation de moyens. Il reprend, à ses frais, toute prestation d'installation reconnue défectueuse et signalée dans le délai de l'article 7.</p>
</section>

<section id='art-9' class='cn-legal-sec'>
<h2>Article 9 — Maintenance et assistance</h2>
<p><span class='cn-cl'>9.1</span> La maintenance préventive ou corrective, le dépannage et l'assistance sont fournis dans le cadre d'un contrat distinct précisant le périmètre, les horaires, les délais d'intervention et le prix. En l'absence de contrat, les interventions sont réalisées sur demande, selon la disponibilité du Prestataire, et facturées au tarif communiqué.</p>
<p><span class='cn-cl'>9.2</span> Les délais d'intervention indiqués au contrat courent à compter de la réception de la demande complète du Client par les canaux convenus. Ne sont pas couverts, sauf stipulation contraire : les interventions rendues nécessaires par un usage non conforme, une modification non autorisée ou un événement extérieur, ainsi que les évolutions ou extensions du système.</p>
</section>

<section id='art-10' class='cn-legal-sec'>
<h2>Article 10 — Abonnements et services récurrents</h2>
<p><span class='cn-cl'>10.1</span> La durée, le prix, la périodicité de facturation, les conditions de renouvellement et de résiliation de chaque abonnement (internet, téléphonie, vidéosurveillance, stockage, supervision et services associés) sont précisés au devis et au contrat. À défaut de stipulation contraire, l'abonnement est conclu pour la durée initiale indiquée puis se renouvelle tacitement par périodes successives d'égale durée, sauf résiliation notifiée par lettre recommandée ou courrier électronique avec accusé de réception moyennant un préavis de trois (3) mois avant l'échéance.</p>
<p><span class='cn-cl'>10.2</span> Les Services fournis par des opérateurs, éditeurs ou hébergeurs tiers sont en outre soumis à leurs propres conditions, que le Client reconnaît avoir consultées et acceptées. Le Prestataire n'est pas responsable des interruptions, restrictions ou évolutions décidées par ces tiers, ni des défaillances des réseaux de télécommunications.</p>
<p><span class='cn-cl'>10.3</span> Le Prestataire peut suspendre l'accès aux Services récurrents en cas d'utilisation illicite ou contraire au contrat, ou de défaut de paiement persistant après mise en demeure.</p>
</section>

<section id='art-11' class='cn-legal-sec'>
<h2>Article 11 — Réserve de propriété</h2>
<p><span class='cn-cl'>11.1</span> <strong>Les Produits demeurent la propriété du Prestataire jusqu'au paiement effectif et intégral du prix en principal et accessoires</strong> (article 2367 du Code civil et articles L.624-16 et suivants du Code de commerce). Le défaut de paiement d'une seule échéance autorise le Prestataire à exiger la restitution des Produits impayés, aux frais et risques du Client, sans préjudice de son droit à l'exécution forcée.</p>
<p><span class='cn-cl'>11.2</span> Tant que le prix n'est pas intégralement payé, le Client s'interdit de céder, de donner en gage ou de transférer en garantie les Produits, doit les conserver en bon état, en permettre l'identification, et informer sans délai le Prestataire de toute saisie ou procédure les concernant. Le Client supporte les risques des Produits dès leur livraison (article 6).</p>
</section>

<section id='art-12' class='cn-legal-sec'>
<h2>Article 12 — Financement en location (crédit-bail, LOA, LLD)</h2>
<p><span class='cn-cl'>12.1</span> Le Client peut financer tout ou partie de l'équipement par un contrat de location avec option d'achat, de crédit-bail ou de location longue durée, conclu avec l'un des <strong>établissements ou sociétés de financement partenaires</strong> du Prestataire ou avec tout autre organisme de son choix.</p>
<p><span class='cn-cl'>12.2</span> Le Prestataire intervient exclusivement en qualité de <strong>fournisseur du matériel</strong> : il n'est ni prêteur, ni bailleur, et n'est pas partie au contrat de financement, dont les conditions (loyers, durée, assurance, valeur résiduelle, option d'achat, résiliation) relèvent uniquement de la relation entre le Client et l'organisme de financement. Le Prestataire n'assure aucun conseil en financement.</p>
<p><span class='cn-cl'>12.3</span> À la demande du Client, le Prestataire établit un devis détaillé poste par poste et fournit à l'organisme de financement les documents usuels (devis, bon de commande, facture, procès-verbal de livraison et de conformité). Les informations juridiques d'identification du Prestataire sont publiques (voir les <a href='/mentions-legales/'>mentions légales</a>) et un extrait Kbis peut être communiqué sur demande.</p>
<p><span class='cn-cl'>12.4</span> Lorsque l'organisme de financement acquiert les Produits, la propriété lui est transférée dans les conditions de la vente conclue avec lui ; le paiement du prix est alors effectué par l'organisme, sur présentation du procès-verbal de livraison signé par le Client. <strong>L'obtention du financement peut être stipulée comme condition suspensive de la commande</strong> ; à défaut de mention au devis, la commande est ferme.</p>
<p><span class='cn-cl'>12.5</span> Le Client reste tenu de ses obligations envers l'organisme de financement, notamment le paiement des loyers, qui est indépendant de l'exécution du contrat conclu avec le Prestataire, sauf stipulation contraire du contrat de financement.</p>
</section>

<section id='art-13' class='cn-legal-sec'>
<h2>Article 13 — Vidéosurveillance, données et conformité</h2>
<p><span class='cn-cl'>13.1</span> Le Client est <strong>seul responsable</strong> de la conformité de l'exploitation de son système de vidéosurveillance et de ses traitements de données, notamment au regard du RGPD, du Code de la sécurité intérieure et du Code du travail : finalité et proportionnalité du dispositif, information des personnes filmées et des représentants du personnel, durée de conservation des images, sécurisation des accès, formalités ou autorisations préalables lorsque des lieux ouverts au public sont concernés.</p>
<p><span class='cn-cl'>13.2</span> Le Prestataire conseille le Client lors de l'étude du site mais ne saurait se substituer à lui pour la détermination des finalités ni pour l'accomplissement de ces obligations.</p>
<p><span class='cn-cl'>13.3</span> Lorsque, à l'occasion d'une intervention, le Prestataire a accès à des données personnelles du Client, il agit en qualité de sous-traitant au sens de l'article 28 du RGPD : il traite ces données uniquement sur instruction documentée du Client, s'engage à en garantir la confidentialité et la sécurité et à ne pas les utiliser à d'autres fins. Un accord de sous-traitance peut être conclu sur demande.</p>
</section>

<section id='art-14' class='cn-legal-sec'>
<h2>Article 14 — Propriété intellectuelle et licences</h2>
<p><span class='cn-cl'>14.1</span> Les documents, études, schémas, configurations et outils élaborés par le Prestataire demeurent sa propriété ; le Client dispose d'un droit d'usage non exclusif, pour ses besoins internes, à compter du paiement intégral du prix. Les logiciels et micrologiciels livrés sont soumis aux licences de leurs éditeurs, que le Client s'engage à respecter.</p>
</section>

<section id='art-15' class='cn-legal-sec'>
<h2>Article 15 — Responsabilité</h2>
<p><span class='cn-cl'>15.1</span> Le Prestataire est responsable, dans les conditions du droit commun, des dommages directs, certains et prévisibles causés au Client par un manquement à ses obligations contractuelles. <strong>Sa responsabilité globale, toutes causes confondues, est limitée au montant hors taxes effectivement payé par le Client au titre de la commande ou du contrat à l'origine du dommage</strong> (pour les contrats récurrents, aux sommes payées au cours des douze (12) mois précédant le fait générateur).</p>
<p><span class='cn-cl'>15.2</span> Le Prestataire n'est pas responsable des dommages indirects ou immatériels : perte d'exploitation, de chiffre d'affaires, de clientèle, de données, atteinte à l'image, ni des dommages résultant d'un manquement du Client à ses obligations, d'un fait de tiers ou d'un cas de force majeure.</p>
<p><span class='cn-cl'>15.3</span> Ces limitations ne s'appliquent ni en cas de faute lourde ou dolosive, ni en cas de dommage corporel, ni lorsque la loi l'interdit. Les parties reconnaissent que ces stipulations reflètent l'équilibre économique du contrat.</p>
</section>

<section id='art-16' class='cn-legal-sec'>
<h2>Article 16 — Force majeure</h2>
<p><span class='cn-cl'>16.1</span> Aucune partie n'est responsable d'un manquement dû à un cas de force majeure au sens de l'article 1218 du Code civil, notamment : panne ou coupure des réseaux d'énergie ou de télécommunications, cyberattaque, grève, catastrophe naturelle, incendie, décision d'une autorité, rupture d'approvisionnement ou pénurie de composants indépendante de la volonté du Prestataire. L'exécution des obligations est suspendue pendant la durée de l'événement ; si celui-ci excède soixante (60) jours, chaque partie peut résilier le contrat par écrit, sans indemnité.</p>
</section>

<section id='art-17' class='cn-legal-sec'>
<h2>Article 17 — Résiliation pour manquement</h2>
<p><span class='cn-cl'>17.1</span> En cas de manquement grave d'une partie à l'une de ses obligations, l'autre partie peut résoudre le contrat de plein droit, quinze (15) jours après l'envoi d'une mise en demeure par lettre recommandée avec accusé de réception restée sans effet (article 1225 du Code civil), sans préjudice de tous dommages et intérêts. Le défaut de paiement à l'échéance constitue un manquement grave.</p>
<p><span class='cn-cl'>17.2</span> La résiliation n'affecte pas les sommes dues au titre des Produits livrés et des Services exécutés ; les clauses relatives à la propriété, aux paiements, à la responsabilité et à la confidentialité survivent à la fin du contrat.</p>
</section>

<section id='art-18' class='cn-legal-sec'>
<h2>Article 18 — Sous-traitance, cession et dispositions générales</h2>
<p><span class='cn-cl'>18.1</span> Le Prestataire peut sous-traiter tout ou partie des Services à des tiers qualifiés, en restant responsable de leur exécution à l'égard du Client. Le Client ne peut céder ou transférer le contrat sans l'accord écrit du Prestataire.</p>
<p><span class='cn-cl'>18.2</span> Le fait pour une partie de ne pas se prévaloir d'un manquement ou d'une clause ne vaut pas renonciation. Si une stipulation des CGV est déclarée nulle ou inapplicable, les autres demeurent en vigueur. Les parties s'engagent à garder confidentielles les informations non publiques échangées à l'occasion du contrat.</p>
</section>

<section id='art-19' class='cn-legal-sec'>
<h2>Article 19 — Réclamations, droit applicable et juridiction</h2>
<p><span class='cn-cl'>19.1</span> Toute réclamation peut être adressée par écrit à <a href='mailto:contact@connectis-solutions.fr'>contact@connectis-solutions.fr</a> ; les parties s'efforcent de résoudre à l'amiable tout différend avant toute action judiciaire.</p>
<p><span class='cn-cl'>19.2</span> Les CGV et les contrats conclus sont soumis au <strong>droit français</strong>. À défaut d'accord amiable, <strong>le tribunal de commerce de Nancy est seul compétent</strong>, y compris en cas de référé, d'appel en garantie, de pluralité de défendeurs ou de procédure d'urgence, nonobstant toute clause contraire des conditions du Client.</p>
</section>
<p class='cn-updated'>Version en vigueur au 25 septembre 2026.</p>
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

    $capital_li = CONNECTIS_CAPITAL_SOCIAL !== ''
        ? "<li><span>Capital social</span><strong>" . CONNECTIS_CAPITAL_SOCIAL . "</strong></li>\n"
        : '';
    $mentions = str_replace('{{CAPITAL_LI}}', $capital_li, $mentions);

    return [
        'mentions-legales' => ['title' => 'Mentions légales', 'content' => $mentions],
        'cgv'              => ['title' => 'Conditions générales de vente', 'content' => $cgv],
        'confidentialite'  => ['title' => 'Politique de confidentialité', 'content' => $privacy],
    ];
}
