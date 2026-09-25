# Conventions d'ingénierie — Connectis Solutions

Règles de fonctionnement du dépôt `connectis-solutions`. Objectif : que n'importe qui (humain ou
agent) reprenne le projet sans redécouvrir les mêmes pièges.

## 1. Ce qui est versionné, et ce qui ne l'est pas

| | Versionné ici ? | Où ça vit |
|---|---|---|
| Cœur WordPress (`wp-admin/`, `wp-includes/`, `index.php`, `wp-config.php`) | **Non** | Serveur uniquement (installé via le panneau NOC WP) |
| `wp-content/uploads/` (médias) | **Non** | Serveur uniquement |
| `wp-content/themes/`, `plugins/`, `mu-plugins/` | **Oui** | Ce dépôt → déployé sur le serveur |
| Contenu des pages, SEO, réglages | **Oui, en code** (mu-plugins, §9) | Réappliqué à chaque incrément de version (`CONNECTIS_PAGES_VERSION`, `CONNECTIS_SEO_VERSION`, …) : une modification manuelle dans l'éditeur est écrasée |
| Médias, menus, comptes, entrées de formulaires | **Non** (base de données / uploads) | Serveur. Le mu-plugin `connectis-content-seed.php` n'est qu'un amorçage initial historique |

## 2. Déploiement

Pipeline : `.github/workflows/deploy-wp-content.yml`.

- **Déclencheur :** push sur `main` touchant `wp-content/**`, ou manuel (`gh workflow run deploy-wp-content.yml`).
- **Mécanisme :** `lftp mirror --reverse --ignore-time` (SFTP). Compare **la taille** des fichiers et ne
  transfère que ce qui diffère (≈ 2 min au total). `--ignore-time` est indispensable : un checkout Git
  donne à tous les fichiers un mtime « maintenant ». **Aucune suppression distante** (pas de `--delete`) :
  supprimer un fichier du dépôt ne le supprime pas du serveur → utiliser l'API WordPress
  (`plugins/delete`) ou le File Manager du panneau.
- **Limite connue :** la comparaison par taille manque une modification qui conserverait exactement la
  même taille. En cas de doute, supprimer le fichier côté serveur puis redéployer.
- **Secrets** (GitHub → *Settings → Secrets and variables → Actions*) : `SFTP_SERVER`, `SFTP_USERNAME`,
  `SFTP_PASSWORD`, `SFTP_PORT`, `SFTP_REMOTE_PATH` (= `public_html`, chemin **relatif** au home du compte).
  Un mot de passe contenant une virgule casserait la syntaxe `open -u user,pass` de lftp.
- **Aucun identifiant en clair** dans le dépôt, un commit ou un fichier de config. Le dépôt est **public**.

## 3. Ajouter une extension ou un thème

1. **Vérifier l'identité, pas seulement l'existence.** Un HTTP 200 sur
   `downloads.wordpress.org/plugin/<slug>.zip` ne prouve rien : le slug `seopress` est un vieux plugin
   ThemeKraft abandonné, le vrai SEOPress est `wp-seopress`. Contrôler `name`, `author` et
   `active_installs` via `https://api.wordpress.org/plugins/info/1.2/?action=plugin_information&slug=<slug>`
   ou la page GitHub officielle.
2. Télécharger le zip officiel (wordpress.org ou *release* GitHub), décompresser dans
   `wp-content/plugins/<slug>/`.
3. **Vérifier que rien n'est ignoré** : `git status --ignored wp-content/plugins/<slug>` — les extensions
   embarquent leur propre `vendor/` (autoload PHP) qui **doit** être déployé.
4. Commit + push → déploiement.
5. **Activer par l'API** (`plugins/activate`, une extension à la fois) puis vérifier la santé (`/`,
   `/wp-json/…`, `/wp-json/connectis/v1/status?t=<timestamp>`). Une extension défectueuse peut provoquer
   un fatal sur tous les hooks : ne jamais en activer plusieurs d'un coup.

## 4. Convention de commit

Message court à l'impératif, en français, décrivant le *pourquoi* ; **un commit par sujet**.
Les commits assistés par un agent portent `Co-Authored-By: Claude Sonnet 5 <noreply@anthropic.com>`.

## 5. Leçons apprises (post-mortems)

- **`.gitignore` : ancrer les règles à la racine.** `vendor/` et `*.sql` (sans `/` initial) excluaient les
  dépendances embarquées des extensions → Contact Form 7 impossible à activer
  (`require 'vendor/autoload.php'`). Règle : `/vendor/`, `/*.sql`, jamais à toute profondeur.
- **YAML : quoter toute valeur de `name:` contenant `:`** — sinon GitHub n'enregistre pas le workflow, sans
  message d'erreur exploitable (nom affiché = chemin du fichier, `workflow_dispatch` refusé).
