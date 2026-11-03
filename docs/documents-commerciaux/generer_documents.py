# -*- coding: utf-8 -*-
"""Génère « Connectis - Bon de commande et contrats.pdf » : bon de commande, CGV, contrat de service
maintenance impression, contrat de service & abonnement, mandat de prélèvement SEPA.

PDF à champs remplissables (à l'écran ou à la main). Les CGV sont lues dans connectis-legal.php :
une seule source, celle du site. Régénérer : python docs/documents-commerciaux/generer_documents.py
"""
import io
import os
import re
import sys

from reportlab.lib.colors import HexColor, white
from reportlab.lib.enums import TA_JUSTIFY
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import ParagraphStyle
from reportlab.pdfbase.pdfmetrics import stringWidth
from reportlab.pdfgen import canvas
from reportlab.platypus import Flowable, Frame, Paragraph

import conditions_service

HERE = os.path.dirname(os.path.abspath(__file__))
ROOT = os.path.abspath(os.path.join(HERE, "..", ".."))
LOGO = os.path.join(ROOT, "wp-content", "mu-plugins", "connectis-maintenance", "logo-full.png")
LEGAL = os.path.join(ROOT, "wp-content", "mu-plugins", "connectis-legal.php")
OUT = os.path.join(HERE, "Connectis - Bon de commande et contrats.pdf")

NAVY, BLUE, CYAN = HexColor("#070d1f"), HexColor("#1b63e6"), HexColor("#33d0e8")
SOFT, BORDER, GREY = HexColor("#eef4fd"), HexColor("#cfd8e8"), HexColor("#4a5568")

W, H = A4
M = 34
CW = W - 2 * M

SOCIETE = "CONNECTIS SOLUTIONS"
PIED = [
    "CONNECTIS SOLUTIONS – 110 boulevard d'Austrasie, 54000 Nancy – Tél. 01 87 66 84 69 – contact@connectis-solutions.fr – www.connectis-solutions.fr",
    "SAS au capital de 10 000 € – RCS Nancy 103 370 557 – SIRET 103 370 557 00011 – Code NAF 62.02A – N° TVA FR64 103 370 557",
]


def cgv_blocs():
    """Titre, intro, articles (titre, paragraphes) et date de version, lus dans connectis-legal.php."""
    src = open(LEGAL, encoding="utf-8").read()
    corps = re.search(r"\$cgv = <<<'HTML'\n(.*?)\nHTML;", src, re.S).group(1)

    def inline(t):
        t = re.sub(r"<span class='cn-cl'>(.*?)</span>", r"<b>\1</b>", t)
        t = t.replace("<strong>", "<b>").replace("</strong>", "</b>")
        return re.sub(r"</?a\b[^>]*>", "", t).strip()

    intro = inline(re.search(r"<p class='cn-lead'>(.*?)</p>", corps, re.S).group(1))
    arts = []
    for sec in re.findall(r"<section id='art-\d+'.*?</section>", corps, re.S):
        titre = re.search(r"<h2>(.*?)</h2>", sec).group(1)
        arts.append((titre, [inline(p) for p in re.findall(r"<p>(.*?)</p>", sec, re.S)]))
    version = re.search(r"<p class='cn-updated'>(.*?)</p>", corps).group(1)
    assert len(arts) == 19, len(arts)
    return intro, arts, version


class Bloc(Flowable):
    """Plusieurs paragraphes toujours placés ensemble (un titre ne reste jamais seul en bas de colonne)."""

    def __init__(self, items):
        super().__init__()
        self.items = items
        self.hauteurs = []

    def wrap(self, largeur, haut):
        self.largeur = largeur
        self.hauteurs = [it.wrap(largeur, haut)[1] for it in self.items]
        return largeur, sum(self.hauteurs)

    def draw(self):
        y = sum(self.hauteurs)
        for it, h in zip(self.items, self.hauteurs):
            y -= h
            it.drawOn(self.canv, 0, y)


