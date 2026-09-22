# SKIM Application Skeleton

> Quick start skeleton for the [SKIM](https://github.com/skimphp/framework) PHP 8.5+ micro-framework.

This repository is the starting point for every new SKIM project. It contains the minimal scaffold — controllers, config, routes, and Docker services — so you can go from `composer create-project` to a working "Hello World" in under a minute.

---

## Installation

### Via Composer (recommended — when published on Packagist)

```bash
composer create-project skim/skeleton myapp && cd myapp
```

### Via Git

```bash
git clone https://github.com/skimphp/skeleton.git myapp && cd myapp
composer install
```

### Via Composer with VCS repositories (no Packagist needed)

```bash
composer create-project skim/skeleton myapp \
  --repository='{"type":"vcs","url":"https://github.com/skimphp/skeleton.git"}' \
  --repository='{"type":"vcs","url":"https://github.com/skimphp/framework"}' \
  --stability=dev
```

> **Note:** During `composer create-project` or `composer install`, the `post-root-package-install` script automatically copies `.env.example` to `.env` if `.env` does not exist. No manual `cp` is needed.

---

## Zero-dependency Hello World

After installation, point your web server to `public/` or use the Docker setup below. The default `.env` uses the **file** driver for cache and session, so the app works immediately without Redis, MySQL, or any external service.

### PHP built-in server (simplest, no Docker)

```bash
cd myapp
php -S localhost:8080 -t public/
```

Open [http://localhost:8080](http://localhost:8080) — you will see the Hello World page.

### Nginx

```nginx
server {
    listen 80;
    server_name myapp.local;
    root /var/www/myapp/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi_params;
    }
}
```

### Apache

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [L]
```

---

## Docker (optional)

The skeleton ships with a complete Docker Compose stack — PHP, MySQL, PostgreSQL, and Redis. Use it when you are ready to add database, cache, or queue workers.

```bash
cd myapp
docker compose up -d --build

# Run migrations inside the container
docker compose exec app php skim migrate

# Open in browser
open http://localhost:8080
```

| Service | Image | Port | Purpose |
|---------|-------|------|---------|
| `app` | PHP 8.5 Alpine | 8080 | Application |
| `mysql` | MySQL 8.0 | 3306 | Primary database |
| `pgsql` | PostgreSQL 16 | 5432 | Analytics database |
| `redis` | Redis 7 | — | Cache + Queue + Sessions |

When using Docker, edit `.env` to match the service names:

```env
DB_DRIVER=mysql
DB_HOST=mysql
DB_NAME=skim_dev
DB_USER=skim
DB_PASS=secret

CACHE_DRIVER=redis
REDIS_HOST=redis
REDIS_PORT=6379

SESSION_DRIVER=redis
```

---

## Project structure

```
myapp/
├── skim                      ← CLI entry point (stable proxy, never changes)
├── app/
│   ├── controllers/           ← your controllers (namespace app\controllers)
│   ├── models/                ← your models     (namespace app\models)
│   ├── views/                 ← PHP templates
│   └── commands/              ← CLI command classes
├── config/
│   ├── app.php                ← name, debug, env, timezone, log, commands
│   ├── db.php                 ← database connections (commented out by default)
│   └── cache.php              ← cache driver, Redis, file path
├── docker/
│   ├── Dockerfile             ← PHP 8.5 image
│   └── php.ini                ← development PHP settings
├── docker-compose.yml         ← app + mysql + pgsql + redis
├── migrations/                ← SQL migration files
├── public/
│   └── index.php              ← single HTTP entry point
├── .skim/
│   ├── cache/                 ← runtime file cache (not committed)
│   ├── config_cache/          ← compiled env + config (not committed)
│   ├── ide-helper.php         ← auto-generated IDE hints (not committed)
│   ├── logs/                  ← application logs (not committed)
│   ├── sessions/              ← session files (not committed)
│   └── temp/                  ← temporary files (not committed)
├── tests/                     ← Pest tests
├── .env                       ← local secrets, auto-copied from .env.example
├── .env.example               ← zero-dependency defaults + commented extras
├── routes.php                 ← HTTP routes
└── README.md
```

---

## CLI commands

```bash
php skim install              # interactive .env wizard
php skim serve                # PHP dev server
php skim migrate              # run pending migrations
php skim migrate:down         # rollback last batch
php skim migrate:fresh        # drop all + re-run (dev only)
php skim cache:build          # pre-compile env + config for production
php skim cache:clear          # flush cache by prefix
php skim ide:generate         # generate .ide-helper.php from DB schema
php skim list --agent         # compact command list for agents/scripts
```

---

## Enabling external services

The skeleton starts with **zero external dependencies**. When you are ready to add services, uncomment the relevant blocks in `.env` and `config/db.php`:

### Database (SQLite, MySQL, PostgreSQL)

1. Edit `config/db.php` — uncomment the connection block for your driver.
2. Edit `.env` — uncomment and set `DB_DRIVER`, `DB_HOST`, `DB_NAME`, etc.
3. Run `php skim migrate`.

### Redis cache / sessions

1. Edit `.env` — set `CACHE_DRIVER=redis` and `SESSION_DRIVER=redis`.
2. Uncomment the `REDIS_*` block in `.env`.
3. Ensure Redis is running (e.g. `docker compose up -d redis`).

---

## Updating the framework

```bash
composer update skim/framework
```

The `skim` CLI proxy in your project root **never changes** — it simply delegates to the framework's binary. You only need to update it manually if a new major version of the framework changes the entry-point contract (this will be documented in the upgrade guide).

---

## Manual testing without Packagist

Since `skim/framework` is not yet on Packagist, use one of these methods:

**Method 1 — Git clone (fastest):**
```bash
git clone https://github.com/skimphp/skeleton.git test-project
cd test-project
composer install
php -S localhost:8080 -t public/
# open http://localhost:8080
```

**Method 2 — Composer create-project with VCS:**
```bash
composer create-project skim/skeleton test-project \
  --repository='{"type":"vcs","url":"https://github.com/skimphp/skeleton.git"}' \
  --repository='{"type":"vcs","url":"https://github.com/skimphp/framework"}' \
  --stability=dev
cd test-project
php -S localhost:8080 -t public/
```

---

## License

MIT
