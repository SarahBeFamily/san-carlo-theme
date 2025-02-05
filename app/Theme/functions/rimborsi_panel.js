// Upload files on panel with ajax
jQuery(document).ready(function ($) {
function caricaAllegato() {
	var files = document.getElementById('file').files;
	var formData = new FormData();
	for (var i = 0; i < files.length; i++) {
		formData.append('files[]', files[i]);
	}
	$.ajax({
		url: rimborsi_ajax.ajax_url,
		action: 'carica_allegato',
		type: 'POST',
		data: formData,
		processData: false,
		contentType: false,
		success: function (data) {
			console.log(data);

			// Aggiungo il file alla lista
			var file = JSON.parse(data);
			var html = '<li class="list-group" id="file-' + file.id + '">';
			html += '<a href="' + file.url + '" target="_blank">' + file.name + '</a>';
			html += '<button class="btn btn-danger" onclick="rimuoviAllegato(' + file.id + ')">Rimuovi</button>';
			html += '</li>';
			$('#files').append(html);
		}
	});
}
$('#carica').click(function () {
	caricaAllegato();
});
function rimuoviAllegato(id) {
	$.ajax({
		url: rimborsi_ajax.ajax_url,
		action: 'elimina_allegato',
		type: 'POST',
		data: {
			id: id
		},
		success: function (data) {
			console.log(data);
			$('#file-' + id).remove();
		}
	});
}

});