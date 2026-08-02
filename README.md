# ShopBoard

> Developer-Centric Shopping Kanban - Laravel 11 + Inertia.js + Vue 3

## Tech Stack
- **Backend:** Laravel 11 (SQLite)
- **Frontend:** Vue 3 + Inertia.js
- **Styling:** Tailwind CSS (Dark Mode)
- **Drag and Drop:** Native HTML5 DnD

## Quick Start

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

Open `http://localhost:8000`

## Features
- 3-column Kanban: Wishlist, Ready to Buy, Completed
- Drag and drop between columns
- Budget aggregator per column (IDR)
- Problem/Benefit justification on each card
- Quick purchase link

## License
MIT
