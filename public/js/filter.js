
function toggleFilter() {
    if ($('#filter').hasClass('show')) {
        $('#filter').removeClass('show');
        $('#filter').css('right', '-100%');
    } else {
        $('#filter').addClass('show');
        $('#filter').css('right', '0%');
    }
}


