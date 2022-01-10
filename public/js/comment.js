$('#commentModal').click(function () {
    //showModal
    $('#commentModalOpen').modal('show')
});


$('.editComment').click(function (event) {
    event.preventDefault();
    $('#updateTitle').val($(this).data('title'));
    $('#updateComment').val($(this).data('comment'));
    var route = $(this).data('route');
    $('#updateForm').attr('action', route);
    $('#commentModalEdit').modal('show');
});

$('.deleteComment').click(function () {
    $('#comment-id').val($(this).data('id'));
    //showModal
    $('#removeComment').modal('show');
});

$('#confirmCommentBtn').click(function () {
    var form = '#' + 'comment' + $('#comment-id').val();
    $(form).submit();
});
