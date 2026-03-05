# Deploy Safety Checklist (audio.nmth.gov.tw)

在任何 production 同步 / 打包 / rsync 前必須確認：

## 1. staging-only files
以下檔案 **不能進 production**

- stg-*.php
- *.off
- *.bak*

## 2. staging MU plugins
確認 mu-plugins 目錄中沒有：

- stg-*.php
- debug / test / temporary scripts

## 3. robots / index
Production 必須確認：

- robots.txt 正常
- 沒有 stg-robots-noindex

## 4. WordPress debug
Production 必須確認：

- WP_DEBUG = false

## 5. Redis / cache
Production 必須確認：

- redis-cache 正常
- cache 不包含 staging keys

## 6. external HTTP blocking
NMTH HTTP Guard 若為 production：

- 必須確認 allowlist hosts
- 不要誤擋 production API

## Deploy command (example)

rsync example：

rsync -av --delete \
  --exclude="*.off" \
  --exclude="*.bak*" \
  --exclude="stg-*.php" \
  ./site/ server:/var/www/site/

