# Community Issue Tracker

A dynamic web application where citizens can report local problems — potholes, broken streetlights, waste disposal issues — and view them on an interactive map. Administrators manage all issues through a secure back office.

**Live demo:** `http://localhost:8000`  
**GitHub:** https://github.com/peter-pheak/community-issue-tracker  
**Branch:** `UI-prototypes`

---

## Tech Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| Backend | Laravel | 13.7 |
| Language | PHP | 8.3+ |
| Database | SQLite (default) / MySQL | — |
| ORM | Eloquent | — |
| Templating | Blade | — |
| Frontend CSS | Bootstrap 5 (CDN) | 5.3.3 |
| Map | Leaflet.js (CDN) | 1.9.4 |
| Icons | Bootstrap Icons (CDN) | 1.11.3 |
| Build tool | Vite | 8.x |
| CSS framework | Tailwind CSS | 4.x |
| Package (PHP) | Composer | 2.9+ |
| Package (JS) | npm | 11+ |

---

## Design System

Custom-designed UI prototypes merged into Blade views:

- **Color palette:** Navy (`#0B4F6C`), accent orange (`#E07A5F`), teal (`#2A9D8F`), warm cream background (`#F4F1EA`)
- **Atmospheric background:** Radial glow + 3-layer SVG skyline pattern
- **Transparent navbar:** Blur backdrop, becomes solid on scroll, orange underline animation
- **Hero section:** Gradient card with animated stat counter numbers
- **Category filter chips:** Clickable pill buttons (Road, Lighting, Waste, Other)
- **Status radio filters:** Color-coded Open/In Progress/Resolved
- **Issue cards:** Hover lift effect with accent left border
- **Scroll reveal:** Fade-in animation via IntersectionObserver
- **Footer:** SVG wave divider, mountain skyline, link columns, back-to-top button
- **Form animations:** Shake on validation errors, drag-and-drop file upload with preview
- **Reduced motion:** `prefers-reduced-motion` respected

---

## Features

### Public

- **Interactive Map** — Leaflet with async markers via `/api/issues/map`
- **Issue Cards** — Paginated grid with badges, description, geo-location
- **Filtering** — Client-side search, category chips, status radios
- **Report Form** — Title, description, category chips, coordinate picker map, drag-and-drop image upload, anonymous option
- **Issue Detail** — Full description, photo, mini map, status history timeline, threaded comments with avatar initials
- **Comment Form** — Shake validation, server-side validation

### Admin (`/admin/login` — admin / admin123)

- **Dashboard** — Stat cards, category breakdown, recent issues
- **Issue CRUD** — Edit, delete, status change with history logging
- **Comment Moderation** — Delete individual comments
- **Login Throttling** — 5 attempts/minute
- **Image Cleanup** — Removed on issue deletion

---

## Installation

```bash
# 1. Clone
git clone --branch UI-prototypes https://github.com/peter-pheak/community-issue-tracker.git
cd community-issue-tracker

# 2. PHP dependencies
composer install

# 3. Environment
cp .env.example .env
php artisan key:generate

# 4. Database (SQLite)
touch database/database.sqlite
php artisan migrate --seed

# 5. Storage symlink
php artisan storage:link

# 6. Frontend
npm install
npm run build

# 7. Run
php artisan serve
```

Open **http://localhost:8000**

### Install PHP (Ubuntu)

```bash
apt search php | grep "^php[0-9]"   # Find your version
sudo apt install -y php8.5-cli php8.5-mbstring php8.5-xml \
  php8.5-curl php8.5-gd php8.5-tokenizer php8.5-fileinfo php8.5-sqlite3
```

