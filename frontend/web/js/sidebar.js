'use strict';
$(document).ready(function () {
    $('.house-overview .nav-top .nav-icon ').click(function () {
        console.log('.nav-icon clicked');
        let sidebar = $(".house-overview .sidebar");
        sidebar.toggleClass('show');
    });

    $('.house-overview .sidebar .close-icon').click(function () {
        console.log('.close-icon clicked');
        let sidebar = $(".house-overview .sidebar");
        if (sidebar.hasClass('show')){
            sidebar.removeClass('show');
        }
    });
});