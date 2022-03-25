const transferModal = new bootstrap.Modal(document.getElementById('requestTransfer'));


document.querySelectorAll('.transferBtn').forEach(elem => elem.addEventListener("click", (event) => {

    const modelID = document.querySelector('#model_id').value = elem.getAttribute('data-model-id');
    const modelTAG = document.querySelector('#asset_tag_transfer').value = elem.getAttribute('data-model-tag');
    const locationID = document.querySelector('#location_id').value = elem.getAttribute('data-location-id');
    const locationFROM = document.querySelector('#location_from').value = elem.getAttribute('data-location-from');
    transferModal.show()
}));
