# Changelog

Format inspiré de [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/). Toutes les dates au
format AAAA-MM-JJ.

## [Non publié]

### À venir
- Import et personnalisation du starter template Blocksy.
- Arborescence complète des pages (Accueil, Nos solutions ×6, À propos, Devis & Contact, Recrutement, mentions légales).
- Formulaires de devis/contact + conformité RGPD (bandeau cookies).

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
