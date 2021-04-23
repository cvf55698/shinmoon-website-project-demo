(function($,window){

    $(".member-cart-product input[type='number']").change(function() {
        
        value = $(this).val();
        try{
            value = parseInt($(this).val());
            if(isNaN(value)){
                return;
            }
        }catch(e){
            return;
        }
        
        var product_id = $(this).data("product-id");
        var csrf_token = $(this).data("csrf-token");
        data = {product_id:product_id,csrf_token:csrf_token,query_count: value};
        $.ajax({
            url:"/cart/edit",
            type: "post",
			data:data,
            dataType:"json",
            success:function(result){
                success = result['success'];
                if(success){
                    location.reload();
                }else{
                    error_message = result['error_message'];
                    if(result['redirect_login']){
                        $.open_message_modal("調整商品數量失敗",error_message,"login");
                    }else{
                        $.open_message_modal("調整商品數量失敗",error_message,"reload");
                    }
                }
            },
            error:function(result){
                $.open_message_modal("加入購物車失敗，錯誤訊息",JSON.stringify(result),"reload");
            },
        });

        var loading_modal = new bootstrap.Modal(document.getElementById('loading-modal'), {keyboard: false});
        loading_modal.show();
    }); 


    $(".member-cart-product .cancel-button").click(function(){
        var product_id = $(this).data("product-id");
        var csrf_token = $(this).data("csrf-token");
        data = {product_id:product_id,csrf_token:csrf_token};
        $.ajax({
            url:"/cart/delete",
            type: "post",
			data:data,
            dataType:"json",
            success:function(result){
                success = result['success'];
                if(success){
                    location.reload();   
                }else{
                    error_message = result['error_message'];
                    if(result['redirect_login']){
                        $.open_message_modal("商品移出購物車，操作失敗",error_message,"login");
                    }else{
                        $.open_message_modal("商品移出購物車，操作失敗",error_message,"reload");
                    }
                }
            },
            error:function(result){
                $.open_message_modal("商品移出購物車，操作失敗",JSON.stringify(result),"reload");
            },
        });

        var loading_modal = new bootstrap.Modal(document.getElementById('loading-modal'), {keyboard: false});
        loading_modal.show();
    })

    $("#start-checkout-button").click(function(){
        location.href = "/orders/new";
    })

}($,window))