class Doc:
    def __init__(self, cible):
        self.c = canvas.Canvas(cible, pagesize=A4)
        self.c.setTitle("Connectis Solutions – Bon de commande et contrats")
        self.c.setAuthor(SOCIETE)
        self.page = 0
        self.k = 0

    # --- outils -------------------------------------------------------
    def nom(self, base):
        self.k += 1
        return "p%d_%s_%d" % (self.page, base, self.k)

    def texte(self, x, top, s, taille=7, police="Helvetica-Bold", couleur=GREY, centre=False):
        c = self.c
        c.setFont(police, taille)
        c.setFillColor(couleur)
        (c.drawCentredString if centre else c.drawString)(x, H - top - taille, s)

    def champ(self, x, top, w, h=13, tip="", multiligne=False, taille=8):
        self.c.acroForm.textfield(
            name=self.nom("t"), tooltip=tip, x=x, y=H - top - h, width=w, height=h,
            borderWidth=0.4, borderColor=BORDER, fillColor=SOFT, textColor=NAVY,
            fontName="Helvetica", fontSize=taille, forceBorder=True,
            fieldFlags="multiline" if multiligne else "", maxlen=600)

    def case(self, x, top, tip="", cochee=False, taille=9):
        self.c.acroForm.checkbox(
            name=self.nom("c"), tooltip=tip, checked=cochee, x=x, y=H - top - taille, size=taille,
            buttonStyle="cross", borderWidth=0.8, borderColor=BLUE, fillColor=white, textColor=NAVY,
            forceBorder=True, fieldFlags="")

    def case_texte(self, x, top, libelle, tip=None, cochee=False, couleur=NAVY):
        self.case(x, top, tip or libelle, cochee)
        self.texte(x + 13, top + 1.2, libelle, 7, "Helvetica", couleur)
        return x + 13 + stringWidth(libelle, "Helvetica", 7) + 12

    def ligne(self, top, items, x0=None, x1=None, h=13, ecart=7):
        """Étiquettes + champs sur une ligne ; une largeur None remplit jusqu'au bord droit."""
        x0 = M + 8 if x0 is None else x0
        x1 = M + CW - 8 if x1 is None else x1
        x = x0
        for libelle, largeur in items:
            lw = stringWidth(libelle, "Helvetica-Bold", 6.6) + 4
            fin = x1 if largeur is None else x + largeur
            self.texte(x, top + 3.2, libelle, 6.6)
            self.champ(x + lw, top, fin - x - lw, h, libelle.strip(" :."))
            x = fin + ecart

    def cadre(self, top, haut, titre=None, x=None, w=None, bandeau=15):
        x = M if x is None else x
        w = CW if w is None else w
        c = self.c
        c.setStrokeColor(BLUE)
        c.setLineWidth(0.9)
        c.roundRect(x, H - top - haut, w, haut, 4, stroke=1, fill=0)
        if titre:
            c.setFillColor(BLUE)
            c.roundRect(x, H - top - bandeau, w, bandeau, 4, stroke=0, fill=1)
            c.rect(x, H - top - bandeau, w, bandeau / 2, stroke=0, fill=1)
            self.texte(x + 8, top + 4, titre, 7.2, "Helvetica-Bold", white)

    def paragraphes(self, top, haut, textes, taille=6.3, x=None, w=None):
        st = ParagraphStyle("d", fontName="Helvetica", fontSize=taille, leading=taille + 1.4, textColor=NAVY,
                            alignment=TA_JUSTIFY, spaceAfter=1.6)
        x = M if x is None else x
        w = CW if w is None else w
        f = Frame(x, H - top - haut, w, haut, leftPadding=0, rightPadding=0, topPadding=0, bottomPadding=0)
        liste = [Paragraph(t, st) for t in textes]
        f.addFromList(liste, self.c)
        return liste

    # --- éléments communs ----------------------------------------------
    def nouvelle_page(self):
        if self.page:
            self.c.showPage()
        self.page += 1

    def entete(self, lignes, date=True):
        c = self.c
        c.drawImage(LOGO, M, H - 30 - 56, width=64, height=56, mask="auto", preserveAspectRatio=True)
        for i, l in enumerate(lignes):
            self.texte(M + 80, 34 + i * 20, l, 16.5, "Helvetica-Bold", NAVY)
        c.setFillColor(CYAN)
        c.rect(M + 80, H - 34 - len(lignes) * 20 - 3, 40, 2.2, stroke=0, fill=1)
        if date:
            c.setStrokeColor(BLUE)
            c.setLineWidth(0.9)
            c.roundRect(W - M - 150, H - 30 - 40, 150, 40, 4, stroke=1, fill=0)
            self.texte(W - M - 75, 36, "DATE DE PROPOSITION", 6.6, "Helvetica-Bold", BLUE, centre=True)
            self.champ(W - M - 135, 50, 120, 14, "Date de proposition")

    def pied(self):
        c = self.c
        c.setStrokeColor(BORDER)
        c.setLineWidth(0.6)
        c.line(M, 36, W - M, 36)
        for i, l in enumerate(PIED):
            c.setFont("Helvetica", 5.9)
            c.setFillColor(GREY)
            c.drawCentredString(W / 2, 26 - i * 8, l)
        c.setFont("Helvetica", 6)
        c.setFillColor(GREY)
        c.drawRightString(W - M, H - 22, "Page %d" % self.page)

    def facturation_installation(self, top):
        """Blocs « adresse de facturation » et « adresse d'installation » ; renvoie le haut libre suivant."""
        self.cadre(top, 93, "ADRESSE DE FACTURATION")
        t = top + 21
        self.ligne(t, [("SOCIÉTÉ", 300), ("N° SIRET", None)])
        self.ligne(t + 18, [("ADRESSE", None)])
        self.ligne(t + 36, [("CODE POSTAL", 105), ("VILLE", 170), ("CONTACT", None)])
        self.ligne(t + 54, [("TÉLÉPHONE", 150), ("E-MAIL DE FACTURATION", None)])
        top2 = top + 99
        self.cadre(top2, 76, "ADRESSE D'INSTALLATION")
        self.case_texte(W - M - 150, top2 + 3.5, "Identique à l'adresse de facturation", couleur=white)
        t = top2 + 21
        self.ligne(t, [("N°", 70), ("RUE", None)])
        self.ligne(t + 18, [("CODE POSTAL", 105), ("VILLE", None)])
        self.ligne(t + 36, [("TÉLÉPHONE", 150), ("CONTACT SUR PLACE", None)])
        return top2 + 76

    def signatures(self, top):
        self.texte(M, top, "Fait en deux exemplaires dont un est laissé au client qui reconnaît l'avoir reçu ce jour.", 7.4,
                   "Helvetica-Bold", NAVY)
        larg = (CW - 14) / 2
        for i, (g, d) in enumerate([("Sous réserve de validation", "L'attaché commercial CONNECTIS SOLUTIONS"),
                                    ("Lu et approuvé – Bon pour accord", "Le client – Cachet commercial")]):
            x = M + i * (larg + 14)
            self.cadre(top + 12, 52, x=x, w=larg)
            self.texte(x + 8, top + 17, "À", 6.6)
            self.champ(x + 16, top + 16.5, larg / 2 - 22, 12, "Lieu")
            self.texte(x + larg / 2 + 2, top + 17, "Le", 6.6)
            self.champ(x + larg / 2 + 14, top + 16.5, larg / 2 - 22, 12, "Date")
            self.texte(x + 8, top + 34, g, 7, "Helvetica-Bold", NAVY)
            self.texte(x + 8, top + 43, d, 6.6, "Helvetica", GREY)

    # --- pages ---------------------------------------------------------
    def bon_de_commande(self, pages_cgv):
        self.nouvelle_page()
        self.entete(["BON DE COMMANDE"])
        x = M + 80
        x = self.case_texte(x, 62, "Vente")
        self.case_texte(x, 62, "Location financière")
        bas = self.facturation_installation(100)
        self.texte(M, bas + 11, "Entre le client ci-dessus dénommé et CONNECTIS SOLUTIONS", 8.3, "Helvetica-Bold", BLUE)
        self.texte(W - M - 190, bas + 12, "Devis n°", 7, "Helvetica-Bold", NAVY)
        self.champ(W - M - 150, bas + 8, 150, 14, "Devis n°")
        top = bas + 28
        cols = [("RÉFÉRENCE", 88), ("DÉSIGNATION", 230), ("PRIX UNITAIRE H.T.", 78), ("QTÉ", 40), ("MONTANT H.T.", 87)]
        c = self.c
        c.setFillColor(NAVY)
        c.rect(M, H - top - 22, CW, 22, stroke=0, fill=1)
        x = M
        for nom, l in cols:
            self.texte(x + l / 2, top + 7, nom, 6.8, "Helvetica-Bold", white, centre=True)
            x += l
        n = 10
        for i in range(n):
            y0 = top + 22 + i * 17
            x = M
            for _, l in cols:
                self.champ(x + 2, y0 + 2, l - 4, 13, "Ligne %d" % (i + 1), taille=7.5)
                x += l
        c.setStrokeColor(BLUE)
        c.setLineWidth(0.9)
        c.rect(M, H - top - 22 - n * 17, CW, 22 + n * 17, stroke=1, fill=0)
        x = M
        for _, l in cols[:-1]:
            x += l
            c.line(x, H - top - 22, x, H - top - 22 - n * 17)
        fin = top + 22 + n * 17 + 6
        self.cadre(fin, 100, x=M, w=300, titre=None)
        self.texte(M + 8, fin + 5, "OBSERVATION", 6.8, "Helvetica-Bold", NAVY)
        self.texte(M + 68, fin + 5.2, "(en cas de reprise de matériel, le client atteste en avoir la pleine propriété)", 5.8, "Helvetica", GREY)
        self.champ(M + 8, fin + 18, 284, 74, "Observation", multiligne=True, taille=7.5)
        xr = M + 308
        for i, lib in enumerate(["MONTANT H.T.", "TVA", "PRIX TOTAL T.T.C."]):
            y0 = fin + i * 22
            self.texte(xr, y0 + 7, lib, 7.4, "Helvetica-Bold", NAVY)
            self.champ(xr + 100, y0 + 2, CW - 308 - 100, 15, lib)
        self.texte(xr, fin + 71, "En cas de location financière :", 6.8, "Helvetica-Bold", NAVY)
        self.texte(xr, fin + 83, "Nombre de loyers", 6.6)
        self.champ(xr + 62, fin + 81, 36, 12, "Nombre de loyers")
        x2 = self.case_texte(xr + 104, fin + 82, "mensuel")
        x2 = self.case_texte(x2 - 6, fin + 82, "trimestriel")
        self.texte(xr, fin + 97, "Loyer H.T. :", 6.6)
        self.champ(xr + 42, fin + 95, 60, 12, "Loyer HT")
        self.texte(xr + 104, fin + 97, "€", 7, "Helvetica-Bold", NAVY)
        y = fin + 108
        self.case(M, y + 1)
        self.texte(M + 13, y + 2, "Financement par un organisme partenaire : l'obtention du financement est une condition suspensive de la commande (art. 12.4 des CGV).",
                   6.6, "Helvetica", NAVY)
        self.paragraphes(y + 15, 70, [
            "<b>Le client déclare expressément :</b> 1/ avoir pris connaissance des conditions générales de vente de CONNECTIS SOLUTIONS figurant au verso (%s) "
            "et en accepter le contenu, qui lui est opposable ; 2/ que les produits et services commandés sont destinés aux besoins de son activité professionnelle "
            "et en rapport direct avec celle-ci ; 3/ avoir été informé des capacités et des conditions d'utilisation du matériel et que celui-ci correspond à ses besoins "
            "tels qu'il les a exprimés." % pages_cgv,
            "<b>Commande :</b> le contrat est formé à la réception du présent bon, daté et signé (art. 3 des CGV). "
            "<b>Réserve de propriété :</b> les produits demeurent la propriété de CONNECTIS SOLUTIONS jusqu'au paiement effectif et intégral du prix (art. 11 des CGV). "
            "<b>Attribution de compétence :</b> à défaut d'accord amiable, le tribunal de commerce de Nancy est seul compétent (art. 19.2 des CGV).",
        ])
        self.signatures(y + 82)
        assert y + 82 + 70 < H - 40, "bon de commande trop long"
        self.pied()

    def colonnes(self, titres_page, intro, arts, version):
        """Place un texte juridique sur deux colonnes ; renvoie le nombre de pages."""
        st_t = ParagraphStyle("t", fontName="Helvetica-Bold", fontSize=7.3, leading=8.8, textColor=BLUE, spaceBefore=4, spaceAfter=1.5)
        st_p = ParagraphStyle("p", fontName="Helvetica", fontSize=6.5, leading=7.9, textColor=NAVY, alignment=TA_JUSTIFY, spaceAfter=1.5)
        histoire = [Paragraph(intro, st_p)]
        for titre, ps in arts:
            histoire.append(Bloc([Paragraph(titre, st_t), Paragraph(ps[0], st_p)]))
            histoire.extend(Paragraph(p, st_p) for p in ps[1:])
        histoire.append(Paragraph("<i>%s</i>" % version, st_p))
        larg = (CW - 14) / 2
        nb = 0
        while histoire:
            self.nouvelle_page()
            nb += 1
            self.entete(titres_page, date=False)
            for i in range(2):
                f = Frame(M + i * (larg + 14), 48, larg, H - 48 - 100, leftPadding=0, rightPadding=0, topPadding=0, bottomPadding=0)
                f.addFromList(histoire, self.c)
            self.pied()
            assert nb < 5
        return nb

    def contrat_commun(self, titres, date_effet=True):
        self.nouvelle_page()
        self.entete(titres)
        return self.facturation_installation(100)

    def declarations_contrat(self, top, objet, extra):
        reste = self.paragraphes(top, 72, [
            "<b>Le client déclare expressément :</b> 1/ avoir pris connaissance des conditions générales de vente et des conditions générales des contrats de service "
            "de CONNECTIS SOLUTIONS (%s) ainsi que du présent contrat, et en accepter le contenu, qui lui est opposable ; 2/ que %s sont destinés aux besoins de son activité professionnelle et en rapport "
            "direct avec celle-ci ; 3/ avoir été informé des capacités et des conditions d'utilisation et que ceux-ci correspondent à ses besoins tels qu'il les a exprimés." % (self.renvoi, objet),
            extra,
            "<b>Durée et résiliation :</b> durée initiale au recto, puis renouvellement par périodes d'un an sauf résiliation notifiée trois (3) mois avant l'échéance "
            "(art. 9 des conditions des contrats de service) ; indemnité de résiliation anticipée selon l'art. 10.2. "
            "<b>Attribution de compétence :</b> à défaut d'accord amiable, le tribunal de commerce de Nancy est seul compétent (art. 19.2 des CGV).",
        ], taille=6.0)
        assert not reste, "déclarations tronquées"

    def engagements(self, top):
        x = M
        for lib, tip, suffixe, w in [("Délai d'intervention :", "Délai d'intervention", "h ouvrées", 26),
                                     ("Indemnité de résiliation anticipée :", "Indemnité de résiliation anticipée", "% des redevances restant à courir", 26)]:
            self.texte(x, top + 2.5, lib, 7, "Helvetica", NAVY)
            x += stringWidth(lib, "Helvetica", 7) + 4
            self.champ(x, top, w, 12, tip)
            x += w + 3
            self.texte(x, top + 2.5, suffixe, 7, "Helvetica", NAVY)
            x += stringWidth(suffixe, "Helvetica", 7) + 22

    def facturation(self, top):
        self.texte(M, top, "FACTURATION", 8, "Helvetica-Bold", BLUE)
        self.texte(M, top + 15, "Période de facturation :", 7, "Helvetica", NAVY)
        x = self.case_texte(M + 92, top + 14, "Mensuelle")
        self.case_texte(x, top + 14, "Trimestrielle")
        self.texte(M, top + 32, "Mode de paiement :", 7, "Helvetica", NAVY)
        x = self.case_texte(M + 92, top + 31, "Prélèvement SEPA (mandat en dernière page)")
        x = self.case_texte(x, top + 31, "Virement")
        x = self.case_texte(x, top + 31, "Mandat administratif")
        x = self.case_texte(x, top + 31, "Autre :")
        self.champ(x - 6, top + 29.5, M + CW - x + 6, 12, "Autre mode de paiement")

    def contrat_maintenance(self):
        bas = self.contrat_commun(["CONTRAT DE SERVICE", "MAINTENANCE IMPRESSION"])
        self.texte(M, bas + 10, "Il est convenu ce qui suit : CONNECTIS SOLUTIONS prend en charge l'entretien du matériel ci-dessous aux tarifs et conditions ci-après.",
                   7.3, "Helvetica-Bold", NAVY)
        top = bas + 24
        cols = [("MODÈLE", 235), ("N° DE SÉRIE", 140), ("DATE D'EFFET DU CONTRAT", 148)]
        c = self.c
        c.setFillColor(NAVY)
        c.rect(M, H - top - 20, CW, 20, stroke=0, fill=1)
        x = M
        for nom, l in cols:
            self.texte(x + 6, top + 6, nom, 6.8, "Helvetica-Bold", white)
            x += l
        for i in range(3):
            y0 = top + 20 + i * 17
            self.champ(M + 2, y0 + 3, 100, 13, "Modèle %d" % (i + 1))
            xx = self.case_texte(M + 108, y0 + 5, "Neuf", "Neuf (ligne %d)" % (i + 1))
            self.case_texte(xx - 4, y0 + 5, "Reconditionné", "Reconditionné (ligne %d)" % (i + 1))
            self.champ(M + 237, y0 + 3, 134, 13, "Numéro de série %d" % (i + 1))
        self.champ(M + 379, top + 26, 140, 13, "Date d'effet du contrat")
        self.texte(M + 449, top + 46, "À défaut : date de livraison", 6.4, "Helvetica", GREY, centre=True)
        self.texte(M + 449, top + 56, "(cf. bon de livraison)", 6.4, "Helvetica", GREY, centre=True)
        c.setStrokeColor(BLUE)
        c.setLineWidth(0.9)
        c.rect(M, H - top - 20 - 3 * 17, CW, 20 + 3 * 17, stroke=1, fill=0)
        c.line(M + 235, H - top - 20, M + 235, H - top - 20 - 3 * 17)
        c.line(M + 375, H - top - 20, M + 375, H - top - 20 - 3 * 17)
        # tarifs
        t2 = top + 20 + 3 * 17 + 10
        for bloc, nom in enumerate(["MODÈLE A4", "MODÈLE A4 / A3"]):
            y0 = t2 + bloc * 64
            c.setFillColor(NAVY)
            c.rect(M, H - y0 - 20, CW, 20, stroke=0, fill=1)
            for x, lib in [(M + 6, nom), (M + 120, "VOLUME COPIES / MOIS"), (M + 244, "FORFAIT (€ H.T.) / MOIS"), (M + 368, "COÛT COPIE (€ H.T.)")]:
                self.texte(x, y0 + 6.5, lib, 6.8, "Helvetica-Bold", white)
            for j, (lib) in enumerate(["Polychromes", "Copies noires"]):
                yy = y0 + 20 + j * 19
                self.champ(M + 120, yy + 3, 84, 13, "Volume %s %s" % (nom, lib))
                self.texte(M + 206, yy + 6, "copies", 6.6, "Helvetica", GREY)
                self.champ(M + 244, yy + 3, 84, 13, "Forfait %s %s" % (nom, lib))
                self.texte(M + 330, yy + 6, "€", 7, "Helvetica-Bold", NAVY)
                self.texte(M + 368, yy + 6, lib, 6.6, "Helvetica-Bold", GREY)
                self.champ(M + 430, yy + 3, 70, 13, "Coût copie %s %s" % (nom, lib))
                self.texte(M + 503, yy + 6, "€", 7, "Helvetica-Bold", NAVY)
            c.setStrokeColor(BLUE)
            c.rect(M, H - y0 - 20 - 38, CW, 20 + 38, stroke=1, fill=0)
            for x in (M + 114, M + 238, M + 362):
                c.line(x, H - y0 - 20, x, H - y0 - 58)
            self.champ(M + 4, y0 + 24, 106, 30, "Désignation %s" % nom, multiligne=True, taille=7)
        y = t2 + 2 * 64 + 2
        self.texte(M, y + 4, "ABONNEMENT SERVICE :", 7, "Helvetica-Bold", NAVY)
        self.champ(M + 100, y, CW - 100, 13, "Abonnement service")
        self.texte(M, y + 22, "Consommables (toner, encre) :", 7, "Helvetica", NAVY)
        x = self.case_texte(M + 110, y + 21, "inclus")
        x = self.case_texte(x, y + 21, "non inclus")
        self.texte(x + 14, y + 22, "Durée initiale du contrat :", 7, "Helvetica", NAVY)
        self.champ(x + 100, y + 20, 40, 12, "Durée initiale en mois")
        self.texte(x + 143, y + 22, "mois", 7, "Helvetica", NAVY)
        self.engagements(y + 38)
        self.facturation(y + 58)
        self.texte(M, y + 106, "CONDITIONS PARTICULIÈRES", 8, "Helvetica-Bold", BLUE)
        self.champ(M, y + 117, CW, 24, "Conditions particulières", multiligne=True, taille=7.5)
        self.declarations_contrat(y + 146, "le matériel et les prestations objets du présent contrat",
                                  "<b>Maintenance :</b> périmètre, exclusions et obligations des parties selon les articles 3 à 5 ; copies relevées au compteur de l'appareil et "
                                  "facturées selon l'article 6. <b>Réserve de propriété :</b> art. 11 des CGV.")
        self.signatures(y + 220)
        assert y + 220 + 64 < H - 40, "contrat maintenance trop long : %d" % (y + 220 + 64)
        self.pied()

    def contrat_abonnement(self):
        bas = self.contrat_commun(["CONTRAT DE SERVICE", "& ABONNEMENT"])
        self.texte(M, bas + 10, "Il est convenu ce qui suit : CONNECTIS SOLUTIONS fournit au client les services récurrents ci-dessous aux tarifs et conditions ci-après.",
                   7.3, "Helvetica-Bold", NAVY)
        top = bas + 24
        cols = [("SERVICE SOUSCRIT", 218), ("QTÉ", 40), ("PRIX MENSUEL H.T.", 90), ("DATE D'EFFET", 90), ("DURÉE (MOIS)", 85)]
        c = self.c
        c.setFillColor(NAVY)
        c.rect(M, H - top - 20, CW, 20, stroke=0, fill=1)
        x = M
        for nom, l in cols:
            self.texte(x + l / 2, top + 6.5, nom, 6.8, "Helvetica-Bold", white, centre=True)
            x += l
        n = 8
        for i in range(n):
            y0 = top + 20 + i * 17
            x = M
            for k, (_, l) in enumerate(cols):
                self.champ(x + 2, y0 + 3, l - 4, 13, "Ligne %d colonne %d" % (i + 1, k + 1), taille=7.5)
                x += l
        c.setStrokeColor(BLUE)
        c.setLineWidth(0.9)
        c.rect(M, H - top - 20 - n * 17, CW, 20 + n * 17, stroke=1, fill=0)
        x = M
        for _, l in cols[:-1]:
            x += l
            c.line(x, H - top - 20, x, H - top - 20 - n * 17)
        y = top + 20 + n * 17 + 6
        self.texte(M, y + 3, "Exemples : internet / fibre, téléphonie IP (postes, lignes), vidéosurveillance (stockage, supervision), forfaits mobiles, maintenance.", 6.4, "Helvetica", GREY)
        self.texte(M, y + 20, "Frais de mise en service :", 7, "Helvetica-Bold", NAVY)
        self.champ(M + 100, y + 17, 80, 13, "Frais de mise en service HT")
        self.texte(M + 183, y + 20, "€ H.T.", 7, "Helvetica", NAVY)
        self.engagements(y + 38)
        self.facturation(y + 58)
        self.texte(M, y + 106, "CONDITIONS PARTICULIÈRES", 8, "Helvetica-Bold", BLUE)
        self.champ(M, y + 117, CW, 38, "Conditions particulières", multiligne=True, taille=7.5)
        self.declarations_contrat(y + 162, "les services souscrits",
                                  "<b>Services de tiers et sécurité :</b> les services d'opérateurs, éditeurs ou hébergeurs tiers sont soumis à leurs propres conditions ; le client "
                                  "s'engage à remplacer les mots de passe initiaux et à les renouveler (art. 7 des conditions des contrats de service). "
                                  "<b>Prix :</b> révision selon l'art. 8.2.")
        self.signatures(y + 236)
        assert y + 236 + 64 < H - 40, "contrat abonnement trop long : %d" % (y + 236 + 64)
        self.pied()

    def mandat_sepa(self):
        self.nouvelle_page()
        self.entete(["MANDAT DE PRÉLÈVEMENT", "SEPA"], date=False)
        self.paragraphes(108, 70, [
            "<i>En signant ce formulaire de mandat, vous autorisez CONNECTIS SOLUTIONS à envoyer des instructions à votre banque pour débiter votre compte, et votre banque à "
            "débiter votre compte conformément aux instructions de CONNECTIS SOLUTIONS.</i>",
            "<i>Vous bénéficiez du droit d'être remboursé par votre banque suivant les conditions décrites dans la convention que vous avez passée avec elle. "
            "Une demande de remboursement doit être présentée dans les 8 semaines suivant la date de débit de votre compte pour un prélèvement autorisé.</i>",
        ], taille=7.6)
        self.texte(M, 168, "RÉFÉRENCE UNIQUE DU MANDAT :", 7, "Helvetica-Bold", NAVY)
        self.champ(M + 135, 165, 130, 13, "Référence unique du mandat")
        self.texte(M + 285, 168, "IDENTIFIANT CRÉANCIER SEPA (ICS) :", 7, "Helvetica-Bold", NAVY)
        self.champ(M + 430, 165, CW - 430, 13, "ICS de CONNECTIS SOLUTIONS (à renseigner)")
        larg = (CW - 14) / 2
        self.cadre(192, 118, "DÉBITEUR", w=larg)
        self.ligne(216, [("Votre nom :", None)], x0=M + 8, x1=M + larg - 8)
        self.ligne(238, [("Votre adresse :", None)], x0=M + 8, x1=M + larg - 8)
        self.ligne(260, [("Code postal :", 80), ("Ville :", None)], x0=M + 8, x1=M + larg - 8)
        self.ligne(282, [("Pays :", None)], x0=M + 8, x1=M + larg - 8)
        x = M + larg + 14
        self.cadre(192, 118, "CRÉANCIER", x=x, w=larg)
        for i, l in enumerate(["Nom : CONNECTIS SOLUTIONS", "Adresse : 110 boulevard d'Austrasie", "Code postal : 54000        Ville : NANCY", "Pays : FRANCE"]):
            self.texte(x + 10, 222 + i * 22, l, 8, "Helvetica", NAVY)
        self.cadre(322, 52, x=M, w=CW)
        self.texte(M + 10, 333, "IBAN", 7, "Helvetica-Bold", GREY)
        self.champ(M + 40, 330, 300, 14, "IBAN", taille=9)
        self.texte(M + 10, 353, "BIC", 7, "Helvetica-Bold", GREY)
        self.champ(M + 40, 350, 130, 14, "BIC", taille=9)
        self.texte(M + 360, 333, "Paiement :", 7, "Helvetica-Bold", GREY)
        x2 = self.case_texte(M + 410, 332, "Récurrent / répétitif", cochee=True)
        self.case_texte(M + 410, 350, "Ponctuel")
        self.cadre(390, 64, x=M, w=larg)
        self.texte(M + 10, 401, "À", 7, "Helvetica", NAVY)
        self.champ(M + 20, 399, 100, 12, "Lieu")
        self.texte(M + 130, 401, "Le", 7, "Helvetica", NAVY)
        self.champ(M + 144, 399, 90, 12, "Date")
        self.texte(M + 10, 424, "Signature :", 7, "Helvetica-Bold", NAVY)
        self.paragraphes(392, 60, [
            "<i>Nota : vos droits concernant le présent mandat sont expliqués dans un document que vous pouvez obtenir auprès de votre banque.</i>",
            "<b>Veuillez compléter tous les champs du mandat.</b>",
        ], x=M + larg + 14, w=larg, taille=7.2)
        self.pied()

    def fin(self):
        self.c.showPage()
        self.c.save()


