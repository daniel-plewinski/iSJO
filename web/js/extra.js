$(document).ready(function() {
    var active = window.location.pathname;
    $(".nav a[href|='" + active + "']").parent().addClass("active");
});

