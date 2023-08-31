'use strict';
$(document).ready(function() {
    var slideIndex = 1;
    $('.slide-next').click(function() {
         plusSlides(1)
    });

    $('.slide-prev').click(function() {
        plusSlides(-1)
    });

    $('.demo').click(function () {
      var slide = $(this).data('val');
       currentSlide(slide);
    });

    $('.contact-info .label').click(function () {
        $('.contact-info .info').toggleClass('show');
        $(this).toggleClass('open');
    });

    // Next/previous controls
    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

// Thumbnail image controls
    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("slide");
        var dots = document.getElementsByClassName("demo");
        var captionText = document.getElementById("caption");
        if (n > slides.length) {slideIndex = 1}
        if (n < 1) {slideIndex = slides.length}
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex-1].style.display = "block";
        dots[slideIndex-1].className += " active";
        captionText.innerHTML = dots[slideIndex-1].alt;
    }

    showSlides(slideIndex);
});