document.querySelector('.deleteBtn').addEventListener('click', function () {
    document.querySelector('#user-id').value = this.getAttribute('data-id');
    //showModal
    $('#removeUserModal').modal('show');
    // document.querySelector('#removeUserModal').style.display= 'block';
    // document.querySelector('#removeUserModal').classList.add('show');
});

document.querySelector('#confirmBtn').addEventListener('click', function () {
    const user = document.querySelector('#user-id').value;
    const form = document.querySelector('#' + 'form' + user);
    form.submit();
});
