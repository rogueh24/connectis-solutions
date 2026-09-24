# -*- coding: utf-8 -*-
import os
from reportlab.lib.pagesizes import A4
from reportlab.lib.units import mm
from reportlab.lib import colors
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.enums import TA_JUSTIFY, TA_CENTER, TA_LEFT
from reportlab.platypus import (
    BaseDocTemplate, PageTemplate, Frame, NextPageTemplate, PageBreak,
    Paragraph, Spacer, Table, TableStyle, Image, ListFlowable, ListItem,
    KeepTogether, HRFlowable
)
from reportlab.platypus.tableofcontents import TableOfContents
from reportlab.pdfgen import canvas as canvas_module

HERE = os.path.dirname(os.path.abspath(__file__))
LOGO = os.path.join(os.path.dirname(HERE), "WhatsApp Image 2026-09-24 at 16.00.38.jpeg")
OUT = os.path.join(HERE, "Connectis_Solutions_Cahier_des_Charges_et_Projet_Commercial.pdf")

NAVY = colors.HexColor("#070d1f")
NAVY2 = colors.HexColor("#0c1730")
BLUE = colors.HexColor("#1b63e6")
BLUE_DARK = colors.HexColor("#123a8c")
CYAN = colors.HexColor("#33d0e8")
LIGHT_BLUE_BG = colors.HexColor("#eef4fd")
GREY = colors.HexColor("#4a5568")
LGREY = colors.HexColor("#cfd8e8")
WHITE = colors.white

PAGE_W, PAGE_H = A4

styles = getSampleStyleSheet()

styles.add(ParagraphStyle(name="CoverTitle", fontName="Helvetica-Bold", fontSize=26,
                           leading=32, textColor=WHITE, alignment=TA_CENTER, spaceAfter=6))
styles.add(ParagraphStyle(name="CoverSubtitle", fontName="Helvetica", fontSize=14,
                           leading=20, textColor=CYAN, alignment=TA_CENTER, spaceAfter=4))
styles.add(ParagraphStyle(name="CoverMeta", fontName="Helvetica", fontSize=10.5,
                           leading=15, textColor=LGREY, alignment=TA_CENTER))
styles.add(ParagraphStyle(name="H1", fontName="Helvetica-Bold", fontSize=16.5,
                           leading=20, textColor=WHITE, spaceBefore=0, spaceAfter=0))
styles.add(ParagraphStyle(name="H2", fontName="Helvetica-Bold", fontSize=12.5,
                           leading=16, textColor=BLUE_DARK, spaceBefore=14, spaceAfter=6))
styles.add(ParagraphStyle(name="H3", fontName="Helvetica-Bold", fontSize=10.8,
                           leading=14, textColor=GREY, spaceBefore=8, spaceAfter=4))
styles.add(ParagraphStyle(name="Body", fontName="Helvetica", fontSize=9.6,
                           leading=14.5, textColor=colors.HexColor("#1a202c"),
                           alignment=TA_JUSTIFY, spaceAfter=6))
styles.add(ParagraphStyle(name="BodyBold", parent=styles["Body"], fontName="Helvetica-Bold"))
styles.add(ParagraphStyle(name="BulletText", parent=styles["Body"], leftIndent=0, spaceAfter=3))
styles.add(ParagraphStyle(name="PartTitle", fontName="Helvetica-Bold", fontSize=20,
                           leading=24, textColor=WHITE, alignment=TA_CENTER))
styles.add(ParagraphStyle(name="PartSub", fontName="Helvetica", fontSize=12,
                           leading=16, textColor=CYAN, alignment=TA_CENTER, spaceBefore=8))
styles.add(ParagraphStyle(name="TOCHeading", parent=styles["H1"], textColor=BLUE_DARK))
styles.add(ParagraphStyle(name="TOC1", fontName="Helvetica-Bold", fontSize=10.5, leading=16,
                           textColor=colors.HexColor("#1a202c")))
styles.add(ParagraphStyle(name="TOC2", fontName="Helvetica", fontSize=9.5, leading=14,
                           leftIndent=12, textColor=GREY))
styles.add(ParagraphStyle(name="Small", fontName="Helvetica", fontSize=8.2, leading=11,
                           textColor=GREY))
styles.add(ParagraphStyle(name="CellHead", fontName="Helvetica-Bold", fontSize=9,
                           leading=12, textColor=WHITE))
styles.add(ParagraphStyle(name="Cell", fontName="Helvetica", fontSize=8.8, leading=12,
                           textColor=colors.HexColor("#1a202c")))

def P(text, style="Body"):
    return Paragraph(text, styles[style])

def bullets(items, style="BulletText"):
    return ListFlowable(
        [ListItem(P(i, style), leftIndent=6, spaceAfter=3) for i in items],
        bulletType="bullet", start="circle", bulletFontSize=5, bulletColor=BLUE,
        leftIndent=14,
    )

