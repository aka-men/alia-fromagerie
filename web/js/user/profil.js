/**
 * Created by abdelhak on 29/08/2017.
 */

$(document).ready(function(){

    $("#bind_img").on('click', function (e) {
        e.preventDefault();
        $("#appbundle_profil_image_file").trigger("click");
    });


    $("#appbundle_profil_image_file").on('change', function () {
        $("#check_image").val("true");
        var image = $(this)[0].files[0];
       if ($(this).val() === '') {
            $("#show_img").hide();
            $("#deleteImage").hide();
            $("#bind_img").show();
            $("#show_img").attr("src", "");
        } else {
            $("#show_img").attr("src", URL.createObjectURL(image));
            $("#show_img").show();
            $("#deleteImage").show();
            $("#bind_img").hide();
        }
    });

    $("#deleteImage").on('click', function (e) {
        e.preventDefault();
        $("#appbundle_profil_image_file").val("");
        $("#appbundle_profil_image_file").trigger('change');
    });

});