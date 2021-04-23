(function($,window){

    $('input[type=radio][name=invoice_type]').change(function() {
        // donate
        if (this.value == 'donate') {
            
            $("#select-invoice-type-donate").show();
            $("#select-invoice-type-second").hide();
            
            $("input[type=radio][name=invoice_type][value=donate]").prop("checked", true);
        }
        // second_invoice
        else {
            $("#select-invoice-type-donate").hide();
            $("#select-invoice-type-second").show();
            
            $("input[type=radio][name=invoice_type][value=second_invoice]").prop("checked", true);
        }
    });

    $("#select-invoice-type-donate").show();
    $("#select-invoice-type-second").hide();

    $("#back-cart-button").click(function(){
        location.href = "/cart";
    })

    $("#go-on-checkout-button").click(function(){
        var loading_modal = new bootstrap.Modal(document.getElementById('loading-modal'), {keyboard: false});
        $("#loading-modal h5").html("處理中");
        loading_modal.show();
        $("#checkout-setting-form").submit();
    })

    $("#back-cart-button").click(function(){
        location.href = "/cart";
    })


}($,window))