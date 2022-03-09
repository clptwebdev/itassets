const disposeBtns = document.querySelectorAll('.disposeBtn')
const disposeModal = new bootstrap.Modal(document.getElementById('requestDisposal'));

disposeBtns.forEach((item) => {
    item.addEventListener('click', function(){
        document.querySelector('#model_name').value = this.getAttribute('data-model-name');
        document.querySelector('#dispose_id').value = this.getAttribute('data-model-id');
        disposeModal.show();
    });
});

//Removing JQuery

/* $('.disposeBtn').click(function () {
    $('#model_name').val($(this).data('model-name'));
    $('#dispose_id').val($(this).data('model-id'));
    $('#model_type').val($(this).data('model-type'));
    $('#requestDisposal').modal('show');
}); */