def section_bar(num, title):
    t = Table([[P(f"{num}", "H1"), P(title, "H1")]], colWidths=[16 * mm, None])
    t.setStyle(TableStyle([
        ("BACKGROUND", (0, 0), (-1, -1), BLUE_DARK),
        ("VALIGN", (0, 0), (-1, -1), "MIDDLE"),
        ("LEFTPADDING", (0, 0), (0, 0), 10),
        ("LEFTPADDING", (1, 0), (1, 0), 4),
        ("RIGHTPADDING", (0, 0), (-1, -1), 8),
        ("TOPPADDING", (0, 0), (-1, -1), 7),
        ("BOTTOMPADDING", (0, 0), (-1, -1), 7),
    ]))
    t._toc_h1 = f"{num}. {title}"
    return t

def h2(text):
    return P(text, "H2")

def table_std(data, col_widths, head_rows=1):
    t = Table(data, colWidths=col_widths, repeatRows=head_rows)
    style = [
        ("BACKGROUND", (0, 0), (-1, head_rows - 1), BLUE),
        ("TEXTCOLOR", (0, 0), (-1, head_rows - 1), WHITE),
        ("FONTNAME", (0, 0), (-1, head_rows - 1), "Helvetica-Bold"),
        ("FONTSIZE", (0, 0), (-1, -1), 8.8),
        ("GRID", (0, 0), (-1, -1), 0.5, LGREY),
        ("VALIGN", (0, 0), (-1, -1), "TOP"),
        ("TOPPADDING", (0, 0), (-1, -1), 5),
        ("BOTTOMPADDING", (0, 0), (-1, -1), 5),
        ("LEFTPADDING", (0, 0), (-1, -1), 6),
        ("RIGHTPADDING", (0, 0), (-1, -1), 6),
        ("ROWBACKGROUNDS", (0, head_rows), (-1, -1), [WHITE, LIGHT_BLUE_BG]),
    ]
    t.setStyle(TableStyle(style))
    return t

story = []

# ---------------------------------------------------------------- COVER ----
story.append(Spacer(1, 34 * mm))
story.append(Image(LOGO, width=52 * mm, height=52 * mm, hAlign="CENTER"))
story.append(Spacer(1, 14 * mm))
story.append(P("CAHIER DES CHARGES<br/>&amp; DOSSIER DE PROJET COMMERCIAL", "CoverTitle"))
story.append(Spacer(1, 4 * mm))
story.append(P("Conception du site vitrine — Connectis Solutions", "CoverSubtitle"))
story.append(Spacer(1, 18 * mm))
story.append(HRFlowable(width="30%", thickness=0.8, color=CYAN, hAlign="CENTER"))
story.append(Spacer(1, 10 * mm))
story.append(P("Vidéosurveillance&nbsp;&nbsp;·&nbsp;&nbsp;Téléphonie &amp; VoIP&nbsp;&nbsp;·&nbsp;&nbsp;Internet &amp; Fibre optique<br/>"
               "Matériel informatique &amp; télécom&nbsp;&nbsp;·&nbsp;&nbsp;Abonnements&nbsp;&nbsp;·&nbsp;&nbsp;Services &amp; maintenance",
               "CoverMeta"))
story.append(Spacer(1, 22 * mm))
story.append(P("Connectis Solutions — Solutions IT &amp; Télécoms pour professionnels<br/>"
               "connectis-solutions.fr — contact@connectis-solutions.fr<br/>"
               "Stack retenue : Planet Hoster · WordPress · Blocksy · MySQL<br/>"
               "Document rédigé le 24 septembre 2026 — Version 1.0 — Usage interne / partenaires",
               "CoverMeta"))
story.append(NextPageTemplate("normal"))
story.append(PageBreak())

# ------------------------------------------------------------------ TOC ----
story.append(P("Sommaire", "TOCHeading"))
story.append(Spacer(1, 4 * mm))
toc = TableOfContents()
toc.levelStyles = [styles["TOC1"], styles["TOC2"]]
story.append(toc)

# ------------------------------------------------------------- PART 1 ------
def part_title_page(title, subtitle):
    story.append(NextPageTemplate("part"))
    story.append(PageBreak())
    story.append(Spacer(1, 90 * mm))
    part_p = P(title, "PartTitle")
    part_p._toc_part = subtitle
    story.append(part_p)
    story.append(P(subtitle, "PartSub"))
    story.append(NextPageTemplate("normal"))
    story.append(PageBreak())

part_title_page("PARTIE 1", "CAHIER DES CHARGES FONCTIONNEL ET TECHNIQUE")

def h1_entry(num, title):
    story.append(section_bar(num, title))
    story.append(Spacer(1, 4 * mm))

# 1. Présentation du projet
h1_entry("1", "Présentation du projet")
story.append(h2("1.1 Contexte"))
story.append(P(
    "Connectis Solutions est une entreprise de solutions informatiques et télécoms dédiée aux professionnels. "
    "Elle accompagne les entreprises dans la sécurisation, "
    "la connectivité et l'équipement de leurs locaux : vidéosurveillance, téléphonie, internet et fibre optique, "
    "vente de matériel et abonnements associés. L'entreprise s'appuie sur une équipe commerciale de terrain "
    "(flotte commerciale itinérante) qui intervient directement chez le client pour le conseil, le devis et "
    "l'installation.", "Body"))
