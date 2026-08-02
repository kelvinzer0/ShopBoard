# ShopBoard

> Developer-Centric Shopping Kanban - Laravel 11 + Inertia.js + Vue 3

## Tech Stack
- **Backend:** Laravel 11 (SQLite)
- **Frontend:** Vue 3 + Inertia.js
- **Styling:** Tailwind CSS (Dark Mode)
- **Drag and Drop:** Native HTML5 DnD

## Docker (Recommended)

```bash
docker run -d --name shopboard -p 8000:8000 -v shopboard-data:/app/database ghcr.io/kelvinzer0/shopboard:latest
```

Open `http://localhost:8000`

### With docker compose

```yaml
services:
  shopboard:
    image: ghcr.io/kelvinzer0/shopboard:latest
    ports:
      - "8000:8000"
    volumes:
      - shopboard-data:/app/database

volumes:
  shopboard-data:
```

```bash
docker compose up -d
```

> Data persisted via volume. Multi-arch: amd64 + arm64.

## Manual Setup

```bash
git clone https://github.com/kelvinzer0/ShopBoard.git
cd ShopBoard
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan serve
```

## Features
- 3-column Kanban: Wishlist, Ready to Buy, Completed
- Drag and drop between columns
- Budget aggregator per column (IDR)
- Problem/Benefit justification on each card
- Quick purchase link

## CI/CD
Docker images are auto-published to [GHCR](https://github.com/kelvinzer0/ShopBoard/pkgs/container/shopboard) on every `v*` tag push.

```bash
# Trigger a release
git tag v1.0.0
git push origin v1.0.0
```

## License
MIT
