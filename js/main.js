$(document).ready(function() {

    $(".button-collapse").sideNav();
    $('select').material_select();
    $(".nav-dropdown").dropdown({belowOrigin: false});
    $('.datepicker').pickadate({
      selectMonths: true,//Creates a dropdown to control month
      selectYears: 99,//Creates a dropdown of 15 years to control year
      //The title label to use for the month nav buttons
      labelMonthNext: 'Volgende maand',
      labelMonthPrev: 'Vorige maand',
      //The title label to use for the dropdown selectors
      labelMonthSelect: 'Selecteer een maand',
      labelYearSelect: 'Selecteer een jaar',
      //Months and weekdays
      monthsFull: [ 'Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December' ],
      monthsShort: [ 'Jan', 'Feb', 'Mrt', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec' ],
      weekdaysFull: [ 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag', 'Zondag' ],
      weekdaysShort: [ 'Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za', 'Zo' ],
      //Materialize modified
      weekdaysLetter: [ 'M', 'D', 'W', 'D', 'V', 'Z', 'Z' ],
      //Today and clear
      today: 'Vandaag',
      clear: 'Reset',
      close: 'Ok',
      //The format to show on the `input` element
      format: 'd mmmm, yyyy'
    });

    // Als de parallax bestaat, voer bigImage() uit.
    if ($('.parallax-container').length) {
      $('.parallax').parallax();
    	$(window).scroll(function() {
    		bigImage("parallax-container");
    	});
      bigImage("parallax-container");
    } else
    if ($('.slider').length) {
      $('.slider').slider({full_width: true});
      $(window).scroll(function() {
        bigImage("slider");
      });
      bigImage("slider");
    };

});

function bigImage(image) {
	var height = $('.' + image + '').height() - $('nav').height();
	var scroll = Math.max(1, $('body').scrollTop());
	var percent = Math.min(1, scroll/height);
	var percent16 = Math.min(0.16, scroll/height * 0.16);
	var percent12 = Math.min(0.12, scroll/height * 0.12);
	$('nav').css('background-color', 'rgba(238, 127, 24, ' + percent + ')');
	$('nav').css('box-shadow', '0 2px 5px 0 rgba(0,0,0,' + percent16 + '), 0 2px 10px 0 rgba(0,0,0,' + percent12 + ')');
}


function scrollTo(item) {
  $('html, body').delay(300).animate({
    scrollTop: ($(item).offset().top - $('nav').height())
  }, 700, "easeOutQuint");
}


// function to first fade the object, and then slide animations
jQuery.fn.fadeThenSlideToggle = function(speed, easing, callback) {
if (this.is(":hidden")) {
return this.slideDown(speed, easing).fadeTo(speed, 1, easing, callback);
} else {
return this.fadeTo(speed, 0, easing).slideUp(speed, easing, callback);
}
};