/**
 * Performs rest request and inserts response to #helloOut container
 */
define(['jquery', 'mage/storage'], function ($, storage) {
    'use strict';
    return function (elementSelector) {
        var serviceUrl = '/rest/V1/hello';
        storage.get(serviceUrl).done(function (response) {
            $(elementSelector).html(response);
        }).fail(
            function (xhr, status, errorThrown) {
                $(elementSelector).html("Error: " + errorThrown + " Status: " + status);
            }
        );
    }
});
