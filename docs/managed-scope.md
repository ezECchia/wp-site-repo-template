# Managed Scope (Template)

本 repo 只納管「我們可控/可維護」的程式碼與設定，避免把 WP core、uploads、第三方外掛整包混進版本控管。

## Included (tracked)
- mu-plugins/（自製/客製；staging-only 檔案不要進）
- themes/（child theme / 客製 theme）
- plugins/（自製/客製外掛）
- docs/、scripts/

## Excluded (not tracked)
- WordPress core
- wp-content/uploads/
- wp-content/cache/, upgrade/
- 第三方外掛整包（elementor, loco-translate, redis-cache…等）
- staging-only: stg-*.php, *.off, *.bak*
