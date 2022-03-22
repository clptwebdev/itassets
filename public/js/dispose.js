const disposeBtns = document.querySelectorAll('.disposeBtn')
const disposeModal = new bootstrap.Modal(document.getElementById('requestDisposal'));

disposeBtns.forEach((item) => {
    item.addEventListener('click', function () {
        document.querySelector('#model_name').value = this.getAttribute('data-model-name');
        document.querySelector('#dispose_id').value = this.getAttribute('data-model-id');
        disposeModal.show();
    });
});

