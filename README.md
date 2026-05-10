# Community Issue Tracker

A dynamic web application where citizens can report local problems (potholes, broken streetlights, waste disposal issues) and view them on an interactive map. Administrators manage all issues through a secure back office.

## Team

| Member | Name | Role |
|--------|------|------|
| M1 | ... | Project Lead & Auth |
| M2 | ... | Report Issue (Front Office) |
| M3 | ... | Public Map & Issue List |
| M4 | ... | Issue Detail & Comments |
| M5 | ... | Admin Dashboard & Issue Management |
| M6 | ... | UI/UX, Master Layout & Testing |

## Tech Stack

- **Backend:** Laravel 11 (PHP 8.2+)
- **Database:** MySQL
- **ORM:** Eloquent
- **Templating:** Blade
- **Frontend:** Bootstrap 5 (CDN)
- **Map:** Leaflet.js (CDN)
- **Icons:** Bootstrap Icons
- **Version Control:** Git / GitHub (feature branches)

## Features

- **Public Map** — issues displayed on an interactive Leaflet map loaded asynchronously via `/api/issues/map`
- **Report Issues** — form with coordinate picker, image upload, and full validation
- **Issue Detail** — full description, status history timeline, and threaded comments
- **Admin Dashboard** — stat cards, category breakdown, and recent issues table
- **Admin CRUD** — edit issues, change status (with history logging), delete issues (with image cleanup)
- **Comment Moderation** — admins can delete individual comments
- **Login Throttling** — admin login limited to 5 attempts per minute
- **Responsive Design** — works at 320px, 768px, 1024px, and 1440px viewports

## Installation

```bash
# 1. Clone the repository
git clone <repo-url>
cd community-issue-tracker

# 2. Install dependencies
composer install

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. The project uses SQLite by default (no MySQL setup needed).
#    Database file: database/database.sqlite

# 5. If PHP SQLite extension is missing, install it:
#    Ubuntu/Debian: sudo apt install php8.3-sqlite3
#    macOS:          brew install php (includes SQLite)
#    Or use the included helper that loads the extension:
#    Run all artisan commands via: ./artisan.sh <command>

# 6. Run migrations and seed
./artisan.sh migrate --seed

# 7. Create storage symlink (required for image uploads)
./artisan.sh storage:link

# 8. Start the development server
./artisan.sh serve
```

## Admin Credentials (demo)

```
URL:      http://localhost:8000/admin/login
Username: admin
Password: admin123
```

## Directory Structure (key files)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── CommentController.php    # M5 — admin comment moderation
│   │   │   ├── DashboardController.php  # M5 — admin dashboard stats
│   │   │   └── IssueController.php      # M5 — admin issue CRUD
│   │   ├── CommentController.php        # M4 — public comment posting
│   │   ├── IssueController.php          # M3 + M4 — index, mapData, show
│   │   ├── LoginController.php          # M1 — admin auth
│   │   └── ReportController.php         # M2 — issue reporting
│   └── Requests/
│       └── IssueRequest.php             # M2 — form validation
├── Models/
│   ├── Admin.php
│   ├── Comment.php
│   ├── Issue.php
│   └── StatusHistory.php
database/
├── migrations/
│   ├── ..._create_admins_table.php
│   ├── ..._create_issues_table.php       # DECIMAL(10,8) / DECIMAL(11,8)
│   ├── ..._create_status_history_table.php
│   └── ..._create_comments_table.php
└── seeders/
    └── AdminSeeder.php
resources/views/
├── layouts/
│   └── app.blade.php                    # M6 — master layout
├── home.blade.php                       # M3 — map + card list
├── issues/
│   ├── report.blade.php                 # M2 — report form
│   └── show.blade.php                   # M4 — issue detail
└── admin/
    ├── login.blade.php                  # M6 — admin login page
    ├── dashboard.blade.php              # M5 — admin dashboard
    └── issues/
        ├── index.blade.php              # M5 — manage issues table
        └── edit.blade.php               # M5 — edit issue form
routes/
└── web.php                              # M1 — route definitions
```

## Submission Checklist

- [x] GitHub repository with clean commit history
- [x] README.md with project overview, team, tech stack, install steps
- [x] `php artisan storage:link` documented
- [x] Admin login with throttle middleware
- [x] Map loads asynchronously via `/api/issues/map`
- [x] `latitude` / `longitude` columns are DECIMAL, not FLOAT
- [x] Composite index on `['status', 'category']`
- [x] CSRF token on every form (`@csrf`)
- [x] Image file cleanup on issue deletion
- [x] Responsive at 320px, 768px, 1024px, 1440px
- [x] Input validation on every form (Form Request + HTML5)

## License

This project is created for educational purposes.
