# Connectis Solutions — Site vitrine

Site vitrine WordPress pour **Connectis Solutions**, solutions IT & télécoms pour professionnels :
vidéosurveillance, téléphonie & VoIP, internet & fibre optique, matériel informatique & télécom,
abonnements, services & maintenance.

| | |
|---|---|
| **Domaine** | [connectis-solutions.fr](https://connectis-solutions.fr) |
| **Contact** | contact@connectis-solutions.fr |
| **Hébergement** | Planet Hoster (N0C — node240-eu.n0c.com) |
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
│   └── mu-plugins/             Page « site en construction », toujours active, invisible aux admins connectés
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
| SEOPress | Référencement local |
| SureMail | Fiabilité de l'envoi d'e-mails (formulaires, notifications) |
| WP Armour — Honeypot Anti Spam | Anti-spam sur les formulaires |
| Abilities API | Successeur maintenu de « WordPress Feature API », expose les fonctionnalités du site pour un pilotage structuré (base d'une future intégration MCP) |

## Déploiement

Le déploiement se fait via **GitHub Actions → SFTP**, sans qu'aucun identifiant ne transite en clair :
les secrets (`SFTP_SERVER`, `SFTP_USERNAME`, `SFTP_PASSWORD`, `SFTP_PORT`, `SFTP_REMOTE_PATH`) sont
stockés côté GitHub (*Settings → Secrets and variables → Actions*).

- Tout push sur `main` touchant `wp-content/**` déclenche `.github/workflows/deploy-wp-content.yml`.
- Déclenchement manuel possible : `gh workflow run deploy-wp-content.yml`.

Détails complets, conventions et leçons apprises : **[docs/ENGINEERING.md](docs/ENGINEERING.md)**.

## Suivi de projet

Historique daté des changements : **[CHANGELOG.md](CHANGELOG.md)**.

### État d'avancement

- [x] Cahier des charges validé
- [x] Dépôt GitHub + pipeline de déploiement SFTP
- [x] Hébergement + WordPress installés (Planet Hoster)
- [x] Thème Blocksy + extensions déployés
- [x] Page « site en construction » en ligne (mu-plugin, logo Connectis)
- [ ] Starter template Blocksy importé et personnalisé (couleurs, sections)
- [ ] Arborescence de pages créée (Accueil, Nos solutions ×6, À propos, Devis & Contact, Recrutement, mentions légales)
- [ ] Contenus intégrés (textes, photos, logos partenaires)
- [ ] Formulaires de devis / contact + RGPD (bandeau cookies)
- [ ] Recette & mise en ligne définitive
