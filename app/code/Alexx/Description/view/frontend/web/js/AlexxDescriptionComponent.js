define([
    'jquery',
    'mage/translate',
    'mage/storage',
    'uiComponent',
    'ko'
], function (
    $,
    $t,
    storage,
    Component,
    ko,
    alert
) {
    'use strict';
    return Component.extend({

        initialize: function (current_description_length) {

            $('#show_form_trigger').click(function (e) {
                e.preventDefault();
                $('#show_form_trigger').hide();
                $('#testform').show();
                $('#delete_trigger').show();
            });


           if (current_description_length>0){
$('#show_form_trigger').hide();
$('#testform').show();
$('#delete_trigger').show();
           }else{
               $('#show_form_trigger').show();
               $('#testform').hide();
               $('#delete_trigger').hide();
           }


           $('#delete_trigger').on('click',function(e){
               e.preventDefault();
               var serviceUrlCreate,serviceUrl,payload;
               /**
                * Save  values .
                */
               serviceUrl        = 'rest/V1/deleteCustomerDescription';
               payload           = $( "#testform" ).serializeArray();

               storage.post(
                   serviceUrl,
                   JSON.stringify(payload)
               ).done(function (response) {
                   var   ret = JSON.parse(response);
                   $('#responsemessage').text(ret.message);
                   $('#ajaxcallvalue').val('');
                   $('#show_form_trigger').show();
                   $('#testform').hide();
                   $('#delete_trigger').hide();

               }).fail(function (response) {
                   var   ret = JSON.parse(response);
                   $('#responsemessage').text(ret.message);

               });
           });

            //#ajaxcall button id selector
            $('#ajaxcall').on('click',function(e){
                e.preventDefault();
                if($('#ajaxcallvalue').val()){
                    /*                ajaxValue = [
                                        'ajaxvalue' : $('#ajaxcallvalue').val();
                                ];*/
                    //#ajaxcallvalue this text box id seletor
                    var serviceUrlCreate,serviceUrl,payload;
                    /**
                     * Save  values .
                     */
                    serviceUrl        = 'rest/V1/editCustomerDescription';
                    payload           = $( "#testform" ).serializeArray();

                    // console.log(  JSON.stringify(payload));
                    storage.post(
                        serviceUrl,
                        JSON.stringify(payload)
                    ).done(function (response) {
             var   ret = JSON.parse(response);
                        $('#responsemessage').text(ret.message);
                        $('#ajaxcallvalue').val(ret.data.description);

                    }).fail(function (response) {
                        var   ret = JSON.parse(response);
                        $('#responsemessage').text(ret.message);
                    });
                }
            });
        }
    })
});
