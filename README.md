
# 欣月商城

## 小型購物商城網站作品
## 作者 : Syn Chang


### 作品網站  [連結](https://ec2-13-59-191-77.us-east-2.compute.amazonaws.com/)
### 特色介紹
- 實現路由映射 (Route Mapping) 功能，不依賴現有套件工具
- 基本安全防護，可初步防止常見網路攻擊，例如 CSRF ，SQL Injection，XSS
- 採用 Controller - Service - Repository 架構，使專案較易於維護
- 事務交易採用 Lock 鎖定機制，確保同時間不會有多個事務對同一筆資料行進行修改
- 在資料表重點欄位加上索引 (Index)，加快 SQL 語句查詢速度

### 運行環境要求
- PHP >=  7.0.0
- mysql >= 5.5.0

### 安裝專案步驟
- 選擇指定資料庫，執行 **initdb.sql** ，初始化資料庫
- 執行 composer 指令，安裝所需套件，指令如下

```
composer install
```
- 將 **.env.sample** 檔案改名為 **.env** ，填寫設定
 - 站點網址 URL
 
```
SITE_URL=http://ip:port/
```
 - 資料庫設定

```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shinmoon
DB_USERNAME=
DB_PASSWORD=
```
 - Mail Smtp 設定 (以 Gmail 為例)
```
MAIL_SMTP_HOST=smtp.gmail.com
MAIL_SECURE=tls
MAIL_PORT=587
MAIL_FROM_ADDRESS=example@gmail.com
MAIL_PASSWORD=
MAIL_FROM_NAME=欣月商城

```
 - Facebook 社群登入 (選填，不填寫即不支援社群登入功能)
 
```
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
FACEBOOK_REDIRECT_URI=
```
 - Google 社群登入 (選填，不填寫即不支援社群登入功能)
 
```
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=
```
- 安裝伺服器，網頁根目錄指向 **{project_path}/public**
- 啟動伺服器，瀏覽網址，可開啟專案網站


### 專案目錄架構
- 主要目錄

```
/app - 專案原始碼
/config - 設定檔
/module - 擺放 helper 方法，
/public - 靜態檔案，以及網站入口檔案 index.php
/views - 視圖模板檔案
/tests - PHPUnit 單元測試檔案
```
- 其他重要目錄檔案

```
/app/Http/Controller - 擺放 Controller 檔案
/app/Shinmoon - 擺放四大主要業務模組 的 Service，Repository 檔案
/config/route.php - 路由設定檔案
```


  ### 單元測試
* 執行以下指令，進行單元測試

 ```
composer test
 ```
* 查看 phpunit.xml，PHPUnit 會執行以下目錄內的測試檔案

```
<directory>tests/Service</directory>
<directory>tests/Controller/member</directory>
<directory>tests/Controller/product</directory>
<directory>tests/Controller/cart</directory>
<directory>tests/Controller/order</directory>
```

* 對所有 Service，Controller 方法進行測試

### 資料表 table 介紹
- member : 使用者
- product_type : 商品種類
- product : 商品
- orders : 使用者的每一筆交易
- order_items : 每筆交易中的每個商品
- svg : 接受捐贈發票的社福團體

### 功能介紹
- 安全防護
 - CSRF
   - 在所有重點 POST 請求，加上 csrf_token 參數，進行驗證
   - 設置 Http Header **x-frame-options: DENY** ，防止駭客利用 &lt;iframe&gt; 方式
   竊取網頁內的 csrf_token
 - XSS，SQL Injection
   - 利用檢測輸入參數，HTML 編碼等方式，對參數進行過濾，防止攻擊
 - Cookie
   - 設置 **httponly : true** 參數，讓 Cookie 無法被 JavaScript 進行讀寫修改

- 路由
  - 路由映射 (Route Mapping)
   - 註冊路由設定檔 **/config/route.php** 
   - 舉例說明 : 首頁路由
 
```
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/$/",
        "URL_FUNCTION"=> "Shop.HomeController@home",  
    ],
```
   - 設定說明
     - 支援的 HTTP 方法有 GET。可寫的常數有 **HTTP_METHOD_GET** , **HTTP_METHOD_POST**
     - **URL_PATTERN** 代表接受路由的正則表達式 Regex 字串
     - **URL_FUNCTION** 代表執行此路由的 Controller 方法
	 在這邊為 **App\Http\Controller\Shop\HomeController::home()** 方法

 - 過濾器 (Filter)
   - 主要使用的 Filter : 
     - MemberNotLoginFilter : 只允許未登入使用者通過檢測
	 - MemberHasLoginFilter : 只允許已登入使用者通過檢測
   - 過濾器檔案，放置目錄 : app/Http/Filter 
   - 使用範例
     - 看到 route.php，**/login** 路由設定
     - 設定如下
      
```
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_POST),
        "URL_PATTERN"=> "/^\/login$/",
        "URL_FUNCTION"=> "Shop.MemberController@login",
        "FILTER"=>[
            'MemberNotLoginFilter',
        ],
        "CSRF"=>'member_login',
    ],
	  
```
   - 使用的過濾器有 **MemberNotLoginFilter** ，會將已登入的使用者攔截，不可請求 **/login** 路由
   
- 事務交易保護
 - 鎖定資料行
   - 在每一個 Service 方法，針對會修改到的資料行，先加上排他鎖，利用 **select for update** 指令。確保同一時間，不會有多筆事務，同時修改同一筆資料行
   - 舉例 : 修改 id=1 使用者電話號碼
     - 先利用 **select id from member where id=1 for update** 指令
	 - 鎖定對應到該會員的資料行
 