story.append(P(
    "À ce jour, Connectis Solutions ne dispose pas de site internet. L'ensemble de la prospection et de la "
    "relation client repose sur le bouche-à-oreille et la présence commerciale terrain. Ce document a pour "
    "objectif de cadrer la création d'un site vitrine professionnel, servant à la fois de support de visibilité "
    "et d'outil d'aide à la vente pour les commerciaux.", "Body"))
story.append(h2("1.2 L'entreprise Connectis Solutions"))
story.append(bullets([
    "<b>Activités :</b> vidéosurveillance, téléphonie fixe/IP/mobile, internet &amp; fibre optique, vente de "
    "matériel informatique et télécom, abonnements &amp; forfaits, services d'installation et de maintenance.",
    "<b>Organisation :</b> agence/showroom + flotte commerciale terrain se déplaçant directement chez les clients "
    "professionnels.",
    "<b>Positionnement :</b> proximité, réactivité et solutions sur mesure — une alternative locale et humaine "
    "aux grands opérateurs nationaux.",
]))
story.append(h2("1.3 Objectifs du site vitrine"))
story.append(bullets([
    "Donner à Connectis Solutions une existence et une crédibilité en ligne.",
    "Présenter clairement l'ensemble de l'offre (vidéosurveillance, téléphonie, fibre, matériel, abonnements, services).",
    "Générer des demandes de devis et des prises de rendez-vous qualifiées.",
    "Servir d'outil d'appui aux commerciaux terrain (site consultable sur mobile/tablette en rendez-vous client).",
    "Améliorer le référencement local pour capter la recherche de proximité sur le secteur d'intervention.",
    "Valoriser l'image de marque autour du positionnement « proximité, réactivité, sur mesure ».",
]))
story.append(PageBreak())

# 2. Cibles
h1_entry("2", "Cibles et utilisateurs du site")
story.append(P("Le site s'adresse exclusivement à une clientèle professionnelle (B2B), tous secteurs d'activité "
               "et toutes tailles de structure confondus. Il doit rester suffisamment général pour parler à "
               "n'importe quelle entreprise ayant besoin de sécuriser ou d'équiper ses locaux.", "Body"))
story.append(table_std(
    [[P("Cible", "CellHead"), P("Besoins types", "CellHead"), P("Attentes vis-à-vis du site", "CellHead")],
     [P("<b>Entreprises &amp; professionnels</b>", "Cell"),
      P("Téléphonie IP/standard, vidéosurveillance de locaux, connexion fibre pro, contrats de maintenance.", "Cell"),
      P("Sérieux, références, réactivité du SAV, contact commercial dédié.", "Cell")],
     ],
    col_widths=[46 * mm, 64 * mm, 58 * mm]))
story.append(Spacer(1, 4 * mm))
story.append(P("Un second profil, transverse, doit également être pris en compte : les <b>commerciaux terrain de "
               "Connectis Solutions</b>, qui utiliseront le site sur mobile pendant leurs rendez-vous pour appuyer "
               "leur argumentaire (fiches produits, simulateurs).", "Body"))
story.append(PageBreak())

# 3. Arborescence
h1_entry("3", "Périmètre du site — Arborescence")
story.append(P("Arborescence proposée pour la version 1 du site :", "Body"))
story.append(bullets([
    "<b>Accueil</b>",
    "<b>Nos solutions</b> (page pilier avec 6 sous-pages) — Vidéosurveillance · Téléphonie &amp; standard · "
    "Internet &amp; Fibre optique · Matériel informatique &amp; télécom · Abonnements &amp; forfaits · "
    "Services (installation, maintenance, dépannage)",
    "<b>À propos</b> (histoire, valeurs, équipe, engagement proximité/réactivité/sur-mesure)",
    "<b>Devis &amp; Contact</b> (formulaire de devis multi-services et formulaire de contact, prise de rendez-vous, coordonnées)",
    "<b>Espace recrutement</b> (rejoindre l'équipe commerciale)",
    "<b>Mentions légales, CGV, politique de confidentialité, gestion des cookies</b> (conformité RGPD)",
]))
story.append(PageBreak())

# 4. Description détaillée
h1_entry("4", "Description détaillée des rubriques")

story.append(h2("4.1 Accueil"))
story.append(bullets([
    "Bandeau d'introduction (accroche + proximité/réactivité/sur-mesure) avec appel à l'action « Demander un devis ».",
    "Présentation synthétique des 6 familles de solutions, avec renvoi vers les pages dédiées.",
    "Bloc « Pourquoi Connectis Solutions » (proximité, réactivité, interlocuteur unique, sur-mesure).",
    "Bloc de réassurance (garanties, SAV, certifications le cas échéant).",
    "Appel à l'action final vers la page Devis &amp; Contact.",
]))

