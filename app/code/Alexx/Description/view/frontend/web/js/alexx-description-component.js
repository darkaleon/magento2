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

        showForm: function () {
            $('#show-form-trigger').hide();
            $('#customer-description-form').show();
            $('#delete-trigger').show();
        },
        hideForm: function () {
            $('#show-form-trigger').show();
            $('#customer-description-form').hide();
            $('#delete-trigger').hide();
        },
        initialize: function (current_description_length) {
            $('#show-form-trigger').click({component:this},function (event) {
                event.preventDefault();
                event.data.component.showForm();
            });

            if (current_description_length > 0) {
                this.showForm();
            } else {
                this.hideForm();
            }

            $('#delete-trigger').click({component:this}, function (event) {
                event.preventDefault();

                storage.post(
                    'rest/V1/deleteCustomerDescription',
                    JSON.stringify($("#customer-description-form").serializeArray())
                ).done(function (response) {
                    var ret = JSON.parse(response);
                    $('#responsemessage').text(ret.message);
                    $('#ajaxcallvalue').val('');
                    event.data.component.hideForm();
                }).fail(function (response) {
                    var ret = JSON.parse(response);
                    $('#responsemessage').text(ret.message);

                });
            });

            //#ajaxcall button id selector
            $('#ajaxcall').click({component:this}, function (event) {
                event.preventDefault();
                if ($('#ajaxcallvalue').val()) {
                    storage.post(
                        'rest/V1/editCustomerDescription',
                        JSON.stringify($("#customer-description-form").serializeArray())
                    ).done(function (response) {
                        var ret = JSON.parse(response);
                        $('#responsemessage').text(ret.message);
                        $('#ajaxcallvalue').val(ret.data.description);
                    }).fail(function (response) {
                        var ret = JSON.parse(response);
                        $('#responsemessage').text(ret.message);
                    });
                }
            });
        }
    })
});
