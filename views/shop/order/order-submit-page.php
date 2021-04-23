{{extends file='shop/base-page.php'}}
{{block name=title}}{{php}}$app_config = require CONFIG_PATH."app.php";echo $app_config['site_name'];{{/php}} - 訂單確認{{/block}}
{{block name=head_css}}
<link href="/css/checkout-commit.css" rel="stylesheet" >
{{/block}}
{{block name=js}}
<script type="text/javascript" src="/javascript/checkout-commit.js"></script>
{{/block}}

{{block name=content_block}}
<div style="background-color: white;min-height:500px;width:95%;left:2.5%;border-radius:10px;" class="content-row" >
    <div id="member-checkout-commit-header" style="height:50px;top:20px;width:95%;left:2.5%;background-color: #C0C0C0;border-radius:10px;" class="content-row" >
        <h4 style="font-size:24px;position: relative;top:10px;left:20px;">結帳前最後確認</h4>
    </div>
    <div style="height:50px;" class="content-row" >
                                
    </div>

    {{if isset($order_items)}}
    <div id="member-checkout-commit-block" style="width:95%;left:2.5%;" class="content-row" >
        <div style="height:35px;background-color: #DCDCDC;border-radius: 10px;" class="row content-row" >
            <span style="font-size: 20px;position: relative;top:4px;" >購物明細</span>
        </div>

        <table class="order-details-table table custom-table">
            <thead>
                <tr>
                    <th scope="col">商品名稱</th>
                    <th scope="col">單價</th>
                    <th scope="col">數量</th>
                    <th scope="col">小計</th>
                </tr>
            </thead>
            <tbody>
                {{foreach $order_items as $order_item}}
                    <tr class="member-cart-product">
                        <th class="name" data-product-id="{{$order_item['product_id']}}" scope="row">
                            <a href="/product/{{$order_item['product_id']}}">{{$order_item['product_name']}}</a>
                        </th>
                        <td class="price"><span class="member-cart-product-td-span-xs">單價 : </span>{{$order_item['unit_price']}}</td>
                        <td class="quantity"><span class="member-cart-product-td-span-xs">數量 : </span>{{$order_item['quantity']}}</td>
                        <td class="price-all"><span class="member-cart-product-td-span-xs">小計 : </span>{{$order_item['subtotal']}}</td>
                    </tr>
                {{/foreach}}

                <tr class="member-cart-all">
                    <th>運費</th>
                    <td></td>
                    <td></td>
                    <td>{{$order['shipping_fee']}}</td>
                </tr>
                <tr class="member-cart-all">
                    <th>總計</th>
                    <td></td>
                    <td></td>
                    <td>${{$order['total']}}</td>
                </tr>
            </tbody>
        </table>

        <div style="height:30px;" class="content-row" >
                                
        </div>


        <div style="height:35px;background-color: #DCDCDC;border-radius: 10px;" class="row content-row" >
            <span style="font-size: 20px;position: relative;top:4px;" >付款資訊</span>
        </div>

        <div id="checkout-info-commit-table" class="content-row">
            <div class="content-row row">
                <div class="info-th  "><span>訂單編號</span></div>
                <div class="info-td  "><span>{{$order['id']}}</span></div>
            </div>

            <div class="content-row row">
                <div class="info-th  "><span>取貨人</span></div>
                <div class="info-td  "><span>{{$order['recipient_name']}}</span></div>
            </div>

            <div class="content-row row">
                <div class="info-th  "><span>取貨人 聯絡電話</span></div>
                <div class="info-td  "><span>{{$order['recipient_telephone_number']}}</span></div>
            </div>

            <div class="content-row row">
                <div class="info-th  "><span>發票類型</span></div>
                <div class="info-td  "><span>{{$invoice_info}}</span></div>
            </div>
        </div>

        <div style="height:30px;" class="content-row" >
                                
        </div>

        <div style="height:50px;" class="content-row" >
            <form id="submit-checkout-form" method="post" enctype="multipart/form-data" action="/orders/submit">
                <input name="csrf_token" type="hidden" value="{{php}}echo App\Http\Csrf\CsrfUtility::generate_token('member');{{/php}}" />
                <input id="checkout-commit-button" name="submit" type="submit" class="btn btn-primary" value="確認無誤結帳"/>
            </form>
        </div>

        <div style="height:30px;" class="content-row" >
                                
        </div>




    </div>
    {{/if}}
</div>
{{/block}}