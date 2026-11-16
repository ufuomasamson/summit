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

# Create Laravel runtime directories if missing. Never deletes existing content,
# never chmods 777, never changes ownership.
ensure_storage_directories() {
  log "Ensuring required Laravel directories exist under ${DEST}"
  cd "${DEST}"

  local dirs=(
    storage
    storage/app
    storage/app/public
    storage/app/public/photos
    storage/app/public/uploads
    storage/app/livewire-tmp
    storage/app/temp
    storage/framework
    storage/framework/cache
    storage/framework/cache/data
    storage/framework/sessions
    storage/framework/testing
    storage/framework/views
    storage/logs
    bootstrap/cache
  )

  local d
  for d in "${dirs[@]}"; do
    if [ ! -d "${DEST}/${d}" ]; then
      mkdir -p "${DEST}/${d}" || fail "Could not create ${DEST}/${d} — grant write access for ${USER} without chmod 777"
      log "Created missing directory: ${d}"
    else
      log "Present: ${d}"
    fi
  done

  [ -d "${DEST}/storage/app" ] || fail "storage/app still missing after ensure step"
}

# Detect the PHP-FPM / web user.
# Prefer the owner of the live PHP-FPM socket (this site uses www-data).
# Do NOT prefer "www" just because the account exists — aaPanel boxes can
# have both www and www-data while PHP-FPM runs as www-data.
detect_web_user() {
  local sock owner candidate
  for sock in /run/php/php8.2-fpm.sock /run/php/php-fpm.sock /tmp/php-cgi-82.sock /tmp/php-cgi.sock; do
    if [ -S "${sock}" ]; then
      owner="$(stat -c '%U' "${sock}" 2>/dev/null || true)"
      if [ -n "${owner}" ] && [ "${owner}" != "root" ]; then
        echo "${owner}"
        return 0
      fi
    fi
  done

  # Fall back: first matching known web user (www-data before www)
  for candidate in www-data www nginx apache httpd; do
    if id "${candidate}" >/dev/null 2>&1; then
      echo "${candidate}"
      return 0
    fi
  done
  return 1
}

# Make storage writable for PHP-FPM without chmod 777.
# aaPanel typically needs the web user as OWNER (chgrp alone is often not enough
# when dirs were created by serveradmin as 755).
ensure_storage_permissions() {
  log "Ensuring storage/bootstrap/cache are writable by PHP-FPM"
  cd "${DEST}"

  local web_user=""
  if web_user="$(detect_web_user)"; then
    log "Detected web user: ${web_user}"
  else
    log "WARNING: Could not detect web user (www/www-data). Skipping ownership adjust."
    log "Run manually (aaPanel):"
    log "  cd ${DEST} && sudo chown -R www:www storage bootstrap/cache && sudo chmod -R ug+rwX storage bootstrap/cache"
    return 0
  fi

  # Own only runtime-writable trees (not the whole site).
  if chown -R "${web_user}:${web_user}" storage bootstrap/cache 2>/dev/null; then
    log "Set owner ${web_user}:${web_user} on storage/ and bootstrap/cache/"
  elif chgrp -R "${web_user}" storage bootstrap/cache 2>/dev/null; then
    log "chown failed; fell back to chgrp ${web_user}"
  else
    log "WARNING: could not chown/chgrp storage (need passwordless sudo for ${USER})."
    log "Fix now with:"
    log "  sudo chown -R ${web_user}:${web_user} ${DEST}/storage ${DEST}/bootstrap/cache"
    log "  sudo chmod -R ug+rwX ${DEST}/storage ${DEST}/bootstrap/cache"
  fi

  chmod -R ug+rwX storage bootstrap/cache 2>/dev/null || log "WARNING: chmod ug+rwX failed"
  find storage bootstrap/cache -type d -exec chmod g+s {} \; 2>/dev/null || true

  # Prove photos is writable by creating/removing a probe file as current deploy user.
  # Real PHP-FPM writability still depends on ownership matching the FPM user.
  if [ -d storage/app/public/photos ]; then
    if touch storage/app/public/photos/.deploy_write_test 2>/dev/null; then
      rm -f storage/app/public/photos/.deploy_write_test
      log "photos/ write probe ok (deploy user)"
    else
      log "WARNING: cannot write storage/app/public/photos as ${USER}"
    fi
    ls -ld storage storage/app storage/app/public storage/app/public/photos || true
  fi
}

sync_application_code() {
  log "Synchronizing application code"
  log "Source: ${SOURCE}"
  log "Dest:   ${DEST}"
  log "Preserving: .env, existing uploads, vendor/, node_modules/, runtime caches"
  log "rsync mode: archive + verbose (NO --delete)"

  # Non-destructive sync. Omit --delete so production-only files are never removed.
  # Sync tracked storage scaffolds (.gitignore / safe brand assets) but never
  # overwrite or clear user uploads, KYC docs, deposit proofs, or logs.
  rsync -av \
    --exclude='.git/' \
    --exclude='.github/' \
    --exclude='.env' \
    --exclude='.env.*' \
    --exclude='public/storage/' \
    --exclude='vendor/' \
    --exclude='node_modules/' \
    --exclude='bootstrap/cache/*.php' \
    --exclude='scripts/vps-deploy.sh' \
    --exclude='storage/*.key' \
    --exclude='storage/*.xlsx' \
    --exclude='storage/leads.xlsx' \
    --exclude='storage/logs/*.log' \
    --exclude='storage/framework/cache/data/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    --exclude='storage/app/public/photos/*' \
    --exclude='storage/app/public/uploads/*' \
    --exclude='storage/app/livewire-tmp/*' \
    --exclude='storage/app/temp/*' \
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
  [ -d storage/app/public ] || fail "storage/app/public missing after deploy"
  [ -d storage/framework ] || fail "storage/framework missing after deploy"
  [ -d storage/logs ] || fail "storage/logs missing after deploy"
  [ -d bootstrap/cache ] || fail "bootstrap/cache missing after deploy"
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
  ensure_storage_directories
  ensure_storage_permissions
  install_composer_dependencies
  refresh_laravel_caches
  verify_post_deploy

  log "Deployment succeeded"
  log "Storage permissions: owned/writable for PHP-FPM web user (no chmod 777)."
  log "If logo/favicon uploads still fail, match the PHP-FPM user (this host: www-data):"
  log "  cd ${DEST}"
  log "  sudo chown -R www-data:www-data storage bootstrap/cache"
  log "  sudo chmod -R ug+rwX storage bootstrap/cache"
  log "  sudo -u www-data touch storage/app/public/photos/.wtest && sudo -u www-data rm storage/app/public/photos/.wtest"
}

main "$@"