story.append(h2("4.2 Nos solutions"))
story.append(P("Page pilier listant les 6 familles d'offres, chacune renvoyant vers une page dédiée détaillant produits, bénéfices, exemples de forfaits et un bouton de devis contextualisé :", "Body"))
story.append(table_std(
    [[P("Sous-rubrique", "CellHead"), P("Contenu attendu", "CellHead")],
     [P("Vidéosurveillance", "Cell"), P("Caméras IP professionnelles, enregistreurs, vidéosurveillance à distance, alarme, contrôle d'accès.", "Cell")],
     [P("Téléphonie &amp; standard", "Cell"), P("Téléphonie fixe, VoIP/IP, standard téléphonique pour entreprises, téléphonie mobile.", "Cell")],
     [P("Internet &amp; Fibre optique", "Cell"), P("Raccordement fibre professionnel, box internet entreprise, Wi-Fi professionnel.", "Cell")],
     [P("Matériel informatique &amp; télécom", "Cell"), P("Vente et installation de matériel (PC, réseaux, téléphonie, vidéosurveillance), configuration incluse.", "Cell")],
     [P("Abonnements &amp; forfaits", "Cell"), P("Forfaits internet/téléphonie/surveillance, formules mensuelles avec ou sans engagement.", "Cell")],
     [P("Services", "Cell"), P("Installation, maintenance préventive/corrective, dépannage, contrats SAV.", "Cell")],
     ],
    col_widths=[46 * mm, 122 * mm]))

story.append(h2("4.3 À propos"))
story.append(P("Présentation de l'entreprise, de ses valeurs (proximité, réactivité, solutions sur mesure) et de son "
               "équipe. Cette page renforce la confiance face aux opérateurs nationaux.", "Body"))

story.append(h2("4.4 Devis &amp; Contact"))
story.append(bullets([
    "Formulaire de devis : sélection du service concerné, informations de contact, description du besoin.",
    "Formulaire de contact simple, distinct du formulaire de devis, pour les demandes générales et le SAV.",
    "Prise de rendez-vous en ligne avec un commercial (créneaux disponibles).",
    "Coordonnées complètes (téléphone, e-mail contact@connectis-solutions.fr), boutons d'appel direct et WhatsApp.",
]))

story.append(h2("4.5 Espace recrutement"))
story.append(P("Page dédiée au recrutement de l'équipe commerciale et technique : présentation de l'entreprise "
               "employeur, offres à pourvoir, formulaire de candidature (dépôt de CV).", "Body"))

story.append(h2("4.6 Mentions légales &amp; RGPD"))
story.append(P("Pages obligatoires : mentions légales, conditions générales de vente/service, politique de "
               "confidentialité, gestion des cookies (bandeau de consentement conforme RGPD/CNIL).", "Body"))
story.append(PageBreak())

# 5. Fonctionnalités transverses
h1_entry("5", "Fonctionnalités transverses")
story.append(bullets([
    "<b>Formulaire de devis multi-étapes</b> avec sélection du service concerné et notification par e-mail à l'équipe commerciale.",
    "<b>Click-to-call</b> et bouton <b>WhatsApp Business</b> flottants sur mobile.",
    "<b>Prise de rendez-vous en ligne</b> (module de réservation type Calendly ou plugin RDV WordPress).",
    "<b>Espace « Nos partenaires / marques »</b> pour valoriser les opérateurs et fabricants de matériel distribués.",
    "<b>Newsletter</b> (capture d'e-mails, envoi d'offres et d'informations commerciales).",
    "<b>Intégration réseaux sociaux</b> (Facebook, Instagram, LinkedIn, Google Avis).",
    "<b>Site 100 % responsive</b>, pensé mobile-first pour un usage par les commerciaux en clientèle.",
    "<b>Référencement local (SEO)</b> optimisé sur le secteur d'intervention, fiche Google Business Profile liée.",
    "<b>Espace client</b> (bouton vers un portail de gestion des abonnements) — fonctionnalité évolutive, hors périmètre v1.",
    "Site en français uniquement pour la version 1 ; structure prévue pour une extension multilingue ultérieure si besoin.",
]))
story.append(PageBreak())

# 6. Exigences techniques
h1_entry("6", "Exigences techniques")
story.append(h2("6.1 Stack technique retenue"))
story.append(table_std(
    [[P("Composant", "CellHead"), P("Choix", "CellHead"), P("Remarques", "CellHead")],
     [P("Hébergement", "Cell"), P("Planet Hoster", "Cell"), P("Hébergement mutualisé/pro avec SSL Let's Encrypt inclus, serveurs LiteSpeed, sauvegardes automatiques.", "Cell")],
     [P("CMS", "Cell"), P("WordPress (dernière version stable)", "Cell"), P("Mises à jour de sécurité activées, back-office en français.", "Cell")],
     [P("Thème", "Cell"), P("Blocksy (+ Blocksy Companion)", "Cell"), P("Thème léger, personnalisable, compatible éditeur de blocs natif.", "Cell")],
     [P("Base de données", "Cell"), P("MySQL", "Cell"), P("Fournie par Planet Hoster, sauvegardée régulièrement.", "Cell")],
     [P("Constructeur de pages", "Cell"), P("Éditeur de blocs natif WordPress (Gutenberg) + extensions Blocksy", "Cell"), P("Évite la dépendance à un constructeur tiers lourd.", "Cell")],
     ],
    col_widths=[34 * mm, 62 * mm, 72 * mm]))
