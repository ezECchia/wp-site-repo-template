#!/usr/bin/env bash
set -euo pipefail

SITE=${SITE:-/volume/htdocs/audio}
REPO_ROOT="$(cd "$(dirname "$0")/.." && pwd)"

echo "[info] SITE=$SITE"
echo "[info] REPO=$REPO_ROOT"

# Check source path
if [ ! -d "$SITE/wp-content" ]; then
  echo
  echo "[error] WordPress path not found:"
  echo "  $SITE/wp-content"
  echo
  echo "Make sure the SITE path is correct."
  echo "Example:"
  echo "  SITE=/volume/htdocs/example.com.tw ./scripts/sync-from-site.sh"
  echo
  exit 2
fi

mkdir -p "$REPO_ROOT/mu-plugins" "$REPO_ROOT/themes" "$REPO_ROOT/plugins"

echo "[sync] mu-plugins (exclude stg-* / *.off / staging readme)"
rsync -a --delete \
  --exclude="*.off" \
  --exclude="stg-*.php" \
  --exclude="STAGING-README.txt" \
  --exclude="index.html" \
  "$SITE/wp-content/mu-plugins/" \
  "$REPO_ROOT/mu-plugins/"

echo "[sync] theme astra-child"
rm -rf "$REPO_ROOT/themes/astra-child"
cp -a "$SITE/wp-content/themes/astra-child" "$REPO_ROOT/themes/"

echo "[sync] custom plugins (exclude mxp-dev-tools)"
for p in \
  custom-elementor-uael-overrides \
  nmth-export-empty-alert-h2 \
  nmth-http-guard
do
  rm -rf "$REPO_ROOT/plugins/$p"
  rsync -a --delete \
    --exclude="*.off" \
    --exclude="*.bak" --exclude="*.bak.*" \
    "$SITE/wp-content/plugins/$p/" \
    "$REPO_ROOT/plugins/$p/"
done

# keep repo clean (even if copied in)
rm -f "$REPO_ROOT/plugins/nmth-http-guard/"*.bak.* 2>/dev/null || true

echo "[done] sync completed"