- **SFTP : chemin relatif** (`public_html`), un `/` initial sort du chroot (`Permission denied`).
- **Installeur NOC WP : dossier cible vide requis.**
- **Déployer ≠ activer.** L'activation est un état en base (`active_plugins`, `stylesheet`).
- **Un fatal dans le hook d'une extension tue toute la requête, REST compris**, hors de portée d'un
  `try/catch` de mu-plugin. Le filet `register_shutdown_function` de `connectis-activate.php` enregistre la
  dernière erreur fatale (lisible sur `/wp-json/connectis/v1/status`) ; PHP n'écrit aucun log
  (`log_errors=Off`, `error_log=/dev/null`). Dernier recours : phpMyAdmin, lire `wp_options`
  (`connectis_last_fatal_error`) et remettre `active_plugins` à `a:0:{}`.
- **L'ancien déploiement complet n'était pas atomique** : des fichiers PHP réécrits pendant qu'ils sont
  chargés donnaient des erreurs 500 / « classe introuvable » transitoires. Résolu par l'incrémental.
- **LiteSpeed Cache met en cache des réponses REST GET** (diagnostic périmé). L'endpoint de statut envoie
  `nocache_headers()` ; ajouter `?t=<timestamp>` pour tester.
- **CSS : ne jamais laisser une image et un badge `inline` sur la même ligne.** Cause réelle du logo
  « décentré » de la page d'attente pendant plusieurs itérations : le badge (inline-block) se plaçait à
  droite de l'image. Et un masque circulaire (`border-radius: 50%`) rogne tout rectangle plus large que
  son diamètre inscrit. Toujours **vérifier par un rendu réel** (Chrome headless avec
  `--virtual-time-budget` pour laisser finir les animations ; émuler le mobile via une `<iframe>` de 390 px
  car Chrome refuse les fenêtres de moins de 500 px).
