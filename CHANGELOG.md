# Changelog

Format inspiré de [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/). Toutes les dates au
format AAAA-MM-JJ.

## [Non publié]

### À venir
- Configuration SMTP de SureMail (délivrabilité des e-mails de devis) — nécessite les identifiants de la boîte contact@.
- Vraies photos de l'équipe / des chantiers (À propos, accueil).
- Vérification de l'alerte « Site dangereux » Google Safe Browsing sur `wp-admin` (signalement de faux positif à déposer).
- Relecture des CGV et des mentions légales par un professionnel ; capital social et n° de TVA à renseigner.

## 2026-09-25 — Mise en ligne

### Ajouté
- **Site public** : interrupteur `CONNECTIS_MAINTENANCE` (`connectis-maintenance.php`) passé à `false`. Le fichier reste
  déployé (le déploiement n'efface rien côté serveur).
- **Pages en composants** (`connectis-pages.php`, versionné par `CONNECTIS_PAGES_VERSION`) : cartes à icônes animées,
  étapes, bloc financement (leasing/LOA/LLD auprès de partenaires financiers), bloc de confiance (identité légale,
  lien Annuaire des Entreprises, engagements), FAQ, bandeau d'identité en haut de page, apparition au défilement
  (avec filet de sécurité).
- **Pages légales** rédigées (`connectis-legal.php`) : mentions légales, CGV (B2B), confidentialité (RGPD).
- **Formulaires** en une colonne : devis en 3 étapes avec choix du service, consentement RGPD.
- **Référencement** (`connectis-seo.php`) : titre et description par page (métadonnées SEOPress), plan du site,
  données structurées « entreprise locale » (SIREN/SIRET, adresse).
- **Administration et durcissement** (`connectis-admin.php`) : commentaires/pings fermés, comptes non énumérables
  (REST `users` et `?author=`), XML-RPC et flux coupés, en-têtes de sécurité, révisions limitées, édition de
  fichiers désactivée, tableau de bord épuré + mémo, page de connexion à l'image du site.
- **Logo horizontal** (symbole à gauche, nom sur deux lignes à droite) dans l'en-tête ; le menu hamburger garde le
  logo d'origine, cliquable, à la place de l'entrée « Accueil ».
- Menu : ordre fixe (Nos solutions, Connectis, Recrutement, Devis & Contact en bouton).

### Modifié
- Page « À propos » renommée **« Connectis »** (URL `/a-propos/` inchangée).
- Photos À propos / Recrutement : postes de travail, sans personnes (crédibilité).
- Images versionnées (`?v=`) pour contourner le cache navigateur.

### Corrigé
- Doublons de pages issus des premières exécutions du contenu de départ (10 mis à la corbeille, 2 restent à trier).
- Texte d'introduction collé à gauche sur les pages d'offres (règle CSS) ; cartes orphelines ; titres non centrés.
- Sélecteurs CSS du menu adaptés au balisage réel de Blocksy (`<ul>` sans classe `.menu`).

## 2026-09-24

### Ajouté (session du soir)
- **SEOPress** (`wp-seopress` 10.2, le vrai) et **MCP Adapter** 0.6.1 : les 8 extensions demandées sont actives.
- **Contact Form 7** actif ; mu-plugin `connectis-forms.php` (auto-réparateur) : formulaires devis / contact /
  candidature créés et branchés dans les pages « Devis & Contact » et « Recrutement ».
- Connexion **API/MCP WordPress** au site (alias `connectis`).
- **Déploiement incrémental** (`lftp mirror`) : ≈ 2 min au lieu de 20 à 28 min.
- Page en construction : fond animé, carte en verre dépoli, **logo complet d'origine** (PNG transparent),
  responsive (vérifié en 1440×900, 390×844 et 320×568), favicon carré.
- Documentation d'ingénierie réécrite (leçons apprises, procédure d'ajout d'extension, accès).

- **Thème sombre sur tout le site**, dans l'esprit de la page en construction : fond marine à halos animés
  et grille technique, cartes/encadrés en verre dépoli, liens cyan, formulaires sombres, pied de page marine ;
  en-tête sombre avec le logo complet, recherche + connexion + **menu hamburger sur ordinateur comme sur mobile**.
- Menus assignés à Blocksy (principal + mobile, pied de page) avec les 6 sous-pages sous « Nos solutions » ;
  logo et favicon importés en médiathèque ; français (dates, fuseau Europe/Paris, slogan).
- **Mode aperçu** protégé par jeton pour voir le vrai site pendant la construction.

### Modifié (positionnement)
- Message central recentré sur **informatique, vidéosurveillance et téléphonie** ; « sécurisation / sécurité » retiré
  de la page en construction, de l'accueil, de « Nos solutions », de « À propos » et du slogan du site.
- Accueil et « Nos solutions » : trois grandes cartes avec photo (Informatique, Vidéosurveillance, Téléphonie),
  puis « Et aussi » (fibre, abonnements, services). Page « Matériel informatique & télécom » renommée
  « Informatique & matériel » (URL inchangée), placée en premier dans le menu.

### Corrigé (session du soir)
- Menu affiché par ordre alphabétique : emplacements de menu enregistrés pour l'ancien thème, réassignés par l'API.
- **Site en panne (HTTP 500)** : extension `seopress` installée par erreur (mauvais slug, vieux plugin
  ThemeKraft multisite), remplacée par `wp-seopress` et supprimée du serveur via l'API.
- **`.gitignore`** excluait `vendor/` et `*.sql` des extensions (Contact Form 7 inactivable) : règles ancrées à la racine.
- **Logo décentré** : la cause réelle était un badge inline à côté de l'image ; badge désormais en bloc centré.
- Endpoint de diagnostic mis en cache par LiteSpeed : `nocache_headers()`.

### Ajouté (journée)
- Cahier des charges et dossier de projet commercial (PDF + script de génération) dans `docs/`.
- Dépôt GitHub public [`rogueh24/connectis-solutions`](https://github.com/rogueh24/connectis-solutions).
- Pipeline de déploiement SFTP via GitHub Actions (secrets chiffrés, aucun identifiant en clair).
- Page d'attente statique « site en construction » (première version, remplacée depuis).
- Hébergement Planet Hoster + WordPress installés sur `connectis-solutions.fr`.
- Thème Blocksy + Blocksy Companion.
- Extensions : LiteSpeed Cache, SEOPress, SureMail, WP Armour (Honeypot Anti Spam), Abilities API
  (successeur de « WordPress Feature API »).
- Page « site en construction » v2 : mu-plugin WordPress (`wp-content/mu-plugins/connectis-maintenance.php`),
  visible par les visiteurs non connectés uniquement, logo Connectis recadré et centré.
- Documentation d'ingénierie ([`docs/ENGINEERING.md`](docs/ENGINEERING.md)) et suivi de projet (ce fichier).

### Corrigé
- Chemin SFTP absolu (`/public_html`) remplacé par un chemin relatif (`public_html`) — l'ancien
  chemin sortait du chroot du compte et provoquait un `Permission denied`.
- Syntaxe YAML du workflow de déploiement (`:` non échappé dans le nom d'une étape, cassait
  l'enregistrement du workflow côté GitHub Actions).

### Modifié
- Logo recadré sur l'icône seule (sans le texte « Connectis Solutions », illisible en petit format)
  pour un rendu centré et net dans le badge circulaire de la page d'attente.
