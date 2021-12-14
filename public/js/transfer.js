$('.transferBtn').click(function () {
    $('#model_id').val($(this).data('model-id'));
    $('#location_id').val($(this).data('location-id'));
    $('#location_from').val($(this).data('location-from'));
    $('#requestTransfer').modal('show');
});
