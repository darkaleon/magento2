define([
    'jquery',
    'mage/translate',
    'mage/storage',
    'uiComponent',
    'ko',
    'Magento_Ui/js/modal/alert'
], function (
    $,
    $t,
    storage,
    Component,
    ko,
    alert
) {
    'use strict';

    function hideForm() {
        $('#show-form-trigger').show();
        $('#customer-description-form').hide();
        $('#delete-trigger').hide();
    }
    function showForm() {
        $('#show-form-trigger').hide();
        $('#customer-description-form').show();
        $('#delete-trigger').show();
    }
    function parseSaveResponce(response) {
        var ret = JSON.parse(response);
        alert({content: ret.message, title: ret.error ? $t('Fail to save description') : $t('Success saving')});
    }
    function parseDeleteResponce(response) {
        var ret = JSON.parse(response);
        if (!ret.error) {
            $('#ajax_description').val('');
            hideForm();
        }
        alert({content: ret.message, title: ret.error ? $t('Fail to delete description') : $t('Success deleting')});
    }

    return Component.extend({
        initialize: function (current_description_length) {
            $('#show-form-trigger').click(function (event) {
                event.preventDefault();
                showForm();
            });
            if (current_description_length > 0) {
                showForm();
            } else {
                hideForm();
            }
            $('#delete-trigger').click(function (event) {
                event.preventDefault();
                storage.post(
                    'rest/V1/deleteCustomerDescription',
                    JSON.stringify($("#customer-description-form").serializeArray())
                ).done(parseDeleteResponce).fail(parseDeleteResponce);
            });
            $('#save-trigger').click(function (event) {
                event.preventDefault();
                if ($('#ajax_description').val()) {
                    storage.post(
                        'rest/V1/editCustomerDescription',
                        JSON.stringify($("#customer-description-form").serializeArray())
                    ).done(parseSaveResponce).fail(parseSaveResponce);
                }
            });
        }
    })
});
