{{extends file='shop/base-page.php'}}
{{block name=title}}{{php}}$app_config = require CONFIG_PATH."app.php";echo $app_config['site_name'];{{/php}} - 會員修改 Email{{/block}}
{{block name=head_css}}{{/block}}
{{block name=js}}
<script type="text/javascript" src="/javascript/email-reset.js"></script>
{{/block}}

{{block name=content_block}}

<div id="content-block"  class="content-row">
    <div style="background-color: white;min-height:500px;width:95%;left:2.5%;border-radius:10px;" class="content-row" >

        <div style="height:50px;top:20px;width:95%;left:2.5%;background-color: #C0C0C0;border-radius:10px;" class="content-row" >
            <h4 style="font-size:24px;position: relative;top:10px;left:20px;">修改 Email</h4>
        </div>
        <div style="height:50px;" class="content-row" >

                                
        </div>

        <div id="member-edit-email-form-block" style="width:95%;left:2.5%;" class="content-row" >
            <form id="member-edit-email-form" class="member-form" method="post" enctype="multipart/form-data" action="/email/edit">
                <div style="display: none;" class="mb-3 row">
                    <label class="col-12 col-sm-2 col-form-label">CSRF</label>
                    <div class="col-12 col-sm-10">
                        <input type="hidden" name="csrf_token" class="form-control" value="{{php}}echo App\Http\Csrf\CsrfUtility::generate_token('member');{{/php}}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-12 col-sm-2 col-form-label">Email</label>
                    <div class="col-12 col-sm-10">
                        <input type="text" name="email" class="form-control" value="">
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-12 col-sm-auto">
                        <input type="submit" name="submit" style="min-width:200px;" class="btn btn-primary mb-3" value="更新">
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

{{/block}}