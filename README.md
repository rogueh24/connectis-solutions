# Connectis Solutions — Site vitrine

Site vitrine WordPress pour **Connectis Solutions**, solutions IT & télécoms pour professionnels :
vidéosurveillance, téléphonie & VoIP, internet & fibre optique, matériel informatique & télécom,
abonnements, services & maintenance.

| | |
|---|---|
| **Domaine** | [connectis-solutions.fr](https://connectis-solutions.fr) |
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

### État d'avancement

- [x] Cahier des charges validé
- [x] Dépôt GitHub + pipeline de déploiement SFTP
- [x] Hébergement + WordPress installés (Planet Hoster)
- [x] Thème Blocksy + extensions déployés
- [x] Page « site en construction » en ligne (mu-plugin, logo complet, responsive)
- [x] Les 8 extensions demandées actives (LiteSpeed, SEOPress, SureMail, WP Armour, Abilities API, MCP Adapter, Contact Form 7, Blocksy Companion)
- [x] 14 pages + 3 formulaires créés ; connexion API/MCP WordPress opérationnelle
- [x] Déploiement incrémental (≈ 2 min au lieu de 20+)
- [ ] Starter template Blocksy importé et personnalisé (couleurs, sections)
- [x] Arborescence de pages créée (Accueil, Nos solutions ×6, À propos, Devis & Contact, Recrutement, mentions légales)
- [ ] Contenus intégrés (textes, photos, logos partenaires)
- [x] Formulaires de devis / contact / candidature (Contact Form 7)
- [ ] Configuration d'envoi des e-mails (SureMail) et bandeau cookies RGPD
- [x] Charte graphique du site public (thème sombre, en-tête + menu hamburger, boutons, pied de page) aux couleurs du logo
- [ ] Relecture juridique (mentions légales : hébergeur ; CGV ; politique de confidentialité)
- [ ] Contenus réels (numéro de téléphone, photos de l'équipe, logos partenaires, références)
- [ ] Mise en ligne : retirer le mode construction (voir docs/ENGINEERING.md §7)
- [ ] Recette & mise en ligne définitive
