(function($,window){
    $("#member-login-form").submit(function(){
        var loading_modal = new bootstrap.Modal(document.getElementById('loading-modal'), {keyboard: false});
        $("#loading-modal h5").html("登入中");
        loading_modal.show();
    })

    $(".oauth-login-button").on('click',function(){
        var loading_modal = new bootstrap.Modal(document.getElementById('loading-modal'), {keyboard: false});
        $("#loading-modal h5").html("登入中");
        loading_modal.show();
    })

}($,window))