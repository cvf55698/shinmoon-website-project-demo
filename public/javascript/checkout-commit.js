(function($,window){
    $("#submit-checkout-form").submit(function(){
        var loading_modal = new bootstrap.Modal(document.getElementById('loading-modal'), {keyboard: false});
        $("#loading-modal h5").html("送出訂單中");
        loading_modal.show();
    })
}($,window))

