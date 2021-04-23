(function($,window){
    var message_modal_id = 'message-modal';
    var hidden_action = "";
    document.getElementById(message_modal_id).addEventListener("hidden.bs.modal", function(){
        if(hidden_action=="reload"){
            location.reload();
        }else if(hidden_action=="login"){
            location.href="/login";
        }
    });


    $.open_message_modal = function(title,content,action_option){
        $("#"+message_modal_id+" .modal-title").html(title);
        $("#"+message_modal_id+" .modal-body p").html(content);
        hidden_action = action_option;
        var message_modal = new bootstrap.Modal(document.getElementById(message_modal_id), {keyboard: false})
        message_modal.show();
    }
}($,window))