{{extends file='shop/base-page.php'}}
{{block name=title}}{{php}}$app_config = require CONFIG_PATH."app.php";echo $app_config['site_name'];{{/php}}{{/block}}
{{block name=head_css}}
<link href="/css/checkout-commit.css" rel="stylesheet" >
{{/block}}
{{block name=js}}{{/block}}

{{block name=content_block}}
{{if isset($order_items)}}
<div style="background-color: white;min-height:500px;width:95%;left:2.5%;border-radius:10px;" class="content-row" >
    <div id="member-order-header" style="height:50px;top:20px;width:95%;left:2.5%;background-color: #C0C0C0;border-radius:10px;" class="content-row" >
        <h4 style="font-size:24px;position: relative;top:10px;left:20px;">訂單內容</h4>
    </div>
    <div style="height:50px;" class="content-row" >
                                
    </div>


    <div id="member-order-block" style="width:95%;left:2.5%;" class="content-row" >
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
                <div class="info-th  "><span>下訂時間</span></div>
                <div class="info-td  "><span>{{$order['order_time']}}</span></div>
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

    </div>

</div>
{{/if}}
{{/block}}