# Release / Sync SOP (Template)

目標：維持「site/stg → repo → GitHub」單向回填納管，不做 repo→production 自動發佈。

## 1) 修改位置
- 修改範圍：mu-plugins、自製/客製 plugins、child theme
- 禁止：stg-*、*.off、*.bak* 混入正式納管

## 2) 回填到 repo
```bash
./scripts/sync-from-site.sh
git status
