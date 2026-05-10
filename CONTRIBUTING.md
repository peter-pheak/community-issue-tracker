# Contributing to Community Issue Tracker

## Git Workflow

This project uses **feature branches** — one branch per team member. All work merges into `main` via pull requests.

```bash
# 1. Clone the repo
git clone <repo-url>
cd community-issue-tracker

# 2. Create your feature branch
git checkout -b feature/<your-role>

# 3. Work, commit often
git add .
git commit -m "descriptive message"

# 4. Push your branch
git push origin feature/<your-role>

# 5. Open a Pull Request on GitHub targeting main
```

## Branch Convention

```
feature/m1-auth          # M1 — auth, setup, migrations, routes
feature/m2-report        # M2 — report form, IssueRequest, storage
feature/m3-map-list      # M3 — homepage map, issue list, API endpoint
feature/m4-detail        # M4 — issue detail, comments (PR onto M3's file)
feature/m5-admin         # M5 — admin dashboard, issue management
feature/m6-ui-test       # M6 — layout, theme, responsive QA, README
```

## File Ownership

**No two members own the same file simultaneously.**

- M4 adds `show()` to M3's `IssueController.php` via PR only — M3 reviews and merges.
- Merge conflicts must be resolved with the original author present.
- If you need to edit a file owned by another member, open an issue or PR — do not push directly.

## Commit Messages

Keep commits small and descriptive:

```
Good:
  feat: add report form with Leaflet coordinate picker
  fix: add image cleanup before issue deletion
  style: center login card on admin page

Avoid:
  updates
  fixed stuff
  wip
```

## Pull Request Process

1. Push your branch and open a PR on GitHub.
2. Assign the relevant file owner as reviewer.
3. Address any review comments.
4. Once approved, merge into `main`.
5. Delete your feature branch after merge.

## Code Style

- Follow PSR-12 (Laravel defaults).
- Use Laravel Form Requests for all validation.
- Use Blade `{{ }}` for output (never `{!! !!}` without explicit sanitization).
- Always include `@csrf` in forms.
- Use Eloquent relationships and eager loading — avoid N+1 queries.
- Prefer `Route::is()` for active link highlighting.

## Development Environment

```bash
# Required
PHP 8.2+
Composer 2.x
MySQL 8.0+

# Start the dev server
php artisan serve

# Run migrations
php artisan migrate:fresh --seed

# Check routes
php artisan route:list
```

## Testing

Before submitting a PR:

1. Test your feature end-to-end.
2. Verify at 320px, 768px, 1024px, and 1440px viewports.
3. Test form validation (empty fields, invalid values).
4. Confirm CSRF tokens are present.
5. Verify Blade output escaping (XSS prevention).

## Questions?

Contact the project lead (M1) or open a GitHub issue.