story.append(Spacer(1, 4 * mm))
story.append(h2("6.2 Extensions (plugins) recommandées"))
story.append(bullets([
    "<b>Formulaires :</b> WPForms ou Contact Form 7 (formulaire de devis multi-étapes).",
    "<b>SEO :</b> Rank Math ou Yoast SEO (référencement local sur le secteur d'intervention).",
    "<b>Performance :</b> LiteSpeed Cache (compatible Planet Hoster).",
    "<b>Sécurité :</b> Wordfence (pare-feu applicatif, anti-intrusion).",
    "<b>Sauvegardes :</b> UpdraftPlus (en complément des sauvegardes de l'hébergeur).",
    "<b>RGPD / cookies :</b> Complianz ou CookieYes.",
    "<b>Anti-spam :</b> Akismet.",
    "<b>Prise de rendez-vous :</b> plugin de réservation WordPress ou intégration Calendly.",
]))
story.append(h2("6.3 Exigences non fonctionnelles"))
story.append(bullets([
    "Nom de domaine : connectis-solutions.fr.",
    "Adresse e-mail de contact du site : contact@connectis-solutions.fr.",
    "Certificat SSL actif sur l'ensemble du site (HTTPS).",
    "Temps de chargement cible inférieur à 3 secondes sur mobile (test Google PageSpeed).",
    "Compatibilité avec les navigateurs récents (Chrome, Edge, Firefox, Safari) et les OS mobiles (iOS, Android).",
    "Conformité RGPD (bandeau cookies, politique de confidentialité, formulaires avec consentement explicite).",
    "Accessibilité : contrastes suffisants, textes alternatifs sur les images, navigation au clavier.",
]))
story.append(PageBreak())

# 7. Charte graphique
h1_entry("7", "Charte graphique &amp; ergonomie")
story.append(P("Le site doit respecter l'identité visuelle du logo Connectis Solutions : dégradé de bleus "
               "(du bleu profond au cyan), fond sombre pour les temps forts (bandeau d'accueil, pied de page) "
               "et fond clair pour les contenus, typographie moderne et lisible. L'ambiance générale doit "
               "véhiculer : technologie, fiabilité, proximité et réactivité.", "Body"))
story.append(bullets([
    "Palette : bleu principal, dégradé bleu-cyan pour les accents, fond sombre (bandeaux) et fond clair (contenu).",
    "Typographie : une police moderne sans-serif, lisible sur mobile.",
    "Maquettes desktop et mobile à valider avant le développement (page d'accueil, une page « solution », page devis).",
    "Boutons d'appel à l'action bien visibles (« Demander un devis », « Appeler un conseiller »).",
]))
story.append(PageBreak())

# 8. Contenus à fournir
h1_entry("8", "Contenus à fournir par Connectis Solutions")
story.append(bullets([
    "Textes de présentation de l'entreprise, de l'équipe et des valeurs (ou brief pour rédaction déléguée).",
    "Liste et description des offres/forfaits par famille de produits (vidéosurveillance, téléphonie, fibre, matériel, abonnements).",
    "Photos de l'équipe, des interventions, du matériel installé (ou recours à une banque d'images professionnelle en complément).",
    "Logos des marques et opérateurs partenaires (fabricants de matériel, opérateurs distribués).",
    "Coordonnées complètes (adresse, téléphone, e-mail contact@connectis-solutions.fr, horaires) et informations légales (SIRET, RCS, etc.).",
]))
story.append(PageBreak())

# 9. Hébergement / maintenance
h1_entry("9", "Hébergement, sécurité &amp; maintenance")
story.append(bullets([
    "Mise en place de l'hébergement Planet Hoster, du nom de domaine et du certificat SSL.",
    "Sauvegardes automatiques régulières (hébergeur + plugin dédié).",
    "Mises à jour régulières de WordPress, du thème Blocksy et des extensions.",
    "Surveillance de la sécurité (pare-feu applicatif, détection d'intrusion, anti-spam).",
    "Contrat de maintenance proposé après mise en ligne : mises à jour, sauvegardes, monitoring de disponibilité, "
    "petites évolutions de contenu.",
]))
story.append(PageBreak())

