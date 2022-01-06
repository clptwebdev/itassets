


    $('#import').click(function () {
        $('#manufacturer-id-test').val($(this).data('id'))
        //showModal
        $('#importManufacturerModal').modal('show')

    });
    // file input empty
    $("#confirmBtnImport").click(":submit", function (e) {

        if (!$('#importEmpty').val()) {
            e.preventDefault();
        }
    });

