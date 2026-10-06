# Connectis Solutions — consignes pour l'assistant

Lu au début de chaque conversation, quel que soit le compte. L'état du site est dans
`README.md` (« Ce qui est livré », « État d'avancement ») et `CHANGELOG.md` (« Non publié →
À venir ») ; les règles techniques dans `docs/ENGINEERING.md`. **Les lire avant d'agir.**

## Le projet

Site vitrine WordPress de Connectis Solutions (informatique, vidéosurveillance, téléphonie
pour professionnels) : <https://connectis-solutions.fr>, **en ligne depuis le 25 septembre
2026**. Thème Blocksy, hébergement Planet Hoster. Dépôt `rogueh24/connectis-solutions`,
branche `main`.

## Le propriétaire

- Il écrit en français, vite, avec des fautes de frappe : répondre **en français**, simplement.
- « Fait au mieux », « go », « continue » : décider et avancer, puis dire ce qui a été fait.
- Budget : outils gratuits. Il change de compte Claude quand il atteint sa limite : **tout
  doit être dans le dépôt** (README, CHANGELOG, ENGINEERING à jour) à la fin de chaque passe.

## Ce qu'il faut savoir avant de toucher à quoi que ce soit

1. **C'est un site en production.** Tout `git push` sur `main` qui touche `wp-content/**`
   **déploie sur le serveur** (GitHub Actions → SFTP, environ deux minutes). Un push qui ne
   touche que la documentation ne déploie rien.
2. **Avant un push qui déploie : dire ce qui va partir et attendre son accord**, sauf s'il
   vient de le demander. Après : vérifier le site et `/wp-json/connectis/v1/status`.
3. **Le contenu, le SEO et les réglages sont versionnés** dans `wp-content/mu-plugins/` :
   on modifie le site par le code, pas par l'administration WordPress.
4. Le cœur de WordPress (`wp-admin`, `wp-includes`, `wp-config.php`) n'est pas dans le dépôt.
5. **Aucun identifiant dans le dépôt ni dans une réponse.** Les secrets sont dans GitHub
   (Settings → Secrets). Le fichier `wordpress admin.txt` du dossier contient des accès : ne
   pas l'ouvrir, ne pas le citer, ne jamais le versionner.
6. Commits en français, une phrase qui dit ce qui change (voir `git log`).
7. Dire vrai : ce qui n'a pas été vérifié sur le site en ligne n'est pas « fait ».

## Fin de passe

`CHANGELOG.md` (une entrée datée), `README.md` si l'état change, `docs/ENGINEERING.md` si une
règle ou une leçon s'ajoute, commit, push — en sachant s'il déploie ou non.
