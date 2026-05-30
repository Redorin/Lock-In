# UDDSafeSpaces

UDDSafeSpaces is a Laravel application for campus space occupancy, student verification, QR check-ins, auto-checkout, and admin monitoring.

## Local Development

Install dependencies and build frontend assets:

```bash
composer install
npm install
npm run build
```

Prepare the app:

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
```

Useful checks:

```bash
php artisan test
php artisan view:cache
php artisan view:clear
```

## Production Environment

Do not commit your real `.env`. Use these values as the production baseline:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.example.com

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
CACHE_STORE=database
QUEUE_CONNECTION=database

TRUSTED_PROXIES=REMOTE_ADDR
```

Use `TRUSTED_PROXIES=REMOTE_ADDR` when the app is behind Cloudflare Tunnel or a reverse proxy you control. Laravel will trust forwarded HTTPS, host, port, prefix, and client IP headers from the proxy that connects to the app.

After changing production environment values, clear and rebuild cached config:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Cloudflare Tunnel

For a quick test tunnel to a local HTTPS app on port `8443`:

```bash
docker run --rm -it \
  --name campusims-tunnel \
  --dns 1.1.1.1 \
  --dns 8.8.8.8 \
  --add-host=host.docker.internal:host-gateway \
  cloudflare/cloudflared:latest tunnel \
  --no-autoupdate \
  --no-tls-verify \
  --url https://host.docker.internal:8443
```

For a real deployment, use a named Cloudflare Tunnel tied to your Cloudflare account and set `APP_URL` to the public HTTPS domain.

## Scheduler

The app schedules automatic checkout every 30 minutes in `bootstrap/app.php`.

With Docker Compose, the `scheduler` service runs `php artisan schedule:run` once per minute:

```bash
docker compose up -d scheduler
docker compose logs -f scheduler
```

Run Laravel's scheduler every minute on the production host:

```cron
* * * * * cd /path/to/campusims && php artisan schedule:run >> /dev/null 2>&1
```

Useful scheduled/manual commands:

```bash
php artisan checkins:auto-checkout
php artisan spaces:repair-occupancy --dry-run
php artisan spaces:repair-occupancy
```

`spaces:repair-occupancy` recalculates each space's `current_occupancy` from active check-ins.

## Queue Worker

The Compose file includes a `queue` service for database-backed queued jobs:

```bash
docker compose up -d queue
docker compose logs -f queue
```

If queued jobs are not being used yet, it is still safe to leave the worker running.

## Database Backups

Create a MySQL backup:

```bash
mysqldump -u USER -p DATABASE_NAME > backups/campusims-$(date +%F-%H%M).sql
```

Restore a backup:

```bash
mysql -u USER -p DATABASE_NAME < backups/campusims-YYYY-MM-DD-HHMM.sql
```

For production, store backups outside the app directory, restrict permissions, and periodically test restore on a separate database.