def construire(cible):
    intro, arts, version = cgv_blocs()
    t_cgv = ["CONDITIONS GÉNÉRALES", "DE VENTE"]
    t_srv = ["CONDITIONS GÉNÉRALES DES", "CONTRATS DE SERVICE"]
    v_srv = "Version en vigueur au 3 novembre 2026."
    # le nombre de pages de chaque texte sert aux renvois : on le mesure d'abord sur un document jetable
    sonde = Doc(io.BytesIO())
    sonde.page = 1
    n1 = sonde.colonnes(t_cgv, intro, arts, version)
    n2 = sonde.colonnes(t_srv, conditions_service.INTRO, conditions_service.ARTICLES, v_srv)

    def libelle(debut, n):
        return "page %d" % debut if n == 1 else "pages %d à %d" % (debut, debut + n - 1)

    d = Doc(cible)
    d.renvoi = "CGV : %s ; contrats de service : %s" % (libelle(2, n1), libelle(2 + n1, n2))
    d.bon_de_commande(libelle(2, n1))
    d.colonnes(t_cgv, intro, arts, version)
    d.colonnes(t_srv, conditions_service.INTRO, conditions_service.ARTICLES, v_srv)
    d.contrat_maintenance()
    d.contrat_abonnement()
    d.mandat_sepa()
    d.fin()
    return "CGV %s, services %s, total %d pages" % (libelle(2, n1), libelle(2 + n1, n2), d.page)


if __name__ == "__main__":
    sys.stdout.reconfigure(encoding="utf-8")
    print("écrit :", OUT, "|", construire(OUT))
