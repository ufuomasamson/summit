#!/usr/bin/env bash
# Summit production deploy — sync git checkout into the live web root.
# Safe by design: never deletes uploads, never rewrites .env, never resets DB.

set -euo pipefail

SOURCE="${SOURCE:-/home/serveradmin/summit-inspect}"
DEST="${DEST:-/www/wwwroot/summitblive.com}"
COMPOSER_BIN="${COMPOSER_BIN:-/usr/bin/composer}"
PHP_BIN="${PHP_BIN:-}"

log() {
  echo "==> [$(date -u +%Y-%m-%dT%H:%M:%SZ)] $*"
}

fail() {
  echo "ERROR: $*" >&2
  exit 1
}

resolve_php() {
  if [ -n "${PHP_BIN}" ] && command -v "${PHP_BIN}" >/dev/null 2>&1; then
    return 0
  fi
  if command -v php8.2 >/dev/null 2>&1; then
    PHP_BIN="$(command -v php8.2)"
    return 0
  fi
  if [ -x /usr/bin/php8.2 ]; then
    PHP_BIN=/usr/bin/php8.2
    return 0
  fi
  fail "PHP 8.2 binary not found (tried php8.2 and /usr/bin/php8.2). Set PHP_BIN explicitly."
}

require_paths() {
  [ -d "${SOURCE}" ] || fail "Source checkout missing: ${SOURCE}"
  [ -d "${DEST}" ] || fail "Website directory missing: ${DEST}"
  [ -f "${SOURCE}/artisan" ] || fail "Source does not look like a Laravel app: ${SOURCE}/artisan missing"
  [ -f "${SOURCE}/composer.lock" ] || fail "composer.lock missing in source checkout"
  [ -f "${DEST}/.env" ] || fail "Production .env missing at ${DEST}/.env — refusing to deploy without existing env"
  [ -x "${COMPOSER_BIN}" ] || [ -f "${COMPOSER_BIN}" ] || fail "Composer not found at ${COMPOSER_BIN}"
  command -v rsync >/dev/null 2>&1 || fail "rsync is required but was not found on PATH"
}

sync_application_code() {
  log "Synchronizing application code"
  log "Source: ${SOURCE}"
  log "Dest:   ${DEST}"
  log "Preserving: .env, storage/, public/storage/, vendor/, node_modules/, runtime caches"
  log "rsync mode: archive + verbose (NO --delete)"

  # Non-destructive sync. Intentionally omit --delete so production-only
  # files (uploads, cached views that differ, etc.) are never removed.
  rsync -av \
    --exclude='.git/' \
    --exclude='.github/' \
    --exclude='.env' \
    --exclude='.env.*' \
    --exclude='storage/' \
    --exclude='public/storage/' \
    --exclude='vendor/' \
    --exclude='node_modules/' \
    --exclude='bootstrap/cache/*.php' \
    --exclude='scripts/vps-deploy.sh' \
    "${SOURCE}/" "${DEST}/" \
    || fail "rsync failed — check that ${USER} can write under ${DEST}"

  log "Code sync complete"
}

install_composer_dependencies() {
  log "Installing Composer production dependencies (composer install, never update)"
  cd "${DEST}"

  # Use the lockfile already synced into DEST. Never run composer update.
  # composer.json scripts on install: post-autoload-dump → package:discover only.
  # post-root-package-install / post-create-project-cmd (key:generate) do NOT run on install.
  "${PHP_BIN}" "${COMPOSER_BIN}" install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-ansi \
    || fail "composer install failed under PHP ${PHP_BIN}"

  log "Composer install complete"
}

refresh_laravel_caches() {
  log "Refreshing Laravel caches"
  cd "${DEST}"

  [ -f "${DEST}/artisan" ] || fail "artisan missing in ${DEST}"

  # Clear stale caches, then rebuild config/views only.
  # route:cache is omitted: web/user/admin/api define closure routes, which
  # Laravel cannot serialize. Prefer reliable deploys over optional route caching.
  # No migrations, seeds, key generation, or DB writes.
  "${PHP_BIN}" artisan config:clear || fail "artisan config:clear failed"
  "${PHP_BIN}" artisan route:clear || fail "artisan route:clear failed"
  "${PHP_BIN}" artisan view:clear || fail "artisan view:clear failed"
  "${PHP_BIN}" artisan cache:clear || log "WARNING: artisan cache:clear failed (non-fatal if cache driver unavailable)"

  "${PHP_BIN}" artisan config:cache || fail "artisan config:cache failed"
  "${PHP_BIN}" artisan view:cache || fail "artisan view:cache failed"

  log "Laravel caches refreshed (config + views; routes left uncached)"
}

verify_post_deploy() {
  log "Post-deploy checks"
  cd "${DEST}"

  [ -f .env ] || fail ".env disappeared during deploy"
  [ -d storage ] || fail "storage/ directory missing after deploy"
  [ -d storage/app ] || fail "storage/app missing after deploy"
  [ -d vendor ] || fail "vendor/ missing after composer install"
  [ -f vendor/autoload.php ] || fail "vendor/autoload.php missing"

  log "PHP: $("${PHP_BIN}" -v | head -n 1)"
  log "Laravel: $("${PHP_BIN}" artisan --version 2>/dev/null || echo 'unknown')"
  log "Commit deployed from checkout: $(git -C "${SOURCE}" rev-parse --short HEAD)"
}

main() {
  log "Starting Summit VPS deployment"
  resolve_php
  require_paths

  log "Using PHP: ${PHP_BIN}"
  log "Using Composer: ${COMPOSER_BIN}"

  # Frontend: Mix source exists, but public/js/app.js is not tracked and views
  # do not load Mix output (guest uses temp/js/app.js; app.js Mix include is
  # commented out). Skipping npm/webpack on the VPS.
  log "Skipping npm/webpack build (no tracked Mix output; app does not require it)"

  sync_application_code
  install_composer_dependencies
  refresh_laravel_caches
  verify_post_deploy

  log "Deployment succeeded"
  log "Permissions: existing ownership on ${DEST} was left unchanged."
  log "If artisan/composer cannot write bootstrap/cache, grant write access for the deploy user on:"
  log "  ${DEST}/bootstrap/cache"
  log "  ${DEST}/storage (already preserved; PHP-FPM must keep write access)"
  log "Do NOT use chmod 777. Prefer group write for the PHP-FPM/web user only if needed."
}

main "$@"
