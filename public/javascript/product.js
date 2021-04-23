(function($,window){
    $("#add-in-cart-button").click(function(){
        var product_id = $(this).data("product-id");
        var csrf_token = $(this).data("csrf-token");
        data = {product_id:product_id,csrf_token:csrf_token};
        $.ajax({
            url:"/cart/add ",
            type: "post",
			data:data,
            dataType:"json",
            success:function(result){
                success = result['success'];
                if(success){
                    $.open_message_modal("加入購物車成功","","");
                }else{
                    error_message = result['error_message'];
                    if(result['redirect_login']){
                        $.open_message_modal("加入購物車失敗",error_message,"login");
                    }else{
                        $.open_message_modal("加入購物車失敗",error_message,"reload");
                    }
                }
            },
            error:function(result){
                $.open_message_modal("加入購物車失敗，錯誤訊息",JSON.stringify(result),"reload");
            },
        });
        
    })
}($,window))