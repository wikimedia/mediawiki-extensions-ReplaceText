function invertSelections() {
	form = document.getElementById('choose_pages');
	num_elements = form.elements.length;
	for (i = 0; i < num_elements; i++) {
		if (form.elements[i].type == "checkbox") {
			if (form.elements[i].checked == true)
				form.elements[i].checked = false;
			else
				form.elements[i].checked = true;
		}
	}
}