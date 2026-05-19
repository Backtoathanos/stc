#!/usr/bin/env bash
# Same flow as deploy-stc-gld.ps1 (Linux/macOS/Git Bash).
# Usage: ./deploy-stc-gld.sh   or   ./deploy-stc-gld.sh --force
set -euo pipefail
REPO_ROOT="$(cd "$(dirname "$0")" && pwd)"
cd "$REPO_ROOT"

STC_GLD="$REPO_ROOT/stc_gld"
FORCE=false
if [[ "${1:-}" == "--force" || "${1:-}" == "-Force" ]]; then FORCE=true; fi

if [[ ! -d "$STC_GLD" ]]; then
  echo "Expected folder not found: $STC_GLD" >&2
  exit 1
fi

if ! git -C "$REPO_ROOT" status --porcelain stc_gld | grep -q . && [[ "$FORCE" != true ]]; then
  echo "No changes under stc_gld. Use --force to build and push anyway."
  exit 0
fi

echo "Running npm run build..."
( cd "$STC_GLD" && npm run build )

ZIP="$STC_GLD/build-archive.zip"
rm -f "$ZIP"
echo "Creating $ZIP ..."
( cd "$STC_GLD" && zip -r -q build-archive.zip build )

cd "$REPO_ROOT"
git add .

if git diff --cached --quiet; then
  echo "Nothing to commit after git add. Exiting."
  exit 0
fi

read -r -p "Commit message: " msg
if [[ -z "${msg// /}" ]]; then
  echo "Commit message is required." >&2
  exit 1
fi

git commit -m "$msg"
git push
echo "Done. Deploy workflow will unzip build-archive.zip on the server."
