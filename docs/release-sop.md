# Release / Sync SOP (audio.nmth.gov.tw)

目標：維持「site → repo → GitHub」單向回填納管，不做 repo→production 自動發佈。

## 1) 在站上（或 staging）修改
- 修改範圍：mu-plugins、自製/客製 plugins、astra-child
- 禁止：把 stg-* 檔案、*.off、*.bak* 混入正式納管

## 2) 回填到 repo（單向同步）
在 repo 執行：

```bash
cd /volume/repos/project-audio.nmth.gov.tw
./scripts/sync-from-site.sh
git status

## 3) Commit / Push
git add -A
git commit -m "chore: sync from site"
git push origin main

## 4) 排除規則（由腳本與 .gitignore 保護）
- mu-plugins：排除 stg-*.php、*.off、STAGING-README.txt、index.html
- plugins：排除 *.off、*.bak*
- 不追蹤：uploads/cache/upgrade、第三方外掛整包、mxp-dev-tools
