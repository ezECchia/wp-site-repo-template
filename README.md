# project-audio.nmth.gov.tw (managed code)

本 repo 用於納管 audio.nmth.gov.tw 站台中「我們可控/可維護」的程式碼與維運文件。

## What is tracked
- mu-plugins/（排除 stg-* 與 *.off）
- themes/astra-child/（含 Dictionary.json，保留追蹤但不顯示 diff）
- plugins/（僅自製/客製：nmth-http-guard、nmth-export-empty-alert-h2、custom-elementor-uael-overrides）
- docs/（盤點、納管範圍、SOP）
- scripts/（同步腳本）

## What is NOT tracked
- WordPress core
- wp-content/uploads/, cache/, upgrade/
- 第三方外掛整包（可重裝）
- mxp-dev-tools（明確不納管）

## Workflow (site → repo → GitHub)
在 repo 執行同步：
```bash
./scripts/sync-from-site.sh
git status
git add -A
git commit -m "chore: sync from site"
git push origin main
