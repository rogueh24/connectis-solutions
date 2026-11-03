# -*- coding: utf-8 -*-
"""Texte des « Conditions générales des contrats de service » de CONNECTIS SOLUTIONS (maintenance impression et abonnements).

Complète les CGV du site (connectis-legal.php), dont elles reprennent les renvois d'articles. Les chiffres commerciaux (durée,
indemnité, délai d'intervention, volume) sont des champs du recto des contrats, pas du texte imposé.
"""

INTRO = ("Les présentes conditions complètent les conditions générales de vente (les « CGV ») de <b>CONNECTIS SOLUTIONS</b> (le « Prestataire ») "
         "pour les <b>contrats de service de maintenance et d'impression</b> et les <b>contrats de service et d'abonnement</b> (le « Contrat »). "
         "En cas de contradiction, s'appliquent dans l'ordre : le recto du Contrat, les présentes conditions, puis les CGV.")

ARTICLES = [
    ("Article 1 — Objet", [
        "<b>1.1</b> Le Prestataire assure, les jours ouvrés et pendant ses heures de travail, par ses techniciens ou ses sous-traitants, l'entretien, le dépannage "
        "et l'assistance des matériels désignés au recto, ou fournit les services récurrents (internet, téléphonie, vidéosurveillance, supervision, "
        "forfaits et services associés) qui y sont listés.",
        "<b>1.2</b> Le Client reconnaît avoir défini ses besoins avec le Prestataire, avoir été informé des caractéristiques et des conditions d'utilisation des "
        "matériels et services, et que ceux-ci correspondent à son activité professionnelle.",
    ]),
    ("Article 2 — Souscription et date d'effet", [
        "<b>2.1</b> Le Contrat est formé à la réception du recto daté et signé par le Client et accepté par le Prestataire. Une proposition non retournée signée "
        "dans les quinze (15) jours de sa date n'engage plus le Prestataire. Le Client fournit sur demande les documents établissant l'identité de la société et "
        "la qualité du signataire et, en cas de prélèvement, un relevé d'identité bancaire.",
        "<b>2.2</b> Le Contrat prend effet à la date indiquée au recto ou, à défaut, à la livraison ou à la mise en service, attestée par le bon d'intervention ou "
        "de livraison. Les éventuels frais de mise en service ou de gestion sont indiqués au recto.",
    ]),
    ("Article 3 — Engagements du Prestataire", [
        "<b>3.1</b> Le Prestataire met en œuvre les moyens nécessaires au bon fonctionnement des matériels et services (obligation de moyens) et intervient dans le "
        "<b>délai indiqué au recto</b>, à compter de la réception de la demande complète du Client par les canaux convenus.",
        "<b>3.2</b> La maintenance comprend, selon le recto, la main-d'œuvre, le déplacement et les pièces nécessaires à la remise en état de fonctionnement, ainsi "
        "qu'une maintenance préventive effectuée selon les préconisations du constructeur. Les consommables (toner, encre) ne sont fournis que si le recto le "
        "mentionne ; le papier, les supports spéciaux et les agrafes ne sont jamais compris.",
    ]),
    ("Article 4 — Engagements du Client", [
        "<b>4.1</b> Le Client fournit des informations exactes et complètes, utilise les matériels et services conformément à leur notice, assure une alimentation "
        "électrique et un accès au réseau adaptés, donne au personnel du Prestataire un accès libre aux matériels, et désigne un interlocuteur chargé de leur "
        "bonne utilisation.",
        "<b>4.2</b> Il s'interdit toute intervention d'un tiers non agréé par le Prestataire sur les matériels objets du Contrat, l'emploi de pièces ou de "
        "consommables non conformes ou non fournis par le Prestataire lorsque le recto les prévoit, et le déplacement des matériels hors des locaux désignés "
        "sans accord écrit préalable. Il effectue ses sauvegardes (CGV, art. 6.4).",
    ]),
    ("Article 5 — Interventions non comprises", [
        "<b>5.1</b> Ne sont pas comprises dans le Contrat, et sont facturées en sus au tarif en vigueur, les interventions rendues nécessaires par : la foudre, une "
        "surtension, un dégât des eaux, un sinistre ou tout événement extérieur aux matériels ; un choc, une chute, un produit d'entretien inadapté ou un usage non "
        "conforme à la notice ; l'intervention d'un tiers non agréé ; des pièces ou consommables non conformes ; une alimentation électrique ou un réseau "
        "inadaptés ; la connexion de matériels incompatibles ; les logiciels, systèmes et données du Client ; une modification de l'environnement du Client non "
        "validée par le Prestataire ; ou une période de suspension du Contrat pour impayé.",
    ]),
    ("Article 6 — Impression : compteurs et facturation", [
        "<b>6.1</b> Les copies et impressions sont comptées par le compteur de l'appareil. Le Client communique le relevé de ses compteurs à chaque fin de période de "
        "facturation, dans les cinq (5) jours de la demande, ou autorise le relevé à distance lorsque l'appareil le permet.",
        "<b>6.2</b> À défaut de relevé, le Prestataire facture sur une base estimée (dernier relevé connu ou moyenne des consommations précédentes), "
        "régularisée au relevé suivant. Un déplacement rendu nécessaire par l'absence de relevé est facturé au tarif en vigueur.",
        "<b>6.3</b> Le forfait mensuel inclut le volume de copies indiqué au recto, facturé d'avance ; le volume non utilisé n'est ni reporté ni remboursé. Les "
        "copies au-delà du forfait sont facturées au coût copie du recto. Le coût copie est établi sur un taux de couverture moyen de 5 % (norme constructeur) ; "
        "une consommation de toner supérieure donne lieu à une facturation complémentaire.",
        "<b>6.4</b> Les éléments dont la mise à disposition est prévue au recto restent la propriété du Prestataire et lui sont restitués en fin de Contrat, "
        "faute de quoi ils sont facturés à leur valeur au tarif en vigueur.",
    ]),
    ("Article 7 — Services récurrents, sécurité et usage", [
        "<b>7.1</b> Le Client utilise les services de manière licite et raisonnable. Sont notamment interdits la revente, l'utilisation comme passerelle de "
        "réacheminement, l'envoi en masse de messages ou d'appels automatisés et toute utilisation frauduleuse ; le Prestataire peut suspendre le service en cas "
        "d'usage abusif, après information du Client. Les numéros à tarification spéciale et les appels hors forfait sont facturés selon les conditions du recto.",
        "<b>7.2</b> <b>Sécurité des accès.</b> Dès la mise en service, le Client remplace les mots de passe initiaux (messagerie vocale, administration du standard, "
        "accès à distance, caméras et enregistreurs), les garde secrets et les renouvelle régulièrement. Le Prestataire n'est pas responsable des fraudes, "
        "piratages et consommations anormales qui résultent du non-respect de ces mesures, sauf faute du Prestataire.",
        "<b>7.3</b> Lorsque le Client est titulaire de lignes ou d'abonnements auprès d'un opérateur, il donne mandat au Prestataire pour effectuer en son nom les "
        "démarches nécessaires (portabilité, résiliation, activation). Les services fournis par des opérateurs ou éditeurs tiers sont soumis à leurs conditions "
        "propres (CGV, art. 10.2).",
    ]),
    ("Article 8 — Prix, facturation et paiement", [
        "<b>8.1</b> Les redevances sont facturées d'avance selon la périodicité du recto ; les première et dernière périodes sont facturées au prorata. Sauf autre "
        "délai au recto, elles sont payables par prélèvement SEPA à la date indiquée (le Client signe alors le mandat joint) ou par virement à trente (30) jours. "
        "Tout retard de paiement est régi par l'article 5.2 des CGV.",
        "<b>8.2</b> Les prix peuvent être révisés à chaque date anniversaire pour tenir compte de l'évolution des coûts de fournitures, de main-d'œuvre et des "
        "tarifs des opérateurs et éditeurs tiers, par notification écrite au moins trente (30) jours avant. Si la hausse excède dix pour cent (10 %), le Client peut "
        "résilier sans indemnité dans le mois qui suit la notification.",
    ]),
    ("Article 9 — Durée et renouvellement", [
        "<b>9.1</b> Le Contrat est conclu pour la durée initiale indiquée au recto. Lorsque l'équipement est financé par un contrat de location, cette durée peut "
        "être alignée sur celle du financement, ce que le recto précise.",
        "<b>9.2</b> À l'issue de la durée initiale, le Contrat se renouvelle par périodes successives d'un (1) an, sauf résiliation notifiée par lettre "
        "recommandée avec accusé de réception au moins trois (3) mois avant l'échéance (CGV, art. 10.1).",
    ]),
    ("Article 10 — Suspension, résiliation et indemnité", [
        "<b>10.1</b> En cas de manquement du Client, notamment de non-paiement, restant sans effet quinze (15) jours après mise en demeure, le Prestataire peut "
        "suspendre ses prestations, puis résilier le Contrat dans les conditions de l'article 17 des CGV. Chaque partie peut aussi résilier de plein droit en cas "
        "de force majeure de plus de soixante (60) jours (CGV, art. 16).",
        "<b>10.2</b> Si le Client met fin au Contrat avant son terme, hors manquement du Prestataire, il verse une <b>indemnité de résiliation anticipée</b> égale au "
        "pourcentage, indiqué au recto, des redevances restant à courir jusqu'au terme ; à défaut d'indication, à <b>la moitié</b> de ces redevances. Elle "
        "indemnise l'immobilisation de moyens et de stocks par le Prestataire ; le juge conserve son pouvoir de modération.",
    ]),
    ("Article 11 — Fin du Contrat et restitution", [
        "<b>11.1</b> À la fin du Contrat, le Client laisse le Prestataire relever les compteurs, reprendre les éléments restituables et les consommables non "
        "utilisés qui lui appartiennent. Les matériels financés par un tiers sont restitués selon le contrat de financement ; les frais de dépose et de transport, "
        "indiqués au devis, sont à la charge du Client. Le Client fait effacer les données mémorisées avant toute reprise (CGV, art. 13.4).",
    ]),
    ("Article 12 — Responsabilité, cession et litiges", [
        "<b>12.1</b> La responsabilité du Prestataire est régie par l'article 15 des CGV (limitation au montant payé) ; il ne répond ni des interruptions des réseaux "
        "d'énergie ou de télécommunications, ni du fonctionnement des logiciels de tiers. Pour les données personnelles, l'article 13.3 des CGV s'applique.",
        "<b>12.2</b> Le Client ne peut céder le Contrat sans accord écrit. Le Prestataire peut le céder à une entreprise offrant les mêmes garanties, après "
        "information écrite du Client. À défaut d'accord amiable, le tribunal de commerce de Nancy est seul compétent (CGV, art. 19.2).",
    ]),
]
