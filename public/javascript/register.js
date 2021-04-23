(function($,window){
    $("#member-register-form").submit(function(){
        var loading_modal = new bootstrap.Modal(document.getElementById('loading-modal'), {keyboard: false});
        $("#loading-modal h5").html("註冊中");
        loading_modal.show();
    })
}($,window))