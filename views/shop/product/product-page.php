{{extends file='shop/base-page.php'}}
{{block name=title}}{{$product['product_name']}}{{/block}}
{{block name=head_css}}
<link href="/css/product.css" rel="stylesheet" >
{{/block}}
{{block name=js}}
<script type="text/javascript" src="/javascript/product.js"></script>
{{/block}}

{{block name=content_block}}

{{if isset($product)}}
<div id="product-block" class="content-row row"  >

<div class="product-block-left col-12 col-sm-5 col-lg-5 col-xl-4">
    <img id="product-block-img" src="/images/{{$product['main_image']}}" />
</div>


<div class="product-block-right col-12 col-sm-7 col-lg-7 col-xl-8">

    <div class="position: relative;top:10px;width:90%;left:5%;">
        <div style="height: 10px;" class="row content-row">

        </div>

        <div style="background-color: #DCDCDC;padding:5px;padding-left: 15px; " class="content-row">
            <span style="color:black;font-size:20px;">
                {{$product['product_name']}}
            </span>
        </div>

        <div style="height: 10px;" class="row content-row">

        </div>
        
        <div class="product-info-block content-row">
            <div class="product-info-item row content-row">
                <div class="info-th">
                    售價 : 
                </div>
                <div class="info-td">
                    ${{$product['price']}}
                </div>
            </div>
            
            <div class="product-info-item row content-row">
                <div class="info-th">
                    庫存狀態 : 
                </div>
                <div class="info-td inventory-status">
                    {{$inventory_status}}
                </div>
            </div>
        </div>

        <div class="product-action">
            {{if isset($member_has_login)}}
                {{if $member_has_login}}
                    <button id="add-in-cart-button" data-product-id="{{$product['id']}}" data-csrf-token="{{$cart_product_operate_hash}}" class="btn">加入購物車</button>
                {{/if}}
            {{/if}}
        </div>

    </div>
</div>

</div>
{{/if}}
{{/block}}