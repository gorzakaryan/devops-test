# Fix Summary

## Problem 1: HTTP 500 — Nginx stale upstream + cached config

### Symptom
`http://saas.local:8083/` and `/video` returned HTTP 500.

### Root causes

**A. Nginx stale upstream IP (Host unreachable 113)**
The `saas` PHP-FPM container was restarted and got a new Docker network IP. Nginx caches upstream DNS at startup, so it was trying to reach the old (dead) IP.

**Fix:**
```bash
docker restart saas-nginx-1
```

**B. Cached config with wrong absolute paths**
`bootstrap/cache/config.php` had hardcoded host paths like `/var/www/html/saas/...` that don't exist inside the container (only `/var/www/...` does). This caused `InvalidArgumentException: View [chat] not found.` → HTTP 500.

**Fix:**
```bash
docker exec saas-saas-2 php artisan config:clear
```

---

## Problem 2: WebSocket connection failed (Reverb not running)

### Symptom
```
WebSocket connection to 'ws://saas.local:8084/app/...' failed
```

### Root cause
The `saas` container only ran `php-fpm`. Reverb (the Laravel WebSocket server) was never started — nothing listened on port 8080 inside the container.

### Fix
Added `supervisor` to auto-start both `php-fpm` and `reverb:start` inside the same container.

### Files changed/created

#### `docker/saas/Dockerfile`
- Added `supervisor` to `apt-get install`
- Added `mkdir -p /var/log/supervisor /var/run/supervisor /var/log/php-fpm` with 777 perms
- Added `COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf`
- Added `COPY php-fpm.conf /usr/local/etc/php-fpm.d/zzz-custom.conf`
- Changed `CMD` from `["php-fpm"]` to `["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]`
- Removed `USER www-data` (supervisor runs as root, spawns children as www-data)

#### `docker/saas/supervisord.conf` (new)
Defines two supervised programs:
- `php-fpm` — runs as `www-data`, logs to `/var/log/php-fpm/`
- `reverb` — runs as `www-data`, runs `php artisan reverb:start --no-interaction`, logs to stdout

#### `docker/saas/php-fpm.conf` (new)
Overrides the default PHP-FPM logging to use files instead of `/proc/self/fd/2` (which `www-data` can't write when started by supervisor as root):
- `error_log = /var/log/php-fpm/error.log`
- `access.log = /var/log/php-fpm/access.log`

### Rebuild & restart
```bash
docker compose build saas
docker compose up -d saas
```
