const transferModal = new bootstrap.Modal(document.getElementById('requestTransfer'));

document.querySelector('.transferBtn').addEventListener('click', function () {
    const modelID = document.querySelector('#model_id').value = this.getAttribute('data-model-id');
    const modelTAG = document.querySelector('#asset_tag_transfer').value = this.getAttribute('data-model-tag');
    const locationID = document.querySelector('#location_id').value = this.getAttribute('data-location-id');
    const locationFROM = document.querySelector('#location_from').value = this.getAttribute('data-location-from');
    transferModal.show()
});
