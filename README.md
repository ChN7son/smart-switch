# smart-switch

## path
使用前請確認根目錄底下有 .env 檔案並設定好才可連接資料庫<br>
DB_HOST=localhost<br>
DB_NAME=sample<br>
DB_USER=sample<br>
DB_PASS=pw#123456<br>

## MVC
所有頁面都將經由入口 `index.php` 進入<br>
後端請求皆於 `action.php`<br>
其餘頁面每個 .php 檔案都將對應一個 .html 檔案<br>

- .html 皆置於 `html/`
- .js 皆置於 `js/`
- 資料庫請求皆置於 `lib/db_func.php`
- 排程腳本皆置於 `cron/`

## DB

### cron

| Column | Type | NULL | Comment |
| --- | --- | --- | --- |
| id | integer | |  | Primary key |
| time | time(0) without time zone | V |  |  |
| dev | integer | V |  | References device(id) |
| control | integer | V |  |  |
| daily | character(13) | V |  |  |
| repeat | smallint | V |  |  |

### device

| Column | Type | NULL | Comment |
| --- | --- | --- | --- |
| id | integer |  | Primary key |
| host | character(8) | V | References host(id) |
| devname | character(20) | V |  |
| type | integer | V |  |
| state | integer | V |  |
| access | integer | V |  |
| price | integer | V |  |
| ip | integer | V |  |
| w | integer | V |  |
| top | integer | V |  |

### host

| Column | Type | NULL | Comment |
| --- | --- | --- | --- |
| id | character(8) |  | Primary key |
| ide | character(40) | V | Unique |
| manpwd | character(20) |  |  |
| vpnpwd | character(20) | V |  |
| clientip | character(15) | V |  |
| cday | timestamp without time zone |  | Default now() |
| atime | integer | V |  |
| dname | character(20) | V |  |
| lineid | character varying(50) | V |  |
| linekey | character varying(50) | V |  |
| remark | text | V |  |


### log

| Column | Type | NULL | Comment |
| --- | --- | --- | --- |
| id | integer |  | Primary key |
| devname | character(20) | V |  |
| control | integer | V |  |
| by | character(20) | V |  |
| how | character(10) | V |  |
| pay | integer | V |  |
| time | timestamp without time zone |  | Default: now() |
| w | integer | V |  |

### userdb

| Column | Type | NULL | Comment |
| --- | --- | --- | --- |
| id | integer |  | Primary key |
| number | character(20) | V |  |
| name | character(20) | V |  |
| level | integer | V |  |
| image | text | V |  |
| correct | integer | V |  |
