(function($) {

	let isTouch = (("ontouchstart" in window) || (navigator.msMaxTouchPoints > 0));
	let clickhandler = isTouch ? "touchstart" : "click";

	$(document).ready(function () {
		checkActiveEvent();
	});

	// Calendar JS
	// Questa funzione viene eseguita quando l'utente cambia il valore del menu a discesa del mese.
	$(document).on("change", "select#month", function() {
		// Ottiene il mese selezionato dall'utente.
		let val = $(this).val(),
			// Ottiene l'anno corrente.
			year = $("a.choose-month").attr("data-year");

		// Chiama la funzione ajaxCall con il mese e l'anno selezionati.
		ajaxCall(val, year);
	});

	// Questa funzione controlla l'evento attivo nel calendario.
	function checkActiveEvent() {
		// Ottiene il primo evento attivo e i suoi dettagli.
		let activeEvent = $('.calendar .event.active').first(),
			moreActiveArray = $('.calendar .event.active'),
			activeEventDetails = activeEvent.siblings('.dettaglio-evento');

		$('.total-slide').html(activeEventDetails.length);
		$('#active-slide').attr('max-value', activeEventDetails.length+1);
		activeEvent.siblings('.dettaglio-evento:not([index="0"])').hide();

		// Se esistono più eventi attivi (cioè due eventi in mesi diversi), nascondo quello del mese successivo al corrente.
		for (let i = 0; i < moreActiveArray.length; i++) {
			let event = moreActiveArray[i];

			if (moreActiveArray.length > 1 && i > 0) {
				$(event).siblings('.dettaglio-evento').hide();
			}
		}
	}



	$(document).on(clickhandler, '#controls .button', function() {
		let next = $(this).hasClass('next'),
			details = $('.calendar .event.active').siblings('.dettaglio-evento'),
			calSlide = details.find('.cal-slide'),
			activeSlide = parseInt($('#active-slide').val()),
			maxSlide = parseInt($('#active-slide').attr('max-value'));

		if (next == true && activeSlide < maxSlide) {
			$('.calendar .event.active').siblings(`.dettaglio-evento[index="${activeSlide}"]`).fadeOut();
			$('.calendar .event.active').siblings(`.dettaglio-evento[index="${activeSlide+1}"]`).fadeIn();
			activeSlide = activeSlide <= maxSlide ? activeSlide+1 : maxSlide;
			$('#active-slide').val(activeSlide);
		} else if (next == false && activeSlide > 1) {
			$('.calendar .event.active').siblings(`.dettaglio-evento[index="${activeSlide}"]`).fadeOut();
			$('.calendar .event.active').siblings(`.dettaglio-evento[index="${activeSlide-1}"]`).fadeIn();
			activeSlide = activeSlide >= 1 ? activeSlide-1 : 1;
			$('#active-slide').val(activeSlide);
		}

		calSlide.find('.total-slide').html(details.length);
		calSlide.find('.current-slide').html(activeSlide);
	});

	$(document).on(clickhandler, '.calendar .event', function() {
	   $(".calendar .event").removeClass('active');
	   $(this).addClass('active');

		let details = $(this).siblings('.dettaglio-evento'),
			activeDetails = $('.calendar .event.active').siblings('.dettaglio-evento'),
			data = $(this).attr("event-date"),
			id = $(this).attr("data-id"),
			event_child = $(`.dettaglio-evento[data-id="${id}"][event-date="${data}"]`),
			other_events = $(`.dettaglio-evento:not([data-id="${id}"][event-date="${data}"]):visible`),
			calSlide = event_child.find('.cal-slide'),
			day = $(this).siblings('span').text(),
            month = $('select#month').val(),
            year = $('a.choose-month').attr('data-year'),
            date = `${year}/${month}/${day}`,
			input = $('input[name="date_choice"]');

			$(this).parent().find('span').addClass('actived');
        	$(this).parent().siblings().find('span').removeClass('actived');

			if (input.length > 0) {
				input.val(date).trigger('click');
			}

			calSlide.find('.total-slide').html(activeDetails.length);
			calSlide.find('.current-slide').html(1);
			$('#active-slide').val(1);
			$('#active-slide').attr('max-value', activeDetails.length);

		other_events.fadeOut();
		event_child.fadeIn();
	});

	// Questa funzione gestisce il click su un giorno specifico nel calendario.
	$(document).on(clickhandler, '.bf-calendar-choice .day_num span', function() {
		// Ottiene il giorno, il mese e l'anno selezionati.
		let day = $(this).text(),
			month = $('select#month').val(),
			year = $('a.choose-month').attr('data-year'),
			date = `${year}/${month}/${day}`,
			input = $('input[name="date_choice"]');

		// Aggiunge la classe 'actived' al giorno selezionato e la rimuove dai fratelli.
		$(this).parent().addClass('actived');
		$(this).parent().siblings().removeClass('actived');

		// Se esiste un input con il nome 'date_choice', imposta il suo valore alla data selezionata e attiva un evento click.
		if (input.length > 0) {
			input.val(date).trigger('click');
		}
	});

	// Questa funzione gestisce il click sui pulsanti 'prev' e 'next' nel calendario.
	$(document).on(clickhandler, '.calendar .prev, .calendar .next', function() {
		// Ottiene il mese e l'anno correnti.
		let currentMonth = parseInt($("select#month").val()),
			year = parseInt($("a.choose-month").attr("data-year")),
			newChoice, month;

		// Se il mese corrente è Dicembre,
		// imposta newChoice al mese precedente se il pulsante cliccato è 'prev', altrimenti imposta newChoice a 1 (Gennaio).
		// Se il pulsante cliccato è 'prev', l'anno rimane invariato, altrimenti l'anno viene incrementato di 1.
		// Se il mese corrente è Gennaio,
		// imposta newChoice a 12 (Dicembre) se il pulsante cliccato è 'prev', altrimenti imposta newChoice al mese successivo.
		if (currentMonth == 12) {
			newChoice = $(this).hasClass('prev') ? currentMonth-1 : 1;
			year = $(this).hasClass('prev') ? year : year+1;
		} else if (currentMonth == 1) {
			newChoice = $(this).hasClass('prev') ? 12 : currentMonth+1;
			year = $(this).hasClass('prev') ? year-1 : year;
		} else {
			newChoice = $(this).hasClass('prev') ? currentMonth-1 : currentMonth+1;
		}

		month = newChoice.toString().length == 1 ? `0${newChoice.toString()}` : newChoice.toString();

		// console.log(month);
		ajaxCall(month, year);
	});


	let loadingArray = {},
		id_var = 0;

	function progressLoading(time, speed = 5) {
		const animate = () => {
			time++;
			let timeInt = parseInt(time);
			$('#loading-progress .progress-bar').width(`${time}%`);
			$('#loading-progress .progress-bar').html(`${timeInt}%`);
		};

		
		loadingArray[++id_var] = setInterval(intLoading, speed);

		if (time === 0) {
			$('#loading-progress').removeClass('hidden');
		}
		else if (time === 'clear') {
			for( var id in loadingArray ){
				clearInterval( loadingArray[id] );
			}
			$('#loading-progress').addClass('hidden');
		}

		function intLoading() {
			if (time === 100) {
				for( var id in loadingArray ){
					clearInterval( loadingArray[id] );
				}
				$('#loading-progress').addClass('hidden');
			} else {
				animate();
			}
		}
	}
	
	// Questa funzione esegue una chiamata AJAX per ottenere il calendario del mese e dell'anno specificati.
	function ajaxCall(month, year) {
		// Aggiunge la classe 'loading' al corpo del documento.
		$('body').addClass('loading');
		progressLoading(0, 80); // Mostra la barra di caricamento allo 0%

		// Esegue la chiamata AJAX.
		$.ajax({
			url: calAjax.url, // L'URL a cui inviare la richiesta.
			type: 'post', // Il tipo di richiesta da eseguire.
			data: { // I dati da inviare al server.
				action: 'calendar_ajax_call', // L'azione da eseguire.
				nonce: calAjax.nonce, // Il nonce per la sicurezza.
				month: month, // Il mese per il quale ottenere il calendario.
				y: year // L'anno per il quale ottenere il calendario.
			},
			// success: function(response) { // La funzione da eseguire se la richiesta ha successo.
			// 	let jsonp = JSON.parse(response);
			// 		console.log(jsonp);

			// 	// Se esiste un elemento con la classe 'bf-calendar-wrap', sostituisci il suo contenuto con la risposta.
			// 	if($('.bf-calendar-wrap').length > 0) {
			// 		$('.bf-calendar-wrap').html(jsonp);
			// 	// Altrimenti, se esiste un elemento con la classe 'bf-calendar-choice', sostituisci il suo contenuto con la risposta.
			// 	} else if ($('.bf-calendar-choice').length > 0) {
			// 		$('.bf-calendar-choice').html(jsonp);
			// 	}
					
			// 	checkActiveEvent();
			// 	// Rimuove la classe 'loading' dal corpo del documento.
			// 	$('body').removeClass('loading');
			// 	progressLoading('clear');
			// },
			// error: function(xhr, status, error) { // La funzione da eseguire se la richiesta ha un errore.
			// 	// Stampa un messaggio di errore.
			// 	console.log('Filter ERROR STATUS: ' + xhr.status);
			// 	console.log(error);
			// 	console.log(xhr);
			// 	alert(xhr.statusText);
			// 	// Rimuove la classe 'loading' dal corpo del documento.
			// 	$('body').removeClass('loading');
			// 	progressLoading('clear');
			// }
			complete: function(xhr, status) { // La funzione da eseguire quando la richiesta è completata.
				// Se c'è un errore o se la risposta è vuota, stampa un messaggio di errore.
				if (status === 'error' || !xhr.responseText) {
					console.log('Filter ERROR STATUS: ' + xhr.status);
					console.log(xhr);
					alert(xhr.statusText);
				} else {
					// Altrimenti, analizza la risposta come JSON.
					let resp = xhr.responseText;
					let jsonp = JSON.parse(resp);
					// console.log(jsonp);

					// Se esiste un elemento con la classe 'bf-calendar-wrap', sostituisci il suo contenuto con la risposta.
					if($('.bf-calendar-wrap').length > 0) {
						$('.bf-calendar-wrap').html(jsonp);
					// Altrimenti, se esiste un elemento con la classe 'bf-calendar-choice', sostituisci il suo contenuto con la risposta.
					} else if ($('.bf-calendar-choice').length > 0) {
						$('.bf-calendar-choice').html(jsonp);
					}
						
					checkActiveEvent();
					// Rimuove la classe 'loading' dal corpo del documento.
					$('body').removeClass('loading');
					progressLoading('clear');
				}
			}
		});
	}

})(jQuery);