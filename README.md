# smart-switch

使用前請確認根目錄底下有 .env 檔案並設定好才可連接資料庫
DB_HOST=localhost
DB_NAME=sample
DB_USER=sample
DB_PASS=pw#123456

所有頁面都將經由入口 index.php 進入
後端請求皆於 action.php
其餘頁面每個 .php 檔案都將對應一個 .html 檔案

.html 皆置於 `html/`
.js 皆置於 `js/`
資料庫請求皆置於 `lib/db_func.php`
排程腳本皆置於 `cron/`