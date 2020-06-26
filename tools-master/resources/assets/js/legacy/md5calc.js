$( document ).ready(function ($) {

    var api_id = $("#api_id").val();
    var api_id_external = api_id * 777;
    $("#api_id_external").val(api_id_external);
    $("#api_id_external_2").val(api_id_external);

// The value of a disabled input is not posted

    $("#api_id").on("change keyup paste click", function() {
        var api_id = $("#api_id").val();
        var api_id_external = api_id * 777;
        // hidden api external id field
        $("#api_id_external").val(api_id_external);
        // none hidden api external id field
        $("#api_id_external_2").val(api_id_external);
    });

    $("#api_key_string").on("change keyup paste click", function() {
        var api_key_string = $("#api_key_string").val();
        var api_key = md5(api_key_string);

        //the hidden field of api_key
        $("#api_key").val(api_key);
        // The none hidden field of API KEY
        $("#api_key_2").val(api_key);
    });
});