- **Alpha sur JPEG : le bruit de compression** du fond devient visible dès qu'on rend le fond transparent
  (petits carrés sombres autour de l'icône) → seuil d'alpha élevé + filtre médian.

## 6. Accès

| Accès | Usage |
|---|---|
| GitHub (`gh`) | push, workflows, dépôt |
| API / MCP WordPress | alias **`connectis`** dans `C:\Users\rogue\Claude\wp-sites.json` (mot de passe d'application) |
| SFTP | uniquement via GitHub Actions (secrets), jamais en direct |
| phpMyAdmin / panneau NOC | humain uniquement |

**Règle de sécurité :** le site par défaut du connecteur MCP est **rogueh24.fr** (autre projet). Tout appel
`mcp__wordpress__*` sur ce projet doit passer explicitement `site: "connectis"`.
Un agent IA ne saisit jamais un mot de passe ou un jeton dans un fichier, un formulaire ou un terminal :
l'humain les place lui-même.

## 7. Design, aperçu et mode construction

- **Mode construction** : `connectis-maintenance.php` sert la page d'attente (HTTP 503) à tout visiteur non
  connecté quand `CONNECTIS_MAINTENANCE` vaut `true`. Le site est **en ligne** depuis 2026-09-25 (`false`).
  Ne pas supprimer le fichier : le déploiement (`lftp mirror` sans `--delete`) ne retire rien du serveur.
- **Mode aperçu** : `https://connectis-solutions.fr/?cp=<jeton>` montre le vrai site sans compte (cookie 12 h).
  Le jeton est le réglage REST `connectis_preview_token` (base de données, **jamais dans Git**) ; le
  changer = `settings/update`. Les réponses d'aperçu sont `no-cache` pour ne jamais être servies au public.
- **Configuration du thème** : `connectis-theme-config.php` pose les réglages Blocksy inaccessibles par
  l'API (en-tête `header_placements`, palette `colorPalette`) ; à relancer en incrémentant
  `CONNECTIS_THEME_CONFIG_VERSION`. Les emplacements de menu se règlent par l'API (`menus/update` →
  `locations`) — `nav_menu_locations` est propre à chaque thème, d'où le menu alphabétique initial.
- **Charte CSS** : `mu-plugins/connectis-branding/custom.css`, injecté dans `<head>` par
  `connectis-branding.php`. L'outil « CSS additionnel » de l'API MCP **ne persiste rien** (relecture vide).
- **Itérer sans déployer** : enregistrer le HTML réel (avec `?cp=`), y injecter `<base href>` + le CSS candidat,
  puis rendre avec Chrome headless (ordinateur, iframe 390 px pour le mobile, script pour ouvrir le tiroir
  du menu). Ne déployer qu'une fois le rendu validé.
- Les images de `mu-plugins/connectis-content-seed/images/` viennent de Pexels (licence libre).

## 8. Activation automatique (historique)

`connectis-activate.php` a activé Blocksy et les premières extensions au premier chargement. L'activation
se fait désormais **par l'API** (plus contrôlée, cf. §3). Le fichier reste utile pour son endpoint de
diagnostic et son filet d'erreurs.

## 9. Contenu versionné, SEO et administration (mu-plugins)

| Fichier | Rôle | Réapplication |
|---|---|---|
| `connectis-pages.php` | Contenu des pages (composants) | Incrémenter `CONNECTIS_PAGES_VERSION` — **écrase** les modifications faites dans l'éditeur |
| `connectis-legal.php` | Textes légaux (utilisés par `connectis-pages.php`) | idem |
| `connectis-forms.php` | Formulaires CF7 + mises en page | `connectis_forms_layout_v1` |
| `connectis-seo.php` | Titres/descriptions SEOPress, plan du site, JSON-LD | `CONNECTIS_SEO_VERSION` |
| `connectis-admin.php` | Réglages, durcissement, tableau de bord, connexion | `CONNECTIS_ADMIN_VERSION` |
| `connectis-branding.php` | CSS, bandeau d'identité, menu, import des logos | `connectis_branding_v1/v2` |

Leçons :
- **Le déploiement compare la taille des fichiers** : passer `= 1` à `= 2` ne change pas la taille, le fichier n'est
  pas renvoyé. Toujours modifier aussi un commentaire (ex. « v2 : … ») quand on incrémente une version.
- **Les pages se réappliquent à la première requête suivant le déploiement** ; vérifier `/wp-json/connectis/v1/status`
  (`pages.version`, `seo`, `admin`, `branding`).
- **Cache LiteSpeed** (`public,max-age=604800`) : tester avec un paramètre `?t=` ; les images portent `?v=<version>`.
- **Balisage Blocksy** : le `<ul>` du menu hors-toile n'a pas de classe `.menu` — cibler `#offcanvas nav > ul`.
- **Lien « Accueil »** du menu = lien personnalisé (pas une page) : détecter par URL, pas par `object_id`.
- **Anti-spam WP Armour** : exige le JavaScript du navigateur ; un test de formulaire par l'API REST échoue
  volontairement (`validation_failed`). Tester l'envoi depuis le navigateur.
- Doublons de pages : chaque exécution partielle du contenu de départ a recréé des pages ; les identifiants
  32 à 43 étaient des doublons. Le contenu de départ n'est plus rejoué (indicateur `connectis_content_seeded_v2`).

### Tableau de bord des mu-plugins (état v1.1.0)

| Fichier | Version courante | Particularités |
|---|---|---|
| `connectis-pages.php` | 11 | Composants (cartes, étapes, FAQ, confiance), filtre typographique (espace insécable, « Wi-Fi »), unique H1 |
| `connectis-legal.php` | — | CGV (19 art.), mentions légales (9 sections), confidentialité ; constante `CONNECTIS_CAPITAL_SOCIAL` |
| `connectis-seo.php` | 3 | Métadonnées SEOPress, mots-clés cibles, plan du site, image de partage, JSON-LD, redirection des doublons `-2` |
| `connectis-admin.php` | 1 | Réglages, durcissement, tableau de bord, connexion, **purge du cache à chaque déploiement**, barre d'administration réservée aux administrateurs |
| `connectis-branding.php` | logo v2 | CSS, menu (logo cliquable, ordre, bouton), apparition au défilement |
| `connectis-theme-config.php` | 1 | Configuration Blocksy (en-tête, palette), pied de page |
| `connectis-cache.php` | 1 | Réglages LiteSpeed Cache (cache navigateur, minification) — CSS critique/UCSS non activés (gain faible, risque de flash sans style) |
| `connectis-maintenance.php` | — | Interrupteur `CONNECTIS_MAINTENANCE` (`false` = site public) |

Autres leçons de la mise en ligne :
- **Le cache LiteSpeed sert l'ancien HTML/CSS aux visiteurs** après un déploiement : `connectis-admin.php` compare une
  empreinte (taille + date des fichiers des mu-plugins) à chaque requête et purge le cache si elle change.
- **La comparaison par taille** : incrémenter une constante de version sans changer la taille du fichier ne le
  redéploie pas → modifier aussi un commentaire (« v11 : … »).
- **Les données officielles** de l'entreprise se lisent via l'API publique
  `https://recherche-entreprises.api.gouv.fr/search?q=<SIREN>` (la page de l'Annuaire est rendue en JavaScript).
  Le capital et la date d'immatriculation figurent au RNE (INPI) ; le n° de TVA intracommunautaire se calcule
  (`FR` + clé + SIREN) et doit être confirmé.
- **L'adresse exacte `/wp-admin/index.php`** a été signalée à tort par la navigation sécurisée de Google : les liens
  « Tableau de bord » pointent vers `/wp-admin/` ; signalement de faux positif à déposer sur
  `safebrowsing.google.com/safebrowsing/report_error/`.
- **Aucun loueur n'est cité** sur le site (décision commerciale) : formulation « partenaires financiers ».

