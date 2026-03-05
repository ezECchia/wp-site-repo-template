# NMTH HTTP Guard

用途：
- 在封網/限制外連環境下，阻擋 WordPress 對外 HTTP(S) 請求（allowlist 以本站/localhost 為主）
- 降低 HTTP timeout，避免外部呼叫造成後台卡住（TTFB 10–15s）

後台呈現策略：
- 平常不干擾後台（不顯示大 notice）
- 只有「真的發生封鎖紀錄」才在 Admin Bar 顯示：HTTP 外連：已封鎖
- 點 Admin Bar 進 plugins.php?nmth_http_guard=1 才能查看最近封鎖清單（預設 <details> 收合）

檔案：
- nmth-http-guard.php
- README.md
- CHANGELOG.md
