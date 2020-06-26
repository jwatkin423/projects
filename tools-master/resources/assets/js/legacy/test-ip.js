$(document).ready(function ($) {

    $(".test-ip").on("click", function() {
        var data = $(this).text();
        var arr = data.split(' ');
        $("#ip").val(arr[0]);
    });

});