# 10. Planning
h1_entry("10", "Planning prévisionnel")
story.append(table_std(
    [[P("Phase", "CellHead"), P("Contenu", "CellHead"), P("Durée indicative", "CellHead")],
     [P("1. Cadrage &amp; maquettes", "Cell"), P("Validation du cahier des charges, arborescence, maquettes desktop/mobile.", "Cell"), P("1 à 2 semaines", "Cell")],
     [P("2. Intégration WordPress / Blocksy", "Cell"), P("Installation de l'environnement, intégration des pages, fonctionnalités transverses.", "Cell"), P("2 à 3 semaines", "Cell")],
     [P("3. Contenus &amp; référencement", "Cell"), P("Intégration des textes/visuels fournis, optimisation SEO local.", "Cell"), P("1 à 2 semaines", "Cell")],
     [P("4. Tests &amp; recette", "Cell"), P("Vérification multi-navigateurs/mobile, formulaires, performance, corrections.", "Cell"), P("1 semaine", "Cell")],
     [P("5. Mise en ligne &amp; formation", "Cell"), P("Publication du site, formation de l'équipe Connectis Solutions à l'administration WordPress.", "Cell"), P("1 semaine", "Cell")],
     ],
    col_widths=[46 * mm, 92 * mm, 30 * mm]))
story.append(Spacer(1, 4 * mm))
story.append(P("Durée totale indicative : environ 6 à 9 semaines à compter de la validation du cahier des charges, "
               "selon la disponibilité des contenus fournis par Connectis Solutions.", "Body"))
story.append(PageBreak())

# 11. Budget indicatif
h1_entry("11", "Budget indicatif")
story.append(P("Fourchettes indicatives données à titre de cadrage, à affiner selon le prestataire retenu et le "
               "niveau de personnalisation souhaité :", "Body"))
story.append(table_std(
    [[P("Poste", "CellHead"), P("Fourchette indicative", "CellHead")],
     [P("Conception &amp; développement du site (WordPress / Blocksy)", "Cell"), P("2 500 € – 5 500 €", "Cell")],
     [P("Rédaction / adaptation des contenus &amp; référencement local", "Cell"), P("500 € – 1 200 €", "Cell")],
     [P("Hébergement Planet Hoster + nom de domaine", "Cell"), P("80 € – 200 € / an", "Cell")],
     [P("Maintenance &amp; support annuel (mises à jour, sauvegardes, sécurité)", "Cell"), P("300 € – 900 € / an", "Cell")],
     ],
    col_widths=[104 * mm, 64 * mm]))
story.append(Spacer(1, 4 * mm))
story.append(P("Ces montants sont donnés à titre indicatif et doivent être confirmés par un devis détaillé du "
               "prestataire retenu pour la réalisation du site.", "Small"))
story.append(PageBreak())

# 12. Critères de recette
h1_entry("12", "Critères de recette / validation")
story.append(bullets([
    "Toutes les pages de l'arborescence validée sont en ligne et accessibles.",
    "Le formulaire de devis fonctionne et transmet correctement les demandes à l'équipe commerciale.",
    "Le site s'affiche correctement sur mobile, tablette et ordinateur (responsive).",
    "Les boutons d'appel et de contact WhatsApp fonctionnent.",
    "Le site est référencé sur les principaux moteurs de recherche et lié à la fiche Google Business Profile.",
    "Le bandeau cookies et les pages légales sont en place et conformes.",
    "Le certificat SSL est actif (HTTPS) sur l'ensemble des pages.",
    "Un temps de formation à l'administration WordPress est réalisé avec l'équipe Connectis Solutions.",
]))

# ------------------------------------------------------------- PART 2 ------
part_title_page("PARTIE 2", "DOSSIER DE PROJET COMMERCIAL")

h1_entry("1", "Résumé exécutif")
story.append(P(
    "Connectis Solutions se positionne comme l'acteur local de référence pour l'équipement et la connectivité "
    "des professionnels : vidéosurveillance, téléphonie, "
    "internet et fibre optique, matériel informatique et télécom, abonnements et services associés. Porté par "
    "une équipe commerciale de terrain réactive, le projet vise à doter l'entreprise d'un site vitrine qui "
    "renforce sa crédibilité, génère des demandes de devis qualifiées et appuie l'action commerciale au quotidien.",
    "Body"))
story.append(PageBreak())

h1_entry("2", "Présentation de l'offre")
story.append(P("L'offre Connectis Solutions couvre l'ensemble du parcours client, du conseil à la maintenance, "
               "autour de six familles de produits et services :", "Body"))
story.append(bullets([
    "<b>Vidéosurveillance</b> — caméras IP, enregistreurs, alarme, contrôle d'accès pour locaux professionnels.",
    "<b>Téléphonie &amp; standard</b> — téléphonie fixe, VoIP/IP, standards pour entreprises, mobile.",
    "<b>Internet &amp; Fibre optique</b> — raccordement, box internet, Wi-Fi professionnel.",
    "<b>Matériel informatique &amp; télécom</b> — vente, installation et configuration.",
    "<b>Abonnements &amp; forfaits</b> — formules mensuelles avec ou sans engagement.",
    "<b>Services</b> — installation, maintenance, dépannage, SAV.",
]))
story.append(P("Cette approche « guichet unique » permet à un même client de regrouper la sécurité, la "
               "connectivité et le matériel auprès d'un seul interlocuteur local.", "Body"))
story.append(PageBreak())

