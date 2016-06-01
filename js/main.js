$(document).ready(function() {

	$(".button-collapse").sideNav();
	$('select').material_select();
	$(".nav-dropdown").dropdown({belowOrigin: false});
	$('.datepicker').pickadate({
		selectMonths: true,// Creates a dropdown to control month
		selectYears: 99,// Creates a dropdown of 99 years to control year
		// The title label to use for the month nav buttons
		labelMonthNext: 'Volgende maand',
		labelMonthPrev: 'Vorige maand',
		// The title label to use for the dropdown selectors
		labelMonthSelect: 'Selecteer een maand',
		labelYearSelect: 'Selecteer een jaar',
		// Months and weekdays
		monthsFull: [ 'Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December' ],
		monthsShort: [ 'Jan', 'Feb', 'Mrt', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec' ],
		weekdaysFull: [ 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag', 'Zondag' ],
		weekdaysShort: [ 'Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za', 'Zo' ],
		// Materialize modified
		weekdaysLetter: [ 'M', 'D', 'W', 'D', 'V', 'Z', 'Z' ],
		// Today and clear
		today: 'Vandaag',
		clear: 'Reset',
		close: 'Ok',
		// The format to show on the `input` element
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


	$('.change-view').click(function() {
		var activate = $(this).attr('href');
		var disable = $(this).attr('data-disable');
		$(activate).slideDown(400, "easeOutCubic");
		$(disable).slideUp(400, "easeOutCubic");
	});

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


// ~~TZT API FUNCTIES~~
function checkSend() {
	// Stop form submit
	var form = $('form.check-send');

	var b_straat = $('form.check-send input#v_straat').val();
	var b_postcode = $('form.check-send input#v_postcode').val();
	var b_huisnummer = $('form.check-send input#v_huisnummer').val();
	var b_plaats = $('form.check-send input#v_plaats').val();
	var begin = {straat: b_straat, postcode: b_postcode, huisnummer: b_huisnummer, plaats: b_plaats};

	var e_straat = $('form.check-send input#o_straat').val();
	var e_postcode = $('form.check-send input#o_postcode').val();
	var e_huisnummer = $('form.check-send input#o_huisnummer').val();
	var e_plaats = $('form.check-send input#o_plaats').val();
	var einde = {straat: e_straat, postcode: e_postcode, huisnummer: e_huisnummer, plaats: e_plaats};

	var p_gewicht = $('form.check-send input#p_gewicht').val();
	var p_lengte = $('form.check-send input#p_lengte').val();
	var p_breedte = $('form.check-send input#p_breedte').val();
	var p_hoogte = $('form.check-send input#p_hoogte').val();
	var pakket = {gewicht: p_gewicht, lengte: p_lengte, breedte: p_breedte, hoogte: p_hoogte};

	// Reset modal
	$('#response-modal .modal-content').html("<h3>Moment geduld</h3><p>Wij zijn de prijs voor u aan het berekenen...</p>");

	berekenKosten(begin, einde, pakket, function(response) {
		if (response.indexOf('ERROR') > -1) {
			$('#response-modal .modal-content').html('<h4>Er is iets fout gegaan...</h4><p>Controleer of er velden leeg zijn en probeer het opnieuw.'+response+'</p></div><div class="modal-footer"><a class="modal-action modal-close waves-effect waves-black btn-flat" onclick='+'$("#response-modal").closeModal();'+'>Annuleer</a>');
		} else {
			$('#response-modal .modal-content').html('<h4>De kosten van uw pakketje zijn:</h4><h4 class="center">€'+response+'</h4></div><div class="modal-footer"><a onclick='+'$("form.check-send").submit();'+' class="modal-action modal-close waves-effect waves-black btn-flat">Naar betalen</a><a class="modal-action modal-close waves-effect waves-black btn-flat" onclick='+'$("#response-modal").closeModal();'+'>Annuleer</a>');
		}
	});

	return false;
}

function clickBetalen(begin, einde, verzender, ontvanger, pakket) {
	$('#response-modal .modal-content').html("<h4>Moment geduld</h4><p>Wij zijn uw pakketje aan het verwerken...</p>");
	$('#response-modal').openModal({dismissible: false});
	betalen(begin, einde, verzender, ontvanger, pakket, function(response) {
		$('#response-modal .modal-content').html("<h4>Geslaagd!</h4><p>Het pakketje is bij ons aangemeld onder trackingcode:</p><h4 class='center'><strong>"+response+"</strong></h4><p>Wij zullen de verzending z.s.m. in werking zetten.</p></div><div class='modal-footer'><a href='home' class='modal-action modal-close waves-effect waves-black btn-flat'>Ok</a>");
	});
}

function betalen(begin, einde, verzender, ontvanger, pakket, callback) {
	// Maak een postrequest naar de API om de route te berekenen en in de database te zetten
	$.ajax({
		type:"POST",
		url:"send_engine/engine.php",
		data:{'begin': begin, 'eind': einde, 'pakket': pakket, 'verzender': verzender, 'ontvanger': ontvanger, 'functie':'totaal'},
		success: function(response) {
			callback(response);
		}
	});
}


function trackTrace() {
	// Haal waarden op uit de inputs
	var trackingnr = $('input#trackingnr').val();
	var postcode = $('input#postcode').val();
	// Reset error field
	$('#error-field').html("");
	// Gebruik de track_engine API om te kijken of deze combinatie bestaat:
	$.ajax({
		type:"POST",
		url:"track_engine/engine.php",
		data:{'trackingnr': trackingnr, 'postcode': postcode},
		success: function(response) {
			if (response == "") {
				$('#error-field').html("Het lijkt er op dat dit pakketje niet bestaat. Kijk de trackingcode na en probeer het opnieuw.");
			} else {
				$.ajax({
					type:"POST",
					url:"track_engine/table.php",
					data:{'tussenstappen': response},
					success: function(table) {
						$('#result .container#table-container').html(table);
						$('#inputs').slideUp(400, "easeOutCubic");
						$('#result').slideDown(400, "easeOutCubic");
					}
				});
			}
		}
	});
	return false;
}


function berekenKosten(begin, einde, pakket, callback) {
	// Maak een postrequest naar de API om de kosten te berekenen
	$.ajax({
		type:"POST",
		url:"send_engine/engine.php",
		data:{'begin': begin, 'eind': einde, 'pakket': pakket, 'functie':'kosten'},
		success: function(response) {
			callback(response);
		}
	});
}






function aanmeldenPakket(id) {
	$.ajax({
		type:"POST",
		url:"treinkoerier_acties/aanmelden-pakket.php",
		data:{'id': id},
		success: function(response) {
			$('button#aanmelden-' + id + ' i').html('done_all');
			setTimeout(function() {
				location.reload();
			}, 1000);
		}
	});
}




jQuery.fn.fadeThenSlideToggle = function(speed, easing, callback) {
	if (this.is(":hidden")) {
		return this.slideDown(speed, easing).fadeTo(speed, 1, easing, callback);
	} else {
		return this.fadeTo(speed, 0, easing).slideUp(speed, easing, callback);
	}
};

jQuery.fn.fadeThenSlide = function(way, speed, easing, callback) {
	if (way) {
		return this.slideDown(speed, easing).fadeTo(speed, 1, easing, callback);
	} else {
		return this.fadeTo(speed, 0, easing).slideUp(speed, easing, callback);
	}
};