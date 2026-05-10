#!/bin/bash
# Helper to run artisan with SQLite extension loaded
PHP_INI_SCAN_DIR="/etc/php/8.3/cli/conf.d:/home/pheak/issue_report/community-issue-tracker" \
php artisan "$@"