h1_entry("3", "Marché et positionnement")
story.append(P(
    "Le marché de la vidéosurveillance et des télécoms connaît une croissance continue, portée par la "
    "généralisation de la fibre optique, la migration de la téléphonie vers l'IP et une demande croissante "
    "de sécurité chez les professionnels, quel que soit leur secteur d'activité. Sur ce marché, "
    "les grands opérateurs nationaux (Orange Pro, SFR Business, Verisure, etc.) proposent des offres "
    "standardisées, avec un service client souvent distant et peu personnalisé.", "Body"))
story.append(P(
    "Connectis Solutions se différencie par un ancrage local fort, une équipe commerciale de terrain "
    "capable d'intervenir rapidement, et une approche sur mesure adaptée à chaque client, là où les grands "
    "groupes privilégient des parcours standardisés à distance.", "Body"))
story.append(PageBreak())

h1_entry("4", "Proposition de valeur &amp; avantages concurrentiels")
story.append(table_std(
    [[P("Atout", "CellHead"), P("Bénéfice pour le client", "CellHead")],
     [P("Proximité locale", "Cell"), P("Interlocuteur joignable, connaissance du terrain, déplacement rapide.", "Cell")],
     [P("Réactivité de la flotte commerciale", "Cell"), P("Rendez-vous et interventions sous délai court, sans passer par un centre d'appels national.", "Cell")],
     [P("Offre sur mesure", "Cell"), P("Solutions adaptées au besoin réel du client, quels que soient son secteur et sa taille, pas de forfait imposé.", "Cell")],
     [P("Guichet unique multi-services", "Cell"), P("Un seul interlocuteur pour la sécurité, la connectivité et le matériel.", "Cell")],
     [P("Accompagnement après-vente", "Cell"), P("Maintenance et SAV assurés localement, contrat de suivi possible.", "Cell")],
     ],
    col_widths=[58 * mm, 110 * mm]))
story.append(PageBreak())

h1_entry("5", "Organisation commerciale")
story.append(P(
    "L'action commerciale de Connectis Solutions repose sur une flotte commerciale terrain : des commerciaux "
    "itinérants qui se déplacent chez les clients professionnels pour évaluer "
    "les besoins, présenter les offres et établir des devis sur place. Le site vitrine vient renforcer cette "
    "organisation en amont (génération de demandes de devis, prise de rendez-vous en ligne) et pendant le "
    "rendez-vous (consultation des fiches produits sur mobile/tablette).", "Body"))
story.append(bullets([
    "Génération de leads qualifiés via le formulaire de devis et la prise de rendez-vous en ligne.",
    "Outil d'aide à la vente pour les commerciaux en clientèle (fiches produits, simulateurs).",
    "Renforcement de la présence digitale en complément du travail de terrain (avis clients, réseaux sociaux).",
]))
story.append(PageBreak())

h1_entry("6", "Plan d'action commercial et rôle du site")
story.append(bullets([
    "<b>Prospection terrain</b> — ciblage des entreprises et professionnels par la flotte commerciale.",
    "<b>Partenariats locaux</b> — développement de partenariats avec des acteurs professionnels prescripteurs.",
    "<b>Présence digitale</b> — site vitrine référencé localement, fiche Google Business Profile, réseaux sociaux, "
    "avis clients.",
    "<b>Suivi commercial</b> — les demandes issues du site (devis, rendez-vous) sont transmises directement à "
    "l'équipe commerciale pour un traitement rapide, cohérent avec le positionnement « réactivité ».",
]))
story.append(PageBreak())

h1_entry("7", "Indicateurs de performance (KPIs)")
story.append(table_std(
    [[P("Indicateur", "CellHead"), P("Objectif suivi", "CellHead")],
     [P("Nombre de demandes de devis / mois", "Cell"), P("Mesurer la génération de leads via le site.", "Cell")],
     [P("Taux de conversion devis → vente", "Cell"), P("Évaluer la qualité des leads transmis à l'équipe commerciale.", "Cell")],
     [P("Trafic local (secteur d'intervention)", "Cell"), P("Suivre l'efficacité du référencement local.", "Cell")],
     [P("Prises de rendez-vous en ligne", "Cell"), P("Mesurer l'usage de la prise de rendez-vous directe.", "Cell")],
     [P("Avis clients collectés", "Cell"), P("Suivre la réputation en ligne et la confiance générée.", "Cell")],
     ],
    col_widths=[68 * mm, 100 * mm]))
story.append(PageBreak())

h1_entry("8", "Prochaines étapes")
story.append(bullets([
    "Validation du présent cahier des charges par Connectis Solutions.",
    "Mise en place du nom de domaine (connectis-solutions.fr) et de l'hébergement Planet Hoster.",
    "Collecte des contenus (textes, photos, logos partenaires).",
    "Réalisation des maquettes desktop/mobile pour validation avant développement.",
    "Développement du site sous WordPress / Blocksy, intégration des contenus et des fonctionnalités transverses.",
    "Tests, recette, mise en ligne et formation de l'équipe à l'administration du site.",
]))
story.append(PageBreak())

