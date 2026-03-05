# Managed Scope (audio.nmth.gov.tw)

本 repo 只納管「我們可控/可維護」的程式碼與設定，避免把 WP 核心、uploads、第三方外掛整包混進版本控管。

## Included (tracked)
- mu-plugins/（排除 stg-* 與 *.off）
- themes/astra-child/
- plugins:
  - nmth-http-guard
  - nmth-export-empty-alert-h2
  - custom-elementor-uael-overrides

## Excluded (not tracked)
- WordPress core
- wp-content/uploads/
- wp-content/cache/, upgrade/
- 第三方外掛整包（elementor, loco-translate, redis-cache…等）
- mxp-dev-tools（明確不納管）
- *.off, stg-*.php, *.bak*, *.log, *.zip, *.sql, *.tar*（由 .gitignore 排除）
