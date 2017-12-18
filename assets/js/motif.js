function motifactions(value, row, index) {
	var html = '<a href="?display=motif&amp;action=showaccount&amp;account='+row.id+'"><i class="fa fa-edit"></i></a>';

	html += '<a class="clickable"><i class="fa fa-trash-o" data-id="'+row.id+'"></i></a>';

	return html;
}


$("input[name=authmode]").change(function() {
	var val = $("input[name=authmode]:checked").val();
	if(val == 'plaintext') {
		$('.plaintext').removeClass("hidden");
		$('.oauth').addClass("hidden");
	} else {
		$('.oauth').removeClass("hidden");
		$('.plaintext').addClass("hidden");
	}
})
