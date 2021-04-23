{{extends file='shop/base-page.php'}}
{{block name=title}}{{php}}$app_config = require CONFIG_PATH."app.php";echo $app_config['site_name'];{{/php}} - 訂單送出失敗{{/block}}
{{block name=head_css}}
<link href="/css/checkout-commit.css" rel="stylesheet" >
{{/block}}
{{block name=js}}{{/block}}

{{block name=content_block}}
<div style="background-color: white;min-height:500px;width:95%;left:2.5%;border-radius:10px;" class="content-row" >
    <div id="member-checkout-commit-header" style="height:50px;top:20px;width:95%;left:2.5%;background-color: #C0C0C0;border-radius:10px;" class="content-row" >
        <h4 style="font-size:24px;position: relative;top:10px;left:20px;">訂單送出失敗</h4>
    </div>
    <div style="height:50px;" class="content-row" >
                                
    </div>
    {{foreach $submit_fail_error_message_arr as $submit_fail_error_message}}
    <div style="top:0px;width:95%;left:2.5%;border-radius:10px;" class="alert alert-danger" role="alert">
        {{$submit_fail_error_message}}
    </div>
    {{/foreach}}
</div>
{{/block}}