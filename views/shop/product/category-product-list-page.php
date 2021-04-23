{{extends file='shop/base-page.php'}}
{{block name=title}}{{php}}$app_config = require CONFIG_PATH."app.php";echo $app_config['site_name'];{{/php}} - {{$category_name}}{{/block}}
{{block name=head_css}}{{/block}}
{{block name=js}}{{/block}}

{{block name=content_block}}


<div id="content-block"  class="content-row">

{{if isset($products)}}
<div style="background-color: white;min-height:500px;width:95%;left:2.5%;border-radius:10px;" class="content-row" >
    <div id="commodity-category-header" style="height:50px;top:20px;width:95%;left:2.5%;background-color: #C0C0C0;border-radius:10px;" class="content-row" >
        <h4 style="font-size:24px;position: relative;top:10px;left:20px;">{{$category_name}}</h4>
    </div>
    <div style="height:50px;" class="content-row" >

        
    </div>

    {{if isset($products)}}
    {{if count($products) neq 0}}
    <div id="commodity-block" style="width:95%;left:2.5%;" class="content-row" >
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
    
    {{if isset($page_data)}}
    {{if count($products) neq 0}}
        <div id="commodity-page-tab-block" style="height:50px;top:20px;width:95%;left:2.5%;" class="content-row page-tab-block">
            <nav>
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="/product/category/{{$product_category_id}}?page={{$page_data['first_page']}}">第一頁</a></li>
                    {{if $page_data['has_past_page']}}
                        <li class="page-item"><a class="page-link" href="/product/category/{{$product_category_id}}?page={{$page_data['past_page']}}">上一頁</a></li>
                    {{/if}}
                </ul>

                <ul class="pagination">
                    {{foreach $page_data['mid_page_arr'] as $mid_page}}
                        {{if $page_data['this_page'] eq $mid_page}}
                            <li class="page-item active"><span class="page-link">{{$mid_page}}</span></li>
                        {{else}}
                            <li class="page-item"><a class="page-link" href="/product/category/{{$product_category_id}}?page={{$mid_page}}">{{$mid_page}}</a></li>
                        {{/if}}
                    {{/foreach}}
                </ul>

                <ul class="pagination">
                    {{if $page_data['has_next_page']}}
                        <li class="page-item"><a class="page-link" href="/product/category/{{$product_category_id}}?page={{$page_data['next_page']}}">下一頁</a></li>
                    {{/if}}
                    <li class="page-item"><a class="page-link" href="/product/category/{{$product_category_id}}?page={{$page_data['last_page']}}">最後一頁</a></li>
                </ul>
            </nav>
            
        </div>

        <div id="commodity-page-tab-block-blank" class="content-row page-tab-block-blank" >

    
        </div>
    {{/if}}
    {{/if}}

    {{if isset($products)}}
    
        {{if count($products) eq 0}}
        <div id="commodity-none-block" style="width:95%;left:2.5%;font-size:20px;" class="alert alert-danger" role="alert">
            目前暫無 [{{$category_name}}] 種類商品
        </div>
        {{/if}}
    {{/if}}

</div>
{{/if}}
{{/block}}