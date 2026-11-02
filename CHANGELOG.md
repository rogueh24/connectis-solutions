# Changelog

Format inspiré de [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/). Toutes les dates au
format AAAA-MM-JJ.

## [Non publié]

### Ajouté (2026-11-02) — numéro de téléphone
- **01 87 66 84 69** (lien `tel:+33187668469`) : tuile « Téléphone » en tête de « Nous contacter directement » (Devis & Contact),
  ligne « Téléphone » dans les mentions légales (éditeur du site), champ `telephone` des données structurées « entreprise locale ».
  `connectis-pages.php` passe en version 21.
- **Pied de page** (`connectis-theme-config.php`) : nom, adresse, téléphone et e-mail en clair, puis copyright et liens légaux.
- **Bouton « Appeler »** flottant, discret, sur téléphone et tablette seulement (≤ 999 px, `connectis-branding.php` + `custom.css`) ; masqué
  sur ordinateur et à l'impression. **Rien dans l'en-tête sur ordinateur** : il reste minimaliste (logo, recherche, connexion, menu).

### Ajouté (2026-11-02) — offre Bureautique
- **Nouvelle offre « Bureautique »** (`/nos-solutions/bureautique/`, d'abord publiée sous `/photocopieurs/`, redirigée en 301) : multifonctions et
  copieurs professionnels, gestion documentaire (planche 5 et 7 de la présentation d'entreprise du 31/10). Même modèle que les
  autres offres (cartes, étapes, liens utiles, FAQ), **sans mention d'achat ni de location** : « demande de devis » simplement (pas de bloc
  financement, pas de question « financement » dans la FAQ). Page créée / renommée par le code (`connectis-pages.php`).
- **Accueil** : titre, accroche et « Nos expertises » à quatre métiers (4e carte, quatre colonnes sur grand écran) ; Solutions et Connectis aussi.
- **Menu** : « Bureautique » dans le sous-menu Solutions, après la téléphonie (ordre fixé par le code ; élément de menu = base de données).
- **Formulaire de devis** : choix « Bureautique » (icône dédiée). **SEO** : titre, description et mot-clé de la page, descriptions mises à jour.
- **Textes légaux** : bureautique dans l'activité et l'objet des CGV, art. 10.4 (conditions de fourniture, maintenance, copies relevées au
  compteur), art. 13.4 (documents mémorisés par les appareils). **À faire relire par l'avocat** : les CGV avaient été relues avant cet ajout.

### Modifié (2026-11-02, suite) — Bureautique & impression
- Titre de l'offre « **Bureautique & impression** » (deux mots, comme les autres offres) ; **marques Canon et HP retirées** du site (page,
  accueil, Connectis, SEO). Entrée de menu renommée dans la base (élément 264).

- Visuel de la **téléphonie** : photo de banque d'images d'une personne avec casque-micro (Pexels, choisie par le client), recadrée sur le visage
  (`pos` / `object-position`). Photos précédentes (personne en téléphone, téléphone de conférence) dans l'historique Git ; une vraie photo d'équipe reste à fournir.

### Retiré (2026-11-02) — « on se justifie pour rien »
- Bloc « Une entreprise vérifiable » (identité légale + bouton Annuaire des Entreprises) retiré de l'accueil, Solutions, Connectis et
  Devis ; il ne reste que « Nos engagements ». Mention « Société immatriculée · RCS · SIREN » de la bande d'assurance remplacée par
  « Équipe locale · Basée à Nancy ». Phrases « informations publiques / Kbis sur demande » retirées (FAQ, CGV art. 12, mentions légales).
  L'identité légale reste dans les **mentions légales** (obligatoire) et dans les données structurées (invisibles).

### Ajouté (performance)
- `connectis-cache.php` : réglages LiteSpeed Cache versionnés (cache navigateur 30 jours, minification HTML/CSS/JS, emojis retirés),
  appliqués par l'interface interne de LiteSpeed ; réglages courants visibles dans `/wp-json/connectis/v1/status`.
- Images du site recadrées, compressées et servies en **WebP** (1,18 Mo → 0,76 Mo) ; JPEG conservé pour l'image de partage.
- Purge automatique du cache à chaque déploiement.

- Cache d'objet **Redis** activé (socket NOC, test de connexion réussi), OPcache réglé côté hébergeur (mémoire 256 Mo,
  16 229 fichiers).

### Clôturé
- N° de TVA confirmé ; CGV et mentions légales relues ; extensions inactives supprimées ; « Sign in with Google » désactivé
  (plus aucun script Google Identity sur le site public) ; signalement Chrome déposé ; fiche Google Business Profile créée. Mot de passe d'application « Claude MCP » conservé
  à la demande du client (rotation possible à tout moment, voir ENGINEERING §6).

## [1.1.0] - 2026-09-25 — Finitions, SEO, textes légaux

### Ajouté
- **Textes légaux réécrits** : CGV en 19 articles (financement en location sans citer de loueur, réserve de propriété,
  responsabilité, sous-traitance RGPD), mentions légales en 9 sections (capital social 10 000 €, immatriculation
  14 avril 2026, TVA, hébergeur avec téléphone).
- Barre d'administration réservée aux administrateurs, sans le logo WordPress.
- Description prête pour la fiche Google Business Profile.
- Extension **WordPress Feature API** 0.1.8 (Automattic), activée seule puis contrôlée : site sain, endpoints protégés (401 en anonyme).
- SEO : mots-clés cibles, plan du site sans taxonomies, image de partage (Open Graph / Twitter), fil d'Ariane et services en JSON-LD,
  un seul H1 par page, redirection 301 et noindex des pages en double `-2`.

### Corrigé
- Pages en double « Vidéosurveillance » (ids 32-33) et brouillon « Privacy Policy » mis à la corbeille : 14 pages publiées.
- Formulaire de devis vérifié en conditions réelles ; Search Console connectée via Site Kit.

### Modifié
- Barre du haut (au-dessus du menu) supprimée ; pied de page réduit à « © année Connectis Solutions » + liens légaux
  (identité complète dans les mentions légales et les blocs de confiance).
- « devis gratuit » remplacé par « devis détaillé et solutions de financement » (site, SEO).
- « Nos solutions » développée ; « Et aussi » → « Services complémentaires » ; grilles de 3 cartes en 3 colonnes.
- Blocs de confiance : icône au-dessus du titre, texte aligné à gauche (ordinateur et mobile).
- Hiérarchie des titres (titre de page > titre de section), espace insécable avant « : ; ! ? », hero mobile resserré.
- Fenêtre de connexion et liste déroulante du compte en thème sombre, avec le logo.
- Menu : « Nos solutions » → « Solutions », menu hamburger centré.

## [1.0.0] - 2026-09-25 — Mise en ligne

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
- Menu hamburger centré : logo d'origine cliquable, « Solutions » (sous-menu sans puces), « Connectis »,
  « Recrutement », « Devis & Contact » en bouton pleine largeur.
- **Purge automatique du cache LiteSpeed** après chaque déploiement (empreinte des mu-plugins,
  `connectis-admin.php`) : les visiteurs ne voient plus d'ancienne version.
- Liens « Tableau de bord » vers `/wp-admin/` (l'adresse exacte `index.php` est signalée à tort par Google).

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
