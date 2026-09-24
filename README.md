# Connectis Solutions — Site vitrine

Site vitrine WordPress pour **Connectis Solutions**, solutions IT & télécoms pour professionnels
(vidéosurveillance, téléphonie & VoIP, internet & fibre optique, matériel informatique & télécom,
abonnements, services & maintenance).

- **Domaine :** connectis-solutions.fr
- **Contact :** contact@connectis-solutions.fr
- **Hébergement :** Planet Hoster
- **Stack :** WordPress · thème Blocksy · MySQL

## Structure du dépôt

- [`docs/`](docs) — cahier des charges et dossier de projet commercial (PDF + script de génération).
- [`site-en-construction/`](site-en-construction) — page d'attente statique (« site en construction »),
  à déposer à la racine de l'hébergement en attendant la mise en ligne du site WordPress définitif.

## Déploiement

Le déploiement sur l'hébergement se fait via SFTP (identifiants stockés en secrets GitHub, jamais
en clair dans ce dépôt). Voir le workflow `.github/workflows/`.

## État du projet

- [x] Cahier des charges validé
- [x] Page d'attente en ligne
- [ ] Installation WordPress
- [ ] Thème Blocksy + starter template
- [ ] Extensions (LiteSpeed Cache, SEOPress, SureMail, WP Armour Anti-Spam...)
- [ ] Intégration des contenus
- [ ] Mise en ligne définitive
