#!/usr/bin/env bash
set -e

echo "🚀 Starting Auntie Kash Kids Academy..."

# Generate APP_KEY only if not already set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Wait briefly for the database to be reachable
echo "⏳ Waiting for database..."
sleep 5

# Run migrations
echo "🗄️  Running migrations..."
php artisan migrate --force

# Seed the database (only seeds if users table is empty — safe to rerun)
echo "🌱 Seeding database (first run only)..."
php artisan db:seed --force || echo "⚠️  Seeding skipped (data may already exist)"

# Cache config for performance
php artisan config:cache
php artisan route:cache

# Start the server on Railway's assigned port
echo "✅ Launching web server on port ${PORT:-8080}..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