h1_entry("A", "Annexe — Glossaire technique")
story.append(table_std(
    [[P("Terme", "CellHead"), P("Définition", "CellHead")],
     [P("CMS", "Cell"), P("Système de gestion de contenu permettant d'administrer un site sans compétences techniques poussées (ici : WordPress).", "Cell")],
     [P("Thème Blocksy", "Cell"), P("Thème WordPress léger et personnalisable utilisé pour l'habillage graphique du site.", "Cell")],
     [P("MySQL", "Cell"), P("Système de base de données utilisé par WordPress pour stocker le contenu du site.", "Cell")],
     [P("SEO local", "Cell"), P("Techniques de référencement visant à apparaître dans les recherches liées à une zone géographique (le secteur d'intervention de l'entreprise).", "Cell")],
     [P("RGPD", "Cell"), P("Règlement général sur la protection des données, encadrant la collecte des données personnelles (formulaires, cookies).", "Cell")],
     [P("VoIP", "Cell"), P("Téléphonie transitant par internet plutôt que par le réseau téléphonique traditionnel.", "Cell")],
     ],
    col_widths=[42 * mm, 126 * mm]))

# --------------------------------------------------------- PAGE TEMPLATES --
def draw_cover_background(c, doc):
    c.saveState()
    c.setFillColor(NAVY)
    c.rect(0, 0, PAGE_W, PAGE_H, fill=1, stroke=0)
    c.setFillColor(NAVY2)
    c.circle(PAGE_W * 0.5, PAGE_H * 0.72, 90 * mm, fill=1, stroke=0)
    c.restoreState()

def draw_part_background(c, doc):
    c.saveState()
    c.setFillColor(NAVY)
    c.rect(0, 0, PAGE_W, PAGE_H, fill=1, stroke=0)
    c.restoreState()

def draw_normal_page(c, doc):
    c.saveState()
    c.setFillColor(BLUE_DARK)
    c.rect(0, PAGE_H - 10 * mm, PAGE_W, 10 * mm, fill=1, stroke=0)
    c.setFillColor(WHITE)
    c.setFont("Helvetica-Bold", 8)
    c.drawString(15 * mm, PAGE_H - 7 * mm, "CONNECTIS SOLUTIONS")
    c.setFont("Helvetica", 8)
    c.drawRightString(PAGE_W - 15 * mm, PAGE_H - 7 * mm,
                       "Cahier des charges & Projet commercial — Site vitrine")
    c.setStrokeColor(LGREY)
    c.setLineWidth(0.4)
    c.line(15 * mm, 14 * mm, PAGE_W - 15 * mm, 14 * mm)
    c.setFillColor(GREY)
    c.setFont("Helvetica", 7.5)
    c.drawString(15 * mm, 9 * mm, "Connectis Solutions — Document confidentiel")
    c.drawRightString(PAGE_W - 15 * mm, 9 * mm, f"Page {doc.page}")
    c.restoreState()

class MyDocTemplate(BaseDocTemplate):
    def afterFlowable(self, flowable):
        if isinstance(flowable, Table) and hasattr(flowable, "_toc_h1"):
            text = flowable._toc_h1
            key = f"h1-{id(flowable)}"
            self.canv.bookmarkPage(key)
            self.canv.addOutlineEntry(text, key, level=0)
            self.notify("TOCEntry", (0, text, self.page, key))
        elif isinstance(flowable, Paragraph):
            style_name = flowable.style.name
            text = flowable.getPlainText()
            if style_name == "PartTitle" and hasattr(flowable, "_toc_part"):
                key = f"part-{id(flowable)}"
                self.canv.bookmarkPage(key)
                full = f"{text} — {flowable._toc_part}"
                self.canv.addOutlineEntry(full, key, level=0)
                self.notify("TOCEntry", (0, full, self.page, key))
            elif style_name == "H2" and text.strip():
                key = f"h2-{id(flowable)}"
                self.canv.bookmarkPage(key)
                self.canv.addOutlineEntry(text, key, level=1)
                self.notify("TOCEntry", (1, text, self.page, key))

margin = 15 * mm
frame_normal = Frame(margin, 18 * mm, PAGE_W - 2 * margin, PAGE_H - 32 * mm,
                      id="normal", topPadding=0, bottomPadding=0)
frame_full = Frame(0, 0, PAGE_W, PAGE_H, id="full", leftPadding=0, rightPadding=0,
                    topPadding=0, bottomPadding=0)

doc = MyDocTemplate(OUT, pagesize=A4,
                     leftMargin=margin, rightMargin=margin,
                     topMargin=18 * mm, bottomMargin=18 * mm,
                     title="Connectis Solutions — Cahier des charges et projet commercial",
                     author="Connectis Solutions")

doc.addPageTemplates([
    PageTemplate(id="cover", frames=[frame_full], onPage=draw_cover_background),
    PageTemplate(id="part", frames=[frame_full], onPage=draw_part_background),
    PageTemplate(id="normal", frames=[frame_normal], onPage=draw_normal_page),
])

story.insert(0, NextPageTemplate("cover"))

doc.multiBuild(story)
print("PDF généré :", OUT)
