#!/usr/bin/env bash
# Same flow as deploy-stc-gld.ps1 (Linux/macOS/Git Bash).
# Usage:
#   ./deploy-stc-gld.sh              # build only if React source changed
#   ./deploy-stc-gld.sh --force      # deploy without React build
#   ./deploy-stc-gld.sh --rebuild    # always rebuild React
set -euo pipefail
REPO_ROOT="$(cd "$(dirname "$0")" && pwd)"
cd "$REPO_ROOT"

STC_GLD="$REPO_ROOT/stc_gld"
FORCE=false
REBUILD=false
for arg in "$@"; do
  case "$arg" in
    --force|-Force) FORCE=true ;;
    --rebuild|-Rebuild) REBUILD=true ;;
  esac
done

if [[ ! -d "$STC_GLD" ]]; then
  echo "Expected folder not found: $STC_GLD" >&2
  exit 1
fi

stc_gld_source_changed() {
  while IFS= read -r line; do
    [[ -z "$line" ]] && continue
    path="${line:3}"
    path="${path#\"}"
    path="${path%\"}"
    [[ "$path" =~ (^|/)(build|node_modules)(/|$) ]] && continue
    [[ "$path" =~ build-archive\.zip$ ]] && continue
    return 0
  done < <(git -C "$REPO_ROOT" status --porcelain stc_gld)
  return 1
}

if ! git -C "$REPO_ROOT" status --porcelain | grep -q . && [[ "$FORCE" != true ]]; then
  echo "No changes in the repo. Use --force to commit and push anyway (skips React build)."
  exit 0
fi

SHOULD_BUILD=false
if [[ "$REBUILD" == true ]] || stc_gld_source_changed; then
  SHOULD_BUILD=true
fi

if [[ "$SHOULD_BUILD" != true ]]; then
  if [[ "$FORCE" == true ]]; then
    echo "Force deploy: no React source changes — skipping npm run build and build-archive.zip."
  else
    echo "No React source changes under stc_gld — skipping npm run build and build-archive.zip."
  fi
else
  echo "Running npm run build..."
  ( cd "$STC_GLD" && npm run build )

  ZIP="$STC_GLD/build-archive.zip"
  rm -f "$ZIP"
  echo "Creating $ZIP ..."
  ( cd "$STC_GLD" && zip -r -q build-archive.zip build )
fi

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

if [[ "$SHOULD_BUILD" == true ]]; then
  echo "Done. Deploy workflow will pull and unzip build-archive.zip on the server."
else
  echo "Done. Deploy workflow will pull only (no React archive update)."
fi
