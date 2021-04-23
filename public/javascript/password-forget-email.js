(function($,window){
    $("#member-forget-password-email-form").submit(function(){
        var loading_modal = new bootstrap.Modal(document.getElementById('loading-modal'), {keyboard: false});
        $("#loading-modal h5").html("處理中");
        loading_modal.show();
    })
}($,window))