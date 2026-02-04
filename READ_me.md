# DocuPilot (ex Super Paperasse) — README (version équipe)

> **Objectif** : Construire un agent IA qui centralise, classe, recherche et rappelle automatiquement tout ce qui touche à la paperasse (factures, contrats, impôts, abonnements, courriers).  
> **Philosophie** : *utile dès le jour 1*, automatisable progressivement, et **sécurité/RGPD** au cœur.

---

## 1) Vision produit (à aligner entre nous)

### Problème
- Les documents admin sont dispersés (mails, papier, drive, photos).
- On oublie des échéances (assurance, impôts, renouvellements).
- On paie des abonnements inutiles/doublons.
- Chercher un document est long et stressant.

### Solution (promesse)
- **1 seul endroit** pour tous les documents.
- **Classement automatique** + **recherche instantanée**.
- **Rappels intelligents** (dates clés, fins d’essai, renouvellements).
- **Insights** (abonnements détectés, doublons, optimisation).

### Public cible (MVP)
- Étudiants + jeunes actifs (France) → besoin simple, adoption rapide.
- Ensuite : familles (partage sécurisé) + indépendants (factures/contrats).

### Définition du succès
- “Je retrouve un document en < 10 secondes”.
- “Je ne rate plus une échéance”.
- “Je récupère X€ / mois d’abonnements inutiles”.

---

## 2) MVP — ce qu’on construit en premier (non négociable)

### MVP V1 (focus “Document Hub”)
1. **Upload** (PDF / image) + stockage sécurisé
2. **OCR** (si image/scanné) + extraction texte
3. **Classification** (catégorie + tags)
4. **Recherche** (full-text + filtres)
5. **Rappels** (dates détectées + création manuelle)

> Tout le reste (banque, emails, résiliation 1 clic, comparateurs) = V2/V3.

---

## 3) Périmètre fonctionnel (axes à couvrir)

### A) Ingestion (entrées)
- Upload manuel (drag & drop)
- Import dossier (batch) *(optionnel V1)*
- (V2) Connecteur email (Gmail/IMAP)
- (V2) Mobile scan (OCR)

### B) Traitement
- OCR + nettoyage texte
- Détection métadonnées :
  - fournisseur (EDF, Free, etc.)
  - montant
  - date facture / date échéance
  - type doc (facture, contrat, avis d’imposition…)
- Classification (catégorie) + tags automatiques
- Résumé court (1–2 lignes) *(optionnel V1)*

### C) Stockage & indexation
- Stockage fichier (S3-like ou local dev)
- Base de données (métadonnées)
- Index de recherche (Elastic / Meilisearch / Postgres FTS)

### D) Recherche & navigation
- Barre de recherche (type Google)
- Filtres : catégorie, date, fournisseur, montant, tags
- Aperçu doc + téléchargement
- Historique / audit log

### E) Rappels / agenda
- Rappels créés :
  - automatiquement (détection date)
  - manuellement (user)
- Notifications : email / push (V2)
- Récurrence (mensuel / annuel) *(V2)*

### F) Optimisation & insights (V2)
- Détection abonnements récurrents
- Doublons
- Alertes “prix anormal” (comparateur) *(V3)*

### G) Sécurité / conformité (dès V1)
- Auth + sessions/tokens
- Chiffrement au repos (min : disque + secrets)
- Chiffrement en transit (HTTPS)
- Gestion des droits d’accès (documents privés)
- RGPD : export / suppression compte

---

## 4) Stack technique (proposition simple pour démarrer)

### Backend (API)
- **Node.js (NestJS)** ou **Python (FastAPI)**  
  > Choisir 1 stack et s’y tenir pour éviter le chaos.
- Auth : JWT + refresh / ou sessions (selon architecture)
- Jobs async : BullMQ (Node) / Celery (Python)

### Base de données
- **PostgreSQL** (recommandé)
- Migrations : Prisma (Node) / Alembic (Python)

### Recherche
- MVP : **Postgres Full-Text Search**
- V2 : ElasticSearch / Meilisearch si besoin

### OCR
- Dev/MVP : **Tesseract** ou **PaddleOCR**
- Production : service OCR fiable (à décider plus tard)

### Frontend
- **Next.js** (ou React + Vite)
- UI : Tailwind + composants (shadcn, etc.)

### Stockage fichiers
- Dev : stockage local `storage/`
- Prod : S3 (AWS/Wasabi/Scaleway)

---

## 5) Architecture (modules)

```
Frontend (Web)
   |
API (Auth, Documents, Search, Reminders)
   |
DB (metadata) --- Storage (files)
   |
Workers (OCR, extraction, classification, indexing, reminders)
```

### Modules backend (dossiers)
- `auth/` : login, register, tokens, permissions
- `documents/` : upload, download, metadata, catégories
- `processing/` : OCR, extraction, classification
- `search/` : indexation + requêtes
- `reminders/` : échéances + notifications
- `admin/` : logs, stats, monitoring
- `shared/` : utils, config, types

---

## 6) Modèle de données (minimum utile)

### Tables (exemple)
- `users`
  - id, email, password_hash, created_at
- `documents`
  - id, user_id, filename, mime_type, storage_path, created_at
