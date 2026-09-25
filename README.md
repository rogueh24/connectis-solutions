# Connectis Solutions — Site vitrine

Site vitrine WordPress pour **Connectis Solutions**, solutions IT & télécoms pour professionnels :
vidéosurveillance, téléphonie & VoIP, internet & fibre optique, matériel informatique & télécom,
abonnements, services & maintenance.

| | |
|---|---|
| **Domaine** | [connectis-solutions.fr](https://connectis-solutions.fr) — **en ligne depuis le 2026-09-25** |
| **Contact** | contact@connectis-solutions.fr |
| **Hébergement** | Planet Hoster |
| **Stack** | WordPress · thème [Blocksy](https://www.blocksy.wordpress.com) + Companion · MySQL |
| **Cahier des charges** | [docs/Connectis_Solutions_Cahier_des_Charges_et_Projet_Commercial.pdf](docs/Connectis_Solutions_Cahier_des_Charges_et_Projet_Commercial.pdf) |

## Structure du dépôt

```
.
├── docs/                     Cahier des charges + dossier de projet commercial (PDF, script de génération)
├── site-en-construction/     Ancienne page d'attente statique (archive — remplacée par le mu-plugin WordPress)
├── wp-content/                Tout ce qui est versionné côté WordPress (voir ENGINEERING.md)
│   ├── themes/blocksy/        Thème principal
│   ├── plugins/                Extensions installées (cf. tableau ci-dessous)
│   └── mu-plugins/             Contenu, SEO, administration, identité visuelle, formulaires, interrupteur de mise en ligne (voir ENGINEERING §9)
└── .github/workflows/         Pipeline de déploiement SFTP
```

> Le cœur de WordPress (`wp-admin`, `wp-includes`, `index.php`, `wp-config.php`) n'est **pas** versionné
> ici : il vit uniquement sur le serveur, installé via le panneau d'hébergement. Voir
> [docs/ENGINEERING.md](docs/ENGINEERING.md) pour le détail de cette convention et des raisons.

## Extensions installées

| Extension | Rôle |
|---|---|
| Blocksy Companion | Starter templates & fonctionnalités du thème Blocksy |
| LiteSpeed Cache | Cache & performance (compatible Planet Hoster / LiteSpeed) |
| SEOPress (`wp-seopress`) | Référencement (SEO) |
| SureMail | Fiabilité de l'envoi d'e-mails (formulaires, notifications) |
| WP Armour — Honeypot Anti Spam | Anti-spam sur les formulaires |
| Contact Form 7 | Formulaires de devis, contact et candidature (avec dépôt de CV) |
| MCP Adapter | Expose les *abilities* WordPress comme outils MCP (pilotage par un agent IA, authentifié) |
| Abilities API | Successeur maintenu de « WordPress Feature API », expose les fonctionnalités du site pour un pilotage structuré (base d'une future intégration MCP) |
| WordPress Feature API | Version 0.1.8 d'Automattic (installée à la demande) : expose les fonctionnalités du site aux agents IA via l'API REST, authentification requise (`/wp/v2/features` → 401 en anonyme). Le projet annonce son remplacement par l'Abilities API ci-dessus. |

## Déploiement

Le déploiement se fait via **GitHub Actions → SFTP**, sans qu'aucun identifiant ne transite en clair :
les secrets (`SFTP_SERVER`, `SFTP_USERNAME`, `SFTP_PASSWORD`, `SFTP_PORT`, `SFTP_REMOTE_PATH`) sont
stockés côté GitHub (*Settings → Secrets and variables → Actions*).

- Tout push sur `main` touchant `wp-content/**` déclenche `.github/workflows/deploy-wp-content.yml`
  (synchronisation **incrémentale** via `lftp mirror` : seuls les fichiers dont la taille change sont envoyés).
- Déclenchement manuel possible : `gh workflow run deploy-wp-content.yml`.

Détails complets, conventions et leçons apprises : **[docs/ENGINEERING.md](docs/ENGINEERING.md)**.

## Suivi de projet

Historique daté des changements : **[CHANGELOG.md](CHANGELOG.md)**.

### Ce qui est livré (v1.1.0)

- **Site public** en thème sombre aux couleurs du logo : 14 pages (accueil, Solutions + 6 offres, Connectis, Devis & Contact,
  Recrutement, mentions légales, CGV, confidentialité), en-tête avec logo horizontal, menu hamburger centré (logo cliquable,
  bouton « Devis & Contact »), pied de page réduit aux liens légaux, animations douces avec filet de sécurité.
- **Message** : informatique, vidéosurveillance et téléphonie pour les professionnels ; devis détaillé et solutions de
  financement en location auprès de partenaires financiers (sans citer de loueur).
- **Formulaires** (devis en 3 étapes, contact, candidature avec CV), consentement RGPD, anti-spam WP Armour,
  e-mails via SureMail — **devis testé en conditions réelles**.
- **Confiance** : identité légale vérifiable (SIREN, SIRET, RCS, capital, TVA) et lien vers l'Annuaire des Entreprises ;
  CGV (19 articles) et mentions légales (9 sections) rédigées.
- **Référencement** : titre, description et mot-clé cible par page, plan du site (`/sitemaps.xml`), image de partage,
  données structurées (entreprise locale, fil d'Ariane, services), un seul H1 par page. Google Search Console connectée
  via Site Kit.
- **Administration** : commentaires fermés, comptes non énumérables, en-têtes de sécurité, barre d'administration
  réservée aux administrateurs, tableau de bord épuré, page de connexion à l'image du site, purge automatique du cache
  après chaque déploiement.
- **Ingénierie** : contenu, SEO et réglages **versionnés dans Git** (mu-plugins), déploiement incrémental
  (≈ 2 min), interrupteur de mise en ligne, endpoint de diagnostic `/wp-json/connectis/v1/status`.

### État d'avancement

- [x] Cahier des charges validé
- [x] Dépôt GitHub + pipeline de déploiement SFTP incrémental
- [x] Hébergement + WordPress installés (Planet Hoster) ; thème Blocksy et extensions actifs
- [x] Charte graphique, pages, formulaires, textes légaux, SEO, durcissement
- [x] **Mise en ligne** (2026-09-25) — v1.0.0, puis v1.1.0
- [x] Google Search Console (Site Kit) ; plan du site envoyé
- [x] Formulaire de devis vérifié (SureMail)
- [ ] Alerte « Site dangereux » de Chrome sur `wp-admin` : signalement de faux positif déposé, en attente de Google
- [ ] Fiche Google Business Profile (description prête, à créer)
- [ ] Relecture des CGV et mentions légales par un avocat ; confirmation du n° de TVA `FR64 103 370 557`
- [ ] Vraies photos de l'équipe et des chantiers ; numéro de téléphone de l'entreprise
- [ ] Suppression des extensions inactives de l'hébergeur (Akismet, BeyondSEO, Extendify, MonsterInsights, Hello Dolly, Really Simple Security, Site Assistant)
- [ ] Peaufinages au fil des demandes
