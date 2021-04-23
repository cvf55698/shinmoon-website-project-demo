{{extends file='shop/base-page.php'}}
{{block name=title}}{{php}}$app_config = require CONFIG_PATH."app.php";echo $app_config['site_name'];{{/php}} - 訂單列表{{/block}}
{{block name=head_css}}
<link href="/css/member-orders-list.css" rel="stylesheet" >
{{/block}}
{{block name=js}}{{/block}}

{{block name=content_block}}
<div style="background-color: white;min-height:500px;width:95%;left:2.5%;border-radius:10px;" class="content-row" >
    <div id="member-orders-list-header" style="height:50px;top:20px;width:95%;left:2.5%;background-color: #C0C0C0;border-radius:10px;" class="content-row" >
        <h4 style="font-size:24px;position: relative;top:10px;left:20px;">歷史訂單列表</h4>
    </div>
    <div style="height:50px;" class="content-row" >

                                
    </div>

    {{if isset($orders)}}
    {{if count($orders) neq 0}}
    <div id="member-orders-list-block" style="width:95%;left:2.5%;" class="content-row" >
        <table class="table custom-table">
            <thead>
                <tr>
                    <th scope="col">訂單編號</th>
                    <th scope="col">下單時間</th>
                    <th scope="col">總金額</th>
                </tr>
            </thead>
            <tbody>
                {{foreach $orders as $order}}
                    <tr>
                        <th scope="row"><a href="/orders/{{$order['id']}}">{{$order['id']}}</a></th>
                        <td>{{$order['order_time']}}</td>
                        <td>{{$order['total']}}</td>
                    </tr>
                {{/foreach}}
            </tbody>
        </table>
    </div>
    {{/if}}
    {{/if}}

    {{if isset($page_data)}}
    {{if count($orders) neq 0}}
    <div id="member-orders-list-page-tab-block" style="height:50px;top:20px;width:95%;left:2.5%;" class="content-row page-tab-block">
        <nav>
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="/orders?page={{$page_data['first_page']}}">第一頁</a></li>
                {{if $page_data['has_past_page']}}
                    <li class="page-item"><a class="page-link" href="/orders??page={{$page_data['past_page']}}">上一頁</a></li>
                {{/if}}
            </ul>

            <ul class="pagination">
                {{foreach $page_data['mid_page_arr'] as $mid_page}}
                    {{if $page_data['this_page'] eq $mid_page}}
                        <li class="page-item active"><span class="page-link">{{$mid_page}}</span></li>
                    {{else}}
                        <li class="page-item"><a class="page-link" href="/orders?page={{$mid_page}}">{{$mid_page}}</a></li>
                    {{/if}}
                {{/foreach}}
            </ul>

            <ul class="pagination">
                {{if $page_data['has_next_page']}}
                    <li class="page-item"><a class="page-link" href="/orders?page={{$page_data['next_page']}}">下一頁</a></li>
                {{/if}}
                <li class="page-item"><a class="page-link" href="/orders?page={{$page_data['last_page']}}">最後一頁</a></li>
            </ul>
        </nav>
    </div>

    <div id="member-orders-list-page-tab-block-blank" class="content-row page-tab-block-blank" >

                                
    </div>
    {{/if}}
    {{/if}}

    {{if isset($orders)}}
    {{if count($orders) eq 0}}
    <div id="member-orders-list-none-block" style="width:95%;left:2.5%;font-size:20px;" class="alert alert-warning" role="alert">
        您目前尚未有任何訂單
    </div>
    {{/if}}
    {{/if}}

</div>
{{/block}}