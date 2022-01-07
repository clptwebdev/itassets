$('.disposeBtn').click(function () {
    $('#model_name').val($(this).data('model-name'));
    $('#dispose_id').val($(this).data('model-id'));
    $('#model_type').val($(this).data('model-type'));
    $('#requestDisposal').modal('show');
});


// $('.disposeBtn').click(function () {
//     $('#accessory_name').val($(this).data('model-name'));
//     $('#dispose_id').val($(this).data('model-id'));
//     $('#requestDisposal').modal('show');
// });
