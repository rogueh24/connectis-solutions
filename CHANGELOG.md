# Changelog

Format inspiré de [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/). Toutes les dates au
format AAAA-MM-JJ.

## [Non publié]

### À venir
- Import et personnalisation du starter template Blocksy.
- Arborescence complète des pages (Accueil, Nos solutions ×6, À propos, Devis & Contact, Recrutement, mentions légales).
- Formulaires de devis/contact + conformité RGPD (bandeau cookies).

## 2026-09-24

### Ajouté
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