### Install Composer

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php && php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/local/bin/composer
```

---

## Seed Sample Data

```bash
php artisan tinker
# Then paste:
collect([
    ["title"=>"Pothole on Main Street","description"=>"Large pothole near Main St and Oak Ave.","category"=>"Road","status"=>"Open","latitude"=>11.565,"longitude"=>104.915,"address"=>"Main St & Oak Ave","reported_by"=>"Jane D."],
    ["title"=>"Street Light Out on Elm","description"=>"Street light at Elm and Pine has been out for two weeks.","category"=>"Lighting","status"=>"In Progress","latitude"=>11.558,"longitude"=>104.888,"address"=>"Elm St & Pine Ave","reported_by"=>"Mike K."],
    ["title"=>"Illegal Dumping at Park","description"=>"Construction debris dumped in the park overnight.","category"=>"Waste","status"=>"Resolved","latitude"=>11.572,"longitude"=>104.905,"address"=>"Central Park","reported_by"=>"Sarah L."],
    ["title"=>"Broken Bench in Central Park","description"=>"Bench near the fountain has a broken leg.","category"=>"Other","status"=>"Open","latitude"=>11.571,"longitude"=>104.904,"address"=>"Central Park Fountain","reported_by"=>"Tom R."],
    ["title"=>"Faded Crosswalk Lines","description"=>"Crosswalk lines near school are almost invisible.","category"=>"Road","status"=>"In Progress","latitude"=>11.562,"longitude"=>104.925,"address"=>"Near School","reported_by"=>"Lisa M."],
    ["title"=>"Broken Street Light Fixture","description"=>"Light fixture damaged. Replacement completed.","category"=>"Lighting","status"=>"Resolved","latitude"=>11.555,"longitude"=>104.895,"address"=>"Oak Ave","reported_by"=>"Admin"],
])->each(fn($d)=> \App\Models\Issue::create($d));
```

---

## Routes

| Method | URI | Name | Purpose |
|--------|-----|------|---------|
| GET | `/` | `home` | Homepage with map + cards |
| GET | `/report` | `report.create` | Report form |
| POST | `/report` | `report.store` | Submit issue |
| GET | `/issues/{issue}` | `issues.show` | Issue detail |
| POST | `/issues/{issue}/comments` | `comments.store` | Post comment |
| GET | `/api/issues/map` | `api.issues.map` | Map marker JSON |
| GET | `/admin/login` | `admin.login` | Login page |
| POST | `/admin/login` | `admin.login.submit` | Login (throttled) |
| POST | `/admin/logout` | `admin.logout` | Logout |
| GET | `/admin/dashboard` | `admin.dashboard` | Dashboard |
| GET | `/admin/issues` | `admin.issues.index` | Issues table |
| GET | `/admin/issues/{issue}/edit` | `admin.issues.edit` | Edit form |
| PUT | `/admin/issues/{issue}` | `admin.issues.update` | Save edit |
| DELETE | `/admin/issues/{issue}` | `admin.issues.destroy` | Delete issue |
| DELETE | `/admin/comments/{comment}` | `admin.comments.destroy` | Delete comment |

---

## Common Tasks

```bash
php artisan serve                         # Dev server
php artisan migrate:fresh --seed          # Reset database
php artisan optimize:clear                # Clear cache
npm run build                             # Rebuild frontend
npm run dev                               # Hot reload (Vite)
```

---

## Troubleshooting

| Symptom | Fix |
|---------|-----|
| Form not visible | Add `visible` class alongside `reveal` |
| Stat counters show 0 | Ensure `$stats` is passed in `IssueController@index` |
| Route [issues.map] not defined | Change `route('issues.map')` to `route('home')` in layout |
| PHP package not found | `apt search php` to find correct version |
| HTTP 500 | `tail -50 storage/logs/laravel.log` |
| SQLite driver missing | `sudo apt install -y php8.5-sqlite3` |
| Vite build fails | `rm -rf node_modules && npm install && npm run build` |

---

## Directory Structure

```
app/Http/Controllers/       # IssueController, ReportController, CommentController, LoginController
app/Http/Controllers/Admin/ # DashboardController, IssueController, CommentController
app/Models/                 # Issue, Comment, Admin, StatusHistory
database/migrations/        # 7 table schemas
resources/views/layouts/    # app.blade.php (master layout)
resources/views/            # home.blade.php
resources/views/issues/     # report.blade.php, show.blade.php
resources/views/admin/      # login.blade.php, dashboard.blade.php
resources/views/admin/issues/ # index.blade.php, edit.blade.php
routes/web.php              # All route definitions
ui-prototypes/              # Static HTML design prototypes
```

---

## License

Educational purposes.
