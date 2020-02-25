define(['jquery','mage/storage'], function ($,storage) {
    var serviceUrl = '/rest/V1/hello';

    storage.get(serviceUrl).done(function (response) {
        $('#helloOut').html(response);
    }).fail(
        function (xhr, status, errorThrown) {
            $('#helloOut').html("Error: " + errorThrown + " Status: " + status);
        }
    );
});
