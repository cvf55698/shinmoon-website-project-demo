(function($,window){
    $("#member-edit-email-form").submit(function(){
        var loading_modal = new bootstrap.Modal(document.getElementById('loading-modal'), {keyboard: false});
        $("#loading-modal h5").html("更新中");
        loading_modal.show();
    })
}($,window))
