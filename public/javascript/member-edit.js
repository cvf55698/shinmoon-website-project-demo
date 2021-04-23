(function($,window){
    $("#member-edit-form").submit(function(){
        var loading_modal = new bootstrap.Modal(document.getElementById('loading-modal'), {keyboard: false});
        $("#loading-modal h5").html("更新中");
        loading_modal.show();
    })
}($,window))

