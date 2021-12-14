
    function selectPhoto(id, src) {
    document.getElementById("profileImage").src = src;
    document.getElementById("photo_id").value = id;
    $('#imgModal').modal('hide');
}

    $(document).ready(function () {
    $("form#imageUpload").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var urlto = '/photo/upload';
        var route = '{{asset("/")}}';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // AJAX request
        $.ajax({
            url: urlto,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                $('#uploadModal').modal('hide');
                document.getElementById("profileImage").src = route + data.path;
                document.getElementById("photo_id").value = data.id;
            }
        });
    });
});

