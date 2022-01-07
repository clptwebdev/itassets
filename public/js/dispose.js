$('.disposeBtn').click(function () {
<<<<<<< HEAD
    $('#accessory_name').val($(this).data('model-name'));

=======
    $('#model_name').val($(this).data('model-name'));
>>>>>>> 1bede865b37b0174ca9d470a6309e912a03b7b10
    $('#dispose_id').val($(this).data('model-id'));
    $('#model_type').val($(this).data('model-type'));
    $('#requestDisposal').modal('show');
});


// $('.disposeBtn').click(function () {
//     $('#accessory_name').val($(this).data('model-name'));
//     $('#dispose_id').val($(this).data('model-id'));
//     $('#requestDisposal').modal('show');
// });
