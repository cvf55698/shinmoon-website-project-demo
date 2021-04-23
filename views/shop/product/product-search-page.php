{{extends file='shop/base-page.php'}}
{{block name=title}}{{php}}$app_config = require CONFIG_PATH."app.php";echo $app_config['site_name'];{{/php}} - 商品搜尋結果{{/block}}
{{block name=head_css}}{{/block}}
{{block name=js}}{{/block}}

{{block name=content_block}}

{{if isset($products)}}
<div style="background-color: white;min-height:500px;width:95%;left:2.5%;border-radius:10px;" class="content-row" >
    
    {{if isset($products)}}
        <div id="commodity-search-header" style="height:50px;top:20px;width:95%;left:2.5%;background-color: #C0C0C0;border-radius:10px;" class="content-row" >
            <h4 style="font-size:24px;position: relative;top:10px;left:20px;">商品 [{{$origin_keyword_html_encode}}] 搜尋結果</h4>
        </div>
        <div style="height:50px;" class="content-row" >

        
        </div>
    {{/if}}


    {{if isset($products)}}
        <div id="commodity-block" style="width:95%;left:2.5%;" class="content-row row" >
            {{foreach $products as $product}}
                <div class="commodity-row-block row content-row">
                    <div class="commodity-row-block-left col-12 col-sm-3 col-xl-2">
                        <a href="/product/{{$product['id']}}">
                            <img class="commodity-row-block-left-img" src="/images/{{$product['main_image']}}" alt=""/>
                        </a>
                    </div>
                    <div style="" class="commodity-row-block-right col-12 col-sm-9 col-xl-10">
                        <div style="height:5px;" class="content-row" ></div>
                        <a href="/product/{{$product['id']}}" class="commodity-row-block-right-name-a" >
                            {{$product['product_name']}}
                        </a>
                        <div style="height:5px;" class="content-row" ></div>
                        <div class="content-row">
                            <span class="commodity-row-block-right-price-span d-inline-block text-truncate">
                                售價 : ${{$product['price']}}
                            </span>
                        </div>
                    </div>
                </div>

            {{/foreach}}
        </div>
    {{/if}}

    {{if isset($page_data)}}
    {{if count($products) neq 0}}
        <div id="commodity-page-tab-block" style="height:50px;top:20px;width:95%;left:2.5%;" class="content-row page-tab-block">
            <nav>
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="/search?keyword={{$origin_keyword}}&page={{$page_data['first_page']}}">第一頁</a></li>
                    {{if $page_data['has_past_page']}}
                        <li class="page-item"><a class="page-link" href="/search?keyword={{$origin_keyword}}&page={{$page_data['past_page']}}">上一頁</a></li>
                    {{/if}}
                </ul>

                <ul class="pagination">
                    {{foreach $page_data['mid_page_arr'] as $mid_page}}
                        {{if $page_data['this_page'] eq $mid_page}}
                            <li class="page-item active"><span class="page-link">{{$mid_page}}</span></li>
                        {{else}}
                            <li class="page-item"><a class="page-link" href="/search?keyword={{$origin_keyword}}&page={{$mid_page}}">{{$mid_page}}</a></li>
                        {{/if}}
                    {{/foreach}}
                </ul>

                <ul class="pagination">
                    {{if $page_data['has_next_page']}}
                        <li class="page-item"><a class="page-link" href="/search?keyword={{$origin_keyword}}&page={{$page_data['next_page']}}">下一頁</a></li>
                    {{/if}}
                    <li class="page-item"><a class="page-link" href="/search?keyword={{$origin_keyword}}&page={{$page_data['last_page']}}">最後一頁</a></li>
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
                商品 [{{$origin_keyword_html_encode}}] 無搜尋結果
            </div>
        {{/if}}
    {{/if}}

</div>
{{/if}}

{{/block}}