{{extends file='shop/base-page.php'}}
{{block name=title}}{{php}}$app_config = require CONFIG_PATH."app.php";echo $app_config['site_name'];{{/php}} - 編輯購物車{{/block}}
{{block name=head_css}}
<link href="/css/cart.css" rel="stylesheet" >
{{/block}}
{{block name=js}}
<script type="text/javascript" src="/javascript/cart.js"></script>
{{/block}}

{{block name=content_block}}
<div style="background-color: white;min-height:500px;width:95%;left:2.5%;border-radius:10px;" class="content-row" >
    <div id="member-cart-header" style="height:50px;top:20px;width:95%;left:2.5%;background-color: #C0C0C0;border-radius:10px;" class="content-row" >
        <h4 style="font-size:24px;position: relative;top:10px;left:20px;">訂單明細</h4>
    </div>
    <div style="height:50px;" class="content-row" >

    </div>


    {{if isset($order_items)}}
    {{if count($order_items) neq 0}}
    <div id="member-cart-list-block" style="width:95%;left:2.5%;" class="content-row" >
        <table id="member-cart-list-table" class="table custom-table">
            <thead>
                <tr>
                    <th scope="col">商品名稱</th>
                    <th scope="col">單價</th>
                    <th scope="col">數量</th>
                    <th scope="col">小計</th>
                    <th scope="col">動作</th>
                </tr>
            </thead>

            <tbody>
                {{foreach $order_items as $order_item}}
                    <tr class="member-cart-product">
                        <th class="name" data-product-id="{{$order_item['product_id']}}" scope="row">
                            <img class="member-cart-product-img" src="/images/{{$order_item['main_image']}}" alt="xxx"/>
                            <a href="/product/{{$order_item['product_id']}}">{{$order_item['product_name']}}</a>
                        </th>
                        <td class="price"><span class="member-cart-product-td-span-xs">單價 : </span>{{$order_item['unit_price']}}</td>
                        <td class="quantity">
                            <span class="member-cart-product-td-span-xs">數量 : </span>
                            <input type="number" value="{{$order_item['quantity']}}" data-product-id="{{$order_item['product_id']}}" 
                                data-csrf-token="{{$order_item['csrf_token']}}" required pattern="\d+" min="1"/>
                        </td>
                        <td class="price-all"><span class="member-cart-product-td-span-xs">小計 : </span>{{$order_item['subtotal']}}</td>
                        <td class="action">
                            <button data-product-id="{{$order_item['product_id']}}" data-csrf-token="{{$order_item['csrf_token']}}" class="cancel-button btn">取消</button>
                        </td>
                    </tr>
                {{/foreach}}

                <tr class="member-cart-all">
                    <th>總計</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>${{$total}}</td>
                </tr>

            </tbody>
        </table>
    </div>
    

    <div style="height: 50px;" class="row content-row">

    </div>

    <div style="height: 50px;" class="row content-row">
        <button id="start-checkout-button" type="button" class="btn btn-primary">進行結帳</button>
    </div>
                            
    <div style="height: 50px;" class="row content-row">

    </div>
    {{/if}}
    {{/if}}
    
    
    {{if isset($order_items)}}
    {{if count($order_items) eq 0}}
    <div id="member-cart-none-block" style="width:95%;left:2.5%;font-size:20px;" class="alert alert-warning" role="alert">
        您目前尚未將任何商品加入購物車
    </div>
    {{/if}}
    {{/if}}
    

</div>
{{/block}}