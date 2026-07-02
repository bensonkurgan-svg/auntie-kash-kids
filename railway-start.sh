#!/usr/bin/env bash
# Auntie Kash Kids Academy — Railway startup
# Deliberately NOT using 'set -e' so a transient hiccup can't kill the boot.

echo "🚀 Starting Auntie Kash Kids Academy..."

# Ensure required writable directories exist
mkdir -p bootstrap/cache storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs storage/app/public storage/app/materials storage/app/public/avatars storage/app/public/blog-images
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Railway provides config via environment variables, so there is no .env file.
# Some artisan commands still try to read .env — create an empty one so they don't fatal.
if [ ! -f .env ]; then
    echo "📄 No .env file present (normal on Railway) — creating an empty placeholder."
    touch .env
fi

# APP_KEY handling:
# Preferred: APP_KEY is set as a Railway variable (recommended).
# Fallback: if it's genuinely empty, generate one into the placeholder .env.
if [ -z "$APP_KEY" ]; then
    echo "🔑 APP_KEY not set in environment — generating a temporary one..."
    echo "   (Recommended: set APP_KEY as a Railway variable so it stays stable across deploys.)"
    php artisan key:generate --force || echo "⚠️  key:generate failed — please set APP_KEY as a Railway variable."
else
    echo "🔑 APP_KEY found in environment ✓"
fi

# ── Wait for the database to accept connections (up to ~60s) ──
echo "⏳ Waiting for database to be ready..."
ATTEMPTS=0
until php artisan migrate:status >/dev/null 2>&1 || [ $ATTEMPTS -ge 20 ]; do
    ATTEMPTS=$((ATTEMPTS+1))
    echo "   ...database not ready yet (attempt $ATTEMPTS/20)"
    sleep 3
done

# ── Run migrations (don't abort boot if they fail) ──
echo "🗄️  Running migrations..."
php artisan migrate --force || echo "⚠️  Migrations reported an issue — continuing."

# ── Seed once (idempotent seeder; safe every deploy) ──
echo "🌱 Seeding database (first run only)..."
php artisan db:seed --force || echo "⚠️  Seeding skipped or already done."

# ── Cache config & routes (non-fatal) ──
php artisan config:cache || true
php artisan route:cache || true

# ── Link public/storage → storage/app/public so uploaded images are servable ──
php artisan storage:link 2>/dev/null || true

# ── Launch the web server on Railway's assigned port ──
echo "✅ Launching web server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
