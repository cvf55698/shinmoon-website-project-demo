{{extends file='shop/base-page.php'}}
{{block name=title}}{{php}}$app_config = require CONFIG_PATH."app.php";echo $app_config['site_name'];{{/php}} - 會員登入{{/block}}
{{block name=head_css}}{{/block}}
{{block name=js}}
<script type="text/javascript" src="/javascript/login.js"></script>
{{/block}}

{{block name=content_block}}

<div style="background-color: white;min-height:500px;width:95%;left:2.5%;border-radius:10px;" class="content-row" >

<div style="height:50px;top:20px;width:95%;left:2.5%;background-color: #C0C0C0;border-radius:10px;" class="content-row" >
    <h4 style="font-size:24px;position: relative;top:10px;left:20px;">會員登入</h4>
</div>
<div style="height:50px;" class="content-row" >

    
</div>

<div id="member-login-form-block" style="width:95%;left:2.5%;" class="content-row" >
    <form id="member-login-form" class="member-form" method="post" enctype="multipart/form-data" action="/login">
        <div style="display: none;" class="mb-3 row">
            <label class="col-12 col-sm-2 col-form-label">CSRF</label>
            <div class="col-12 col-sm-10">
                <input type="hidden" name="csrf_token" class="form-control" value="{{php}}echo App\Http\Csrf\CsrfUtility::generate_token('member_login');{{/php}}">
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-12 col-sm-2 col-form-label">帳號 或 Email</label>
            <div class="col-12 col-sm-10">
                <input type="text" name="account_or_email" class="form-control" value="">
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-12 col-sm-2 col-form-label">密碼</label>
            <div class="col-12 col-sm-10">
                <input type="password" name="password" class="form-control">
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-12 col-sm-auto">
                <input type="submit" name="submit" style="min-width:200px;" class="btn btn-primary mb-3" value="登入">
            </div>

            <div class="col-12">
                <a href="/password/new" style="font-size:17px;text-decoration: none;">忘記密碼</a>
            </div>
        </div>
    </form>

   <hr class="simple" color="#6f5499" />

   <div class="row content-row oauth-login-form">
        <div style="margin-top:10px;margin-bottom: 10px;" class="col-12 col-sm-6 col-xxl-4">
            <a href=
            "{{php}}
                $facebook_provider = new App\Oauth\FacebookProvider();
                echo $facebook_provider->get_auth_url();
            {{/php}}" type="button" role="button" style="background-color: #4169E1;color:white;width:100%;" class="btn oauth-login-button">
                <i class="fab fa-facebook-square"></i>
                <span>&nbsp;&nbsp;使用 Facebook 登入</span>
            </a>
        </div>
        <div style="margin-top:10px;margin-bottom: 10px;" class="col-12 col-sm-6 col-xxl-4">
            <a href=
            "{{php}}
                $google_provider = new App\Oauth\GoogleProvider();
                echo $google_provider->get_auth_url();
            {{/php}}" type="button" role="button" style="background-color: #FF8000;color:white;width:100%;" class="btn oauth-login-button">
                <i class="fab fa-google"></i>
                <span>&nbsp;&nbsp;使用 Google 登入</span>
            </a>
        </div>
   </div>

</div>

<div style="height:50px;" class="content-row" ></div>
</div>
{{/block}}