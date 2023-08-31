'use strict';
$(document).ready(function () {
    window.onscroll = function () {
        menuFunction()
    };

    var myTopnav = document.getElementById("topnav");

    var sticky = myTopnav.offsetTop;

    function menuFunction() {
        if (window.pageYOffset > sticky) {
            myTopnav.classList.add("sticky")
        } else {
            myTopnav.classList.remove("sticky");
        }
    }

    $('.wrapper .nav-top #nav-icon').click(function () {
        $(this).toggleClass("open");
        var sidebar = document.getElementById("sidebar");
        var main = document.getElementById("main");
        if (sidebar.className === "navigation") {
            sidebar.classList.add("show");
            main.classList.add("translate")
        } else {
            sidebar.classList.remove("show");
            main.classList.remove("translate")
        }
    });

    $('body').on('click', function (event) {
        if ($(".nav-icon").hasClass("open")) {
            if (!$(event.target).is('#sidebar') &&
                !$(event.target).is(".wrapper .navigation .profile-info") &&
                !$(event.target).is(".wrapper .nav-top .nav-icon") &&
                !$(event.target).is(".wrapper .nav-top .nav-icon span")) {
                $(".wrapper #sidebar").removeClass("show");
                $(".wrapper #main").removeClass("translate");
                $("#nav-icon").removeClass("open");
            }
        }
    });


    function readURL(input, dest) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(dest).attr('src', e.target.result);
                $(dest).addClass('avatar')
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#admin-image").change(function () {
        readURL(this, '.upload-avatar .avatar');
    });

    $("#notification-small_icon").change(function () {
        readURL(this, '.col-input-small-icon img')
    });

    $("#notification-large_icon").change(function () {
        readURL(this, '.col-input-large-icon img')
    });

    function change_lease_type(lease_type) {

        var targetBox = $("." + lease_type);
        $(".reveal-if").not(targetBox).css({
            "opacity": "0",
            "max-height": "0",
            "overflow": "hidden",
            "transform": "scale(0.8)"
        });
        $(".reveal-if input").not(targetBox).val('');
        $(targetBox).css({"opacity": "1", "max-height": "initial", "overflow": "visible", "transform": "scale(1)"});
    }


    change_lease_type($('input[name="House[lease_type]"]:checked').val());

    $('input[name="House[lease_type]"]').change(function () {
        change_lease_type($(this).attr("value"));
    });


    function addCommas(str) {
        str = str.replace(/,/g, '');
        var objRegex = new RegExp('(-?[0-9]+)([0-9]{3})');
        while (objRegex.test(str)) {
            str = str.replace(objRegex, '$1,$2');
        }
        return str;
    }


    $('.price input').keyup(function () {
        var num = $(this).val();
        $(this).val(addCommas(num));
    });

    $('.price').keyup(function () {
        var num = $(this).val();
        $(this).val(addCommas(num));
    });


    $('#house-postcode').keyup(function () {
        var length = $(this).val().length;
        if (length === 5) {
            $(this).val(($(this).val() + '-'));
        }
    });


    $('.just-number').on('keypress', function (key) {
        if (key.charCode < 48 || key.charCode > 57) {
            return false;
        }
    });


});



