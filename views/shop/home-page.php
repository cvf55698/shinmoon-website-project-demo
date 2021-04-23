{{extends file='shop/base-page.php'}}
{{block name=title}}{{php}}$app_config = require CONFIG_PATH."app.php";echo $app_config['site_name'];{{/php}}{{/block}}
{{block name=head_css}}
<link href="/css/rank.css" rel="stylesheet" >
{{/block}}
{{block name=js}}{{/block}}

{{block name=content_block}}
<div style="background-color: white;min-height:700px;width:95%;left:2.5%;border-radius:10px;" class="content-row" >
    <div id="commodity-rank-header" style="height:50px;top:20px;width:95%;left:2.5%;background-color: #C0C0C0;border-radius:10px;" class="content-row" >
        <h4 style="font-size:24px;position: relative;top:10px;left:20px;">熱銷商品排行</h4>
    </div>
    <div style="height:50px;" class="content-row" >
           
    </div>

    {{if isset($products)}}
    {{if count($products) neq 0}}
    <div id="commodity-rank-block" style="width:95%;left:2.5%;" class="content-row" >
        {{foreach $products as $product}}
            <div class="commodity-card-block" >
                <a href="/product/{{$product['id']}}" class="commodity-card-block-img-a">
                    <img class="commodity-card-block-img" src="/images/{{$product['main_image']}}" alt=""/>
                </a>
                <div class="commodity-card-block-price-div">
                    <span class="commodity-card-block-price-span d-inline-block text-truncate">
                        ${{$product['price']}}
                    </span>
                </div>
                <a href="/product/{{$product['id']}}" class="commodity-card-block-name-a">
                    {{$product['product_name']}}
                </a>
            </div>
        {{/foreach}}
    </div>
    {{/if}}
    {{/if}}

</div>
{{/block}}