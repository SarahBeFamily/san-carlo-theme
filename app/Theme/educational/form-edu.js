var $ = jQuery.noConflict();

$(document).ready(function() {

	const bodyClass = $('body').attr('class');

	if(bodyClass.includes('form-spettacoli-scuole') || bodyClass.includes('form-scuola-incanto')) {

		$(".open-tab").on("click", function(){
			/*
			console.log($(this).next());
			$(".content-tab").removeClass("aperto");
			$(this).next().addClass("aperto");*/

			$(this).next().toggleClass("aperto");
		});
		
		

		$('form.wpcf7-form').on('submit', function() {
			const isChecked = $('.nl-accettazione').is(':checked');
			const mergeField = isChecked ? 'ValueIfChecked' : '';
			$('.accettato').val(mergeField);
		});
		
		$('form.wpcf7-form input.nl-accettazione').on("change", function(){
			if ($("input.nl-accettazione").is(":checked")){
				$(".accettato").val("ACCETTO")
				}
			else {
				$(".accettato").val("NONACCETTO");
			}
		});


		/* somma tot alunni e docenti  */

		function updateTotal() {
			let total = 0;
			
			// Somma i valori degli input alunni e docenti paganti e gratuiti
			total += parseFloat($("input[name='totalunnipaganti']").val()) || 0;
			total += parseFloat($("input[name='totdocentiaccompagnatoristudenti']").val()) || 0;
			total += parseFloat($("input[name='totdocentipaganti']").val()) || 0;
			total += parseFloat($("input[name='totalunnigratuiti']").val()) || 0;
			total += parseFloat($("input[name='totdocentisostegno']").val()) || 0;

			// Aggiorna il campo totalepartecipantitot con la somma calcolata
			$("input[name='totalepartecipantitot']").val(total);
		}

		// Esegui la funzione quando il valore di uno degli input cambia
		$("input[name='totalunnipaganti'], input[name='totdocentiaccompagnatoristudenti'], input[name='totdocentipaganti'], input[name='totalunnigratuiti'], input[name='totdocentisostegno']").on('input', function() {
			updateTotal();
		});
		
		/*end ready*/
	}
});

const bodyClass = $('body').attr('class');
if(bodyClass.includes('form-spettacoli-scuole') || bodyClass.includes('form-scuola-incanto')) {
	
	/* INCREASE E DECREASE NUMBER + DUPLICA FORM */
	
	$('.remove').on('click', remove);
	var new_chq_no = 0;

	function remove() {
		if ($(".wrapper-informazioni-iscrizioni").length > 1) {
			$(".wrapper-informazioni-iscrizioni").last().remove();
		}
	}

	$("#addBtn").click(function (e) {
		e.preventDefault();
		// Clone the div with id "new_chq"
		var clonedDiv = $("#new_chq").clone();

		// Find all input elements inside the cloned div and update their name attributes
		clonedDiv.find("input").each(function () {
			var currentName = $(this).attr("name");
			var newName = currentName + "-" + ($(".wrapper-informazioni-iscrizioni").length + 1);
			$(this).attr("name", newName);

			// Clear the value of the input
			$(this).val('');
		});

		// Append the cloned div to the wrapper
		$(".wrapper-iscrizioni.clone").append(clonedDiv);
	});

	
	$("input[name=fattura]").on("click", function(){
		if($(this).val() == "SI"){
			$(".cf-tab").removeClass("hide-input");
		}
		else {
			$(".cf-tab").addClass("hide-input");
		}
		
	});
	
	$('select[name=tipo]').on('change', function() {
		if($(this).val() == "Genitore"){
		   $(".half-b").addClass("hide-input")
		   }
		if($(this).val() == "Docente"){
		   $(".half-b").removeClass("hide-input")
		   }

	});

	
	$('.select_spettacolo').on('change', function() {
		console.log("change select");

		let selectedSpettacolo = $(this).find("option:selected").attr("id").replace("spettacolo-", ""); // id numerico
		let parent = $(this).parents(".opzione"),
			date = parent.find(`.date-wrapper[id="date_${selectedSpettacolo}_wrapper"]`),
			orari = parent.find(`.orari-wrapper-spettacolo-${selectedSpettacolo}`);
		
		//console.log(date);
		//console.log(orari.attr("class"));
		
		date.removeClass("hidden");
		date.siblings("div").addClass("hidden");
		
		orari.siblings("div").addClass("hidden");
		orari.first().removeClass("hidden");
		
		// seleziono il primo radio della prima data e orario disponibili per lo spettacolo scelto
		date.find(`input[id="data_1_spettacolo_${selectedSpettacolo}_field"]`).prop("checked", true);
		orari.find(`input[id="ora_1_spettacolo_${selectedSpettacolo}_field"]`).prop("checked", true);
	});
	
	$('.date-wrapper input').on('change', function() {
		console.log("change data");

		let parent = $(this).parents(".opzione"),
			selectedSpettacolo = parent.find("option:selected").attr("id").replace("spettacolo-", ""), // id numerico
			selectedData = $(this).attr("class").replace("data-", ""), // id numerico
			orari = parent.find(`.orari-wrapper-data-${selectedData}`);
				
		orari.siblings("div").addClass("hidden");
		orari.first().removeClass("hidden");
		
		// seleziono il primo radio del primo orario disponibile per la data scelta
		orari.find(`input.ora-1`).prop("checked", true);
	});
	
	$("#wpcf7-f5264-p5267-o1 form input[type=submit], #wpcf7-f5313-p5322-o1 form input[type=submit]").on('click', function(e) {
		e.preventDefault();
		
		let date_scelte_1 = $(".date-wrapper.prima-scelta:not(.hidden)").find("input:checked").val();
		let orari_scelti_1 = $(".orari-wrapper.prima-scelta:not(.hidden)").find("input:checked").val();
		let date_scelte_2 = $(".date-wrapper.seconda-scelta:not(.hidden)").find("input:checked").val();
		let orari_scelti_2 = $(".orari-wrapper.seconda-scelta:not(.hidden)").find("input:checked").val();
		
		// Se il form è spettacoli scuole faccio il calcolo del totale partecipanti
		if ($("input[name='studenti'], input[name='docentiaccompagnatoristudenti'], input[name='studentigratuiti'], input[name='docentisostegno']").length > -1) {
			// faccio la somma dei valori disponibili
			let totale = 0;
			let inputVal = $("input[name='studenti'], input[name='docentiaccompagnatoristudenti'], input[name='studentigratuiti'], input[name='docentisostegno']").each(function(i,n){
				let thisVal = $(n).val();
				
				if (thisVal != '')
					totale += parseInt(thisVal); 
		  	});
			
			console.log(totale);
			// Aggiorna il campo totalepartecipantitot con la somma calcolata
        	$("input[name='totalepartecipantitot']").val(totale);
		}

		console.log(date_scelte_1);
		console.log(orari_scelti_1);
		console.log(date_scelte_2);
		console.log(orari_scelti_2);
		
		if (orari_scelti_1 == undefined || orari_scelti_2 == undefined) {
			alert("scegliere data e orario per gli spettacoli a cui si è interessati");
		} else {
			$("input#data_spettacolo_prima_scelta").val(date_scelte_1);
			$("input#data_spettacolo_seconda_scelta").val(date_scelte_2);
			$("input#orario_spettacolo_prima_scelta").val(orari_scelti_1);
			$("input#orario_spettacolo_seconda_scelta").val(orari_scelti_2);

			$(this).parents("form").submit();
		}
	});
	
}