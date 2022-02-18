const deleteBtn = document.querySelectorAll('.deleteBtn');
const deleteModal = new bootstrap.Modal(document.getElementById('permDeleteModal'));

deleteBtn.forEach((item) => {
    item.addEventListener('click', function(){
        let model = document.querySelector('#model-id');
        let value = this.getAttribute('data-id');
        model.value = value;
        deleteModal.show();
    });
})


const confirmBtn = document.querySelector('#confirmPermDelete');

confirmBtn.addEventListener('click', function(){
    let model = document.querySelector('#model-id').value;
    let formName = `#form${model}`;
    let form = document.querySelector(formName);
    form.submit();
    deleteModal.hide(); 
});