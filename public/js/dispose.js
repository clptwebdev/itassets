$('.disposeBtn').click(function () {
    $('#accessory_name').val($(this).data('model-name'));
    $('#dispose_id').val($(this).data('model-id'));
    $('#requestDisposal').modal('show');
});
