#!/usr/bin/env bash
set -euo pipefail

# 1) Branch aus GitHub-Action ermitteln
#   - GITHUB_REF_NAME liefert z.B. "master" oder "develop"
BRANCH=${GITHUB_REF_NAME:-$(basename "${GITHUB_REF}")}

# Umgebungsvariablen aus der Action:
#   DEPLOYMENT_SSH_USER, DEPLOYMENT_SSH_SERVER, DEPLOYMENT_WORKTREE, COMPOSER_COMMAND

REMOTE="${DEPLOYMENT_SSH_USER}@${DEPLOYMENT_SSH_SERVER}"
WT="${DEPLOYMENT_WORKTREE}"

ssh "${REMOTE}" bash -lc "
  set -euo pipefail
  echo '→ Deploying branch ${BRANCH} into ${WT}'

  cd '${WT}'

  # 2) Aktuelle Daten vom remote origin holen
  git fetch origin

  # 3) harten Reset auf origin/<branch>
  git checkout '${BRANCH}'
  git reset --hard 'origin/${BRANCH}'

  # 4) Untracked files & dirs löschen
  git clean -fd

  # 5) Composer-Dependencies installieren (optional)
  echo '→ Composer installieren/updaten'
  ${COMPOSER_COMMAND} install --optimize-autoloader

  echo '✅ Deployment of ${BRANCH} finished.'
"