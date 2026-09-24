# Conventions d'ingénierie — Connectis Solutions

Règles de fonctionnement du dépôt `connectis-solutions`. Objectif : que n'importe qui (humain ou
agent) reprenne le projet sans avoir à redécouvrir les mêmes pièges.

## 1. Ce qui est versionné, et ce qui ne l'est pas

WordPress se sépare en deux mondes, et ce dépôt ne suit que le second :

| | Versionné ici ? | Où ça vit |
|---|---|---|
| Cœur WordPress (`wp-admin/`, `wp-includes/`, `index.php`, `wp-config.php`) | **Non** | Serveur uniquement, installé via le panneau d'hébergement (Softaculous/NOC WP) |
| `wp-content/uploads/` (médias) | **Non** (`.gitignore`) | Serveur uniquement — trop volumineux, change en permanence |
| `wp-content/themes/`, `wp-content/plugins/`, `wp-content/mu-plugins/` | **Oui** | Ce dépôt → déployé sur le serveur |

Raison : le cœur WordPress et les uploads n'ont rien à faire dans un historique Git (gros volume,
zéro valeur de diff, régénérable à volonté). Ce qui a de la valeur à versionner, c'est ce qu'on a
choisi d'installer et personnalisé : thème, extensions, mu-plugins.

## 2. Déploiement

Pipeline : `.github/workflows/deploy-wp-content.yml`.

- **Déclencheur :** push sur `main` touchant `wp-content/**`, ou manuel (`gh workflow run deploy-wp-content.yml`).
- **Mécanisme :** [`wlixcc/SFTP-Deploy-Action`](https://github.com/wlixcc/SFTP-Deploy-Action) envoie
  le contenu de `wp-content/*` (local) vers `<SFTP_REMOTE_PATH>/wp-content` (serveur). C'est un envoi
  additif : les fichiers existants côté serveur (uploads, cache, etc.) ne sont **pas** supprimés
  (`delete_remote_files: false`).
- **Secrets** (GitHub → *Settings → Secrets and variables → Actions*) : `SFTP_SERVER`, `SFTP_USERNAME`,
  `SFTP_PASSWORD`, `SFTP_PORT`, `SFTP_REMOTE_PATH` (= `public_html`, chemin **relatif** au dossier
  personnel du compte SFTP — un `/` en tête sort du chroot et fait échouer le déploiement avec
  `Permission denied`).
- **Aucun identifiant ne doit jamais apparaître en clair** dans ce dépôt, un commit, ou un fichier de
  config. Le mot de passe d'hébergement n'est saisi que par un humain, jamais par un agent.

## 3. Ajouter une extension ou un thème

1. Vérifier le slug exact sur WordPress.org (`https://api.wordpress.org/plugins/info/1.2/?action=query_plugins&request[search]=<nom>`)
   ou sur le dépôt GitHub du projet s'il n'est pas dans l'annuaire officiel.
2. Télécharger le zip officiel (`https://downloads.wordpress.org/plugin/<slug>.zip` ou release GitHub).
3. Décompresser dans `wp-content/plugins/<slug>/` (ou `wp-content/themes/<slug>/` pour un thème).
4. Commit + push sur `main` → déploiement automatique.
5. Activer le plugin/thème depuis l'admin WordPress (l'activation n'est pas versionnable, c'est un
   état de la base de données).

## 4. Convention de commit

Message court à l'impératif, en français, décrivant le *pourquoi* plus que le *quoi* :

```
Corrige la syntaxe YAML du workflow (deux-points non échappé dans le nom de l'étape)
```

Les commits assistés par un agent Claude portent `Co-Authored-By: Claude Sonnet 5 <noreply@anthropic.com>`.

## 5. Leçons apprises (post-mortems courts)

- **YAML — toujours quoter une valeur de `name:` contenant `:`.**
  `- name: Déploiement SFTP (wp-content : thème...)` casse le parseur (« mapping values are not
  allowed here ») car le second `:` est lu comme un nouveau séparateur clé/valeur. GitHub échoue
  alors silencieusement à enregistrer le workflow (nom affiché = chemin du fichier au lieu du champ
  `name:`, `workflow_dispatch` inutilisable) sans message d'erreur exploitable dans les runs.
  **Règle :** toute valeur de `name:` (workflow ou step) contenant `:`, `#`, ou commençant par un
  caractère spécial doit être entre guillemets.
- **SFTP — chemin relatif, pas absolu.** `SFTP_REMOTE_PATH` doit être relatif au home du compte SFTP
  (`public_html`), pas préfixé par `/` (`/public_html` sort du chroot → `Permission denied`).
- **Installeur WordPress — dossier cible vide requis.** L'installeur NOC WP refuse d'installer dans
  un répertoire non vide. Si une page d'attente statique y est déjà déployée, la retirer avant
  l'installation (elle est de toute façon remplacée par le mu-plugin `wp-content/mu-plugins/connectis-maintenance.php`).

## 6. Activation des thèmes/extensions

Déposer les fichiers d'un thème ou d'une extension via le pipeline SFTP ne l'active **pas** —
l'activation est un état stocké en base de données (`active_plugins`, `stylesheet`), pas un fichier.
`wp-content/mu-plugins/connectis-activate.php` s'en charge automatiquement au premier chargement du
site après un déploiement (idempotent, `activate_plugin()` / `switch_theme()`).

## 7. Déploiement — limite connue

Le pipeline actuel réenvoie l'intégralité de `wp-content/*` à chaque exécution (~20 minutes, même
pour un changement d'un seul fichier), car `SFTP-Deploy-Action` n'est pas incrémental sur ce mode
d'utilisation. Pour un projet qui grossit, envisager une synchronisation différentielle (rsync via
SSH, ou une action GitHub dédiée au diff Git) plutôt que d'optimiser prématurément maintenant.

## 8. Accès & sécurité

- Aucun agent IA n'entre de mot de passe dans un formulaire, un terminal SSH, ou un fichier de
  configuration — quelle que soit la demande. L'authentification initiale (hébergement, WordPress,
  GitHub) reste un geste humain.
- Le connecteur MCP WordPress disponible dans cet environnement pointe vers un **autre** site
  (rogueh24.fr) : ne jamais l'utiliser sur ce projet tant qu'il n'a pas été explicitement reconfiguré
  pour connectis-solutions.fr.
