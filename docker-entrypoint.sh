#!/bin/sh
set -e

# Clear cached config so runtime env vars take effect
php artisan config:clear 2>/dev/null || true

# Ensure storage dirs exist (important when using volumes)
mkdir -p storage/framework/{sessions,cache/data,views} storage/logs

# Ensure DB exists and is migrated
touch database/database.sqlite
php artisan migrate --force 2>/dev/null || true

exec "$@"
