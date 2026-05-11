#!/bin/bash
# Helper to run artisan with SQLite extension loaded
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
export PHP_INI_SCAN_DIR="/etc/php/8.3/cli/conf.d:$SCRIPT_DIR"
php artisan "$@"
