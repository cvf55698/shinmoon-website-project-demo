{{extends file='shop/base-page.php'}}
{{block name=title}}{{php}}$app_config = require CONFIG_PATH."app.php";echo $app_config['site_name'];{{/php}} - 設定結帳資訊{{/block}}
{{block name=head_css}}
<link href="/css/checkout-setting.css" rel="stylesheet" >
{{/block}}
{{block name=js}}
<script src="/javascript/checkout-setting.js"> </script>
{{/block}}

{{block name=content_block}}
<div style="background-color: white;min-height:500px;width:95%;left:2.5%;border-radius:10px;" class="content-row" >
    <div id="member-checkout-setting-header" style="height:50px;top:20px;width:95%;left:2.5%;background-color: #C0C0C0;border-radius:10px;" class="content-row" >
        <h4 style="font-size:24px;position: relative;top:10px;left:20px;">設定結帳資訊</h4>
    </div>
    <div style="height:50px;" class="content-row" >
                                
    </div>
    {{if isset($order_items)}}
    <div id="member-checkout-setting-block" style="width:95%;left:2.5%;" class="content-row" >
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

        <div style="height:10px;" class="content-row" >
                                
        </div>

        <form id="checkout-setting-form" method="post" enctype="multipart/form-data" action="/orders/new">
            <div style="display: none;" class="mb-3 row">
                <label class="col-12 col-sm-2 col-form-label">CSRF</label>
                <div class="col-12 col-sm-10">
                    <input type="hidden" name="csrf_token" class="form-control" value="{{php}}echo App\Http\Csrf\CsrfUtility::generate_token('member');{{/php}}">
                </div>
            </div>

            <div style="height:35px;background-color: #DCDCDC;border-radius: 10px;" class="row content-row" >
                <span style="font-size: 20px;position: relative;top:4px;" >收件人資訊</span>
            </div>

            <div style="height:10px;" class="content-row" >
                                
            </div>

            <div style="width:95%;left:2.5%;" class="content-row" >
                <div style="height:60px;top:-5px;" class="mb-3 row content-row">
                    <label class="col-12 col-form-label">收件人 姓名</label>
                    <div class="col-12">
                        <input type="text" name="name" style="max-width: 300px;" class="form-control" placeholder="">
                    </div>
                </div>

                <div style="height:60px;top:-5px;" class="mb-3 row content-row">
                    <label class="col-12 col-form-label">收件人 電話</label>
                    <div class="col-12">
                        <input type="text" name="telephone_number" style="max-width: 300px;" class="form-control" placeholder="範例 : 0912345678">
                    </div>
                </div>
            </div>

            <div style="height:10px;" class="content-row" >
                                
            </div>

            <div style="height:35px;background-color: #DCDCDC;border-radius: 10px;" class="row content-row" >
                <span style="font-size: 20px;position: relative;top:4px;" >發票資訊</span>
            </div>

            <div style="width:95%;left:2.5%;" class="content-row" >

                <div style="height:10px;" class="content-row" ></div>

                <div id="select-invoice-type-block">
                    <span>發票類型</span>
                    <div style="height:5px;" class="content-row" ></div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="invoice_type" value="donate" checked="checked">
                        <label class="form-check-label">
                            捐贈發票
                        </label>
                    </div>
                    <div style="height:5px;" class="content-row" ></div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="invoice_type" value="second_invoice">
                        <label class="form-check-label">
                            二聯電子發票
                        </label>
                    </div>
                </div>

                <div style="height:20px;" class="content-row" ></div>

                <div id="select-invoice-type-donate">
                    捐贈發票
                    <div style="height:10px;" class="content-row" ></div>
                    <div class="form-check">
                        <input style="" class="form-check-input" type="radio" name="donate_invoice_type" value="1" checked="checked">
                        <label style="" class="form-check-label">
                            捐贈單位
                        </label>
                        <select style="max-width:400px;font-size: 15px;position: relative;top:10px;" class="form-select" name="choose_donate">
                            <option selected value="25885:財團法人伊甸社會福利基金會">財團法人伊甸社會福利基金會</option>
                            <option value="583:財團法人心路社會福利基金會">財團法人心路社會福利基金會</option>
                        </select>
                    </div>
                    <div style="height:30px;" class="content-row" ></div>
                    <div class="form-check">
                        <input style="" class="form-check-input" type="radio" name="donate_invoice_type" value="2">
                        <label style="" class="form-check-label">
                            其他社福團體&nbsp;(限數字)&nbsp;&nbsp;<a style="text-decoration: none;" href="https://www.einvoice.nat.gov.tw/APCONSUMER/BTC603W/" target="_blank">愛心碼查詢</a>
                        </label>
                                                
                        <input style="max-width:300px;font-size: 15px;position: relative;top:10px;" type="text" class="form-control" name="other_donate" />
                    </div>
                </div>

                                        
                <div id="select-invoice-type-second">
                    載具類型
                    <div style="height:10px;" class="content-row" ></div>
                    <div class="form-check">
                        <input style="" class="form-check-input" type="radio" name="second_invoice_type" value="1" checked="checked">
                        <label style="" class="form-check-label">
                            {{php}}$app_config = require CONFIG_PATH."app.php";echo $app_config['site_name'];{{/php}}會員載具
                        </label>
                    </div>
                    <div class="form-check">
                        <input style="" class="form-check-input" type="radio" name="second_invoice_type" value="2">
                        <label style="" class="form-check-label">
                            手機條碼載具 (限大寫英數字)
                        </label>
                        <label style="" class="form-check-label">
                            <a style="text-decoration: none;" href="https://www.einvoice.nat.gov.tw/APCONSUMER/BTC500W/"  target="_blank">手機條碼使用說明</a>
                        </label>

                        <input style="max-width:300px;font-size: 15px;position: relative;top:10px;" type="text" class="form-control" name="member_carrier" placeholder="請輸入手機條碼"/>
                    </div>
                </div>
                <div style="height:40px;" class="content-row" ></div>
            </div>


            <div style="width:95%;left:2.5%;height:50px;" class="row content-row" >
                <div class="col-12 col-sm-6">
                     <button id="back-cart-button" style="background-color: #DCDCDC;" type="button" class="btn">&laquo;&nbsp;&nbsp;修改購物車內容</button>
                </div>

                <div class="col-12 col-sm-6">
                    <button id="go-on-checkout-button" style="background-color: #4169E1;color:white;" type="button" class="btn">進行結帳&nbsp;&nbsp;&raquo;</button> 
                </div>
            </div>
                                    
            <div style="height:100px;z-index:-100;" class="content-row" ></div>        
        </form>

    </div>
    {{/if}}
</div>
{{/block}}