- `document_text`
  - document_id, extracted_text, ocr_confidence
- `document_metadata`
  - document_id, category, provider, amount, currency, doc_date, due_date, tags(json)
- `reminders`
  - id, user_id, document_id(nullable), title, due_date, status, created_at
- `audit_logs`
  - id, user_id, action, entity, entity_id, created_at, ip

> Les champs exacts évolueront, mais ce modèle couvre V1.

---

## 7) API (contrats à respecter)

### Auth
- `POST /auth/register`
- `POST /auth/login`
- `POST /auth/refresh`
- `POST /auth/logout`
- `GET /me`

### Documents
- `POST /documents` (upload)
- `GET /documents` (liste + filtres)
- `GET /documents/:id`
- `GET /documents/:id/download`
- `DELETE /documents/:id`

### Processing
- `POST /documents/:id/process` (OCR + extraction)
- `GET /documents/:id/status`

### Search
- `GET /search?q=...&filters...`

### Reminders
- `POST /reminders`
- `GET /reminders`
- `PATCH /reminders/:id`
- `DELETE /reminders/:id`

---

## 8) Workflow équipe (Git + organisation)

### Branching (simple & efficace)
- Branche principale : `main`
- Une branche par tâche : `feature/<courte-description>`
- Fix urgent : `hotfix/<description>`

### Règles
- Ne pas commit directement sur `main`
- PR obligatoire (review + checks)
- Petits commits atomiques
- Message clair (règle 50/72 recommandée)

### Commandes type (résumé)
```bash
git checkout -b feature/upload-doc
git add .
git commit -m "Add document upload endpoint"
git push -u origin feature/upload-doc
# ouvrir PR -> review -> merge
```

---

## 9) Installation & démarrage (local)

### Prérequis
- Node 18+ **ou** Python 3.11+ (selon stack)
- Docker (recommandé)
- PostgreSQL

### Variables d’environnement
Créer `.env` :
- `DATABASE_URL=...`
- `JWT_SECRET=...`
- `STORAGE_PATH=./storage`
- `OCR_ENABLED=true`
- `APP_URL=http://localhost:3000`

### Démarrage (exemple générique)
```bash
# 1) Lancer DB
docker compose up -d

# 2) Installer dépendances
npm install
# ou pip install -r requirements.txt

# 3) Migrations
npm run db:migrate
# ou alembic upgrade head

# 4) Run API
npm run dev
# ou uvicorn app.main:app --reload

# 5) Run Front
cd web && npm install && npm run dev
```

---

## 10) Qualité (tests, lint, CI)

### Tests
- Unit tests : logique extraction/classification
- API tests : endpoints docs/auth/search
- E2E : upload → OCR → search → reminder

### Standards
- Lint + format auto (Prettier/ESLint ou Ruff/Black)
- Hooks : pre-commit / husky

### CI (GitHub Actions)
- Build
- Tests
- Lint
- Migration check

---

## 11) Sécurité & RGPD (à traiter sérieusement)

### À implémenter dès V1
- Hash mots de passe (bcrypt/argon2)
- JWT sécurisé (rotation refresh token)
- Limite upload (taille, types MIME)
- Antivirus scan *(V2 mais recommandé)*
- Permissions strictes (un user ne lit que ses docs)
- Logs + alertes

### RGPD
- Export des données user
- Suppression compte + purge documents
- Politique de rétention (optionnelle)

---

## 12) Roadmap (proposition)

### V1 (MVP)
- Auth + upload + OCR + classification simple
- Recherche + filtres
- Rappels basiques

### V2
- Connecteur Email
- Détection abonnements récurrents
- Notifications push/email

### V3
- Résiliation semi-automatique (templates)
- Comparateurs + optimisation prix
- Partage famille + coffre-fort

---

## 13) Répartition du travail (exemple)

- **Dev A** : Auth + Users + RBAC
- **Dev B** : Documents (upload/storage/download)
- **Dev C** : Processing (OCR + extraction)
- **Dev D** : Search + indexation + UI recherche
- **Dev E** : Reminders + scheduler + UI

> À adapter selon votre équipe.

---

## 14) Convention de nommage

- Branch : `feature/<action-nom>`
- Commits : verbe impératif, ex: `Add`, `Fix`, `Refactor`
- Dossiers : `snake_case` (python) ou `kebab-case` (web), mais cohérent

---

## 15) FAQ (problèmes fréquents)

- **“Je n’arrive pas à push”** → vérifier remote + permissions
- **Conflits** → `git pull --rebase`, résoudre, puis `git rebase --continue`
- **Fichier trop lourd** → ne jamais versionner dans git (utiliser storage)
- **Secrets** → jamais en dur, toujours `.env` + `.gitignore`

---

## 16) Ressources (références)
- Git cheat sheet officiel : https://git-scm.com/cheat-sheet
- Bonnes pratiques commit message (Chris Beams) : https://cbea.ms/git-commit/
- Git book (rebase, workflows) : https://git-scm.com/book/

---

# ✅ Prochaine étape
1) On choisit la stack (FastAPI ou NestJS)  
2) On génère le squelette repo (API + web + docker compose)  
3) On attribue les modules (Auth/Documents/Processing/Search/Reminders)

