(function($) {
	var activeTabIndex = localStorage.getItem('activeTabIndex');

	if (activeTabIndex === null) {
		activeTabIndex = 0;
	}
	console.log(activeTabIndex);

	$('.tab-opener').eq(activeTabIndex).addClass('active');
	$('.tab-item').eq(activeTabIndex).addClass('active');

	$('.tab-opener').click(function(event) {
		event.preventDefault();
		if (!$(this).hasClass('active')) {
			var aim = $(this).parents('.tab-control').find('.tab-opener').removeClass('active').index(this);
			$(this).addClass('active');
			$(this).parents('.tabset').find('.tab-item').removeClass('active').eq(aim).addClass('active');

			localStorage.setItem('activeTabIndex', aim);
		}
	});

	$('.advanced-settings__opener').click(function(e) {
		let item = $(this).closest('.advanced-settings');
		item.find('.advanced-settings__content').slideToggle(function(){
			item.toggleClass('active');
		});
		e.preventDefault();
		
	});

	function preserveOrderOnSelect2Choice(e){
		var id = e.params.data.id;
		var option = $(e.target).children('[value='+id+']');
		option.detach();
		$(e.target).append(option).change();
	}
	po_select2s = $('.js-example-basic-multiple').select2()
	po_select2s.each(function(){
		$(this).on('select2:select',preserveOrderOnSelect2Choice);
	});
	
})(jQuery);


// checkbox expand post type
var checkboxes = document.querySelectorAll('.content-type-head input[type="checkbox"]');
function handleCheckboxChange(event) {
	var parentItem = event.target.closest('.content-types__item');
	if (!parentItem) {
		return; 
	}
	var attributesWrap = parentItem.querySelector('.attributes-wrap');
	if (event.target.checked) {
		attributesWrap.style.display = 'block'; 
	} else {
		attributesWrap.style.display = 'none'; 
	}
}
checkboxes.forEach(function(checkbox) {
	checkbox.addEventListener('change', handleCheckboxChange);
});
checkboxes.forEach(function(checkbox) {
	handleCheckboxChange({ target: checkbox });
});
// add new field
document.addEventListener('DOMContentLoaded', function() {
	document.querySelectorAll('.add-field').forEach(button => {
		button.addEventListener('click', function() {
			var tableName = this.getAttribute('data-post-type');
			var fieldNameInput = this.parentNode.querySelector('.new-field-name');
			var fieldName = fieldNameInput.value;

			if (fieldName.trim() !== '') {
				var formattedFieldName = formatFieldName(fieldName);

				var parentItem = this.closest('.content-types__item');
				var table = parentItem.querySelector('.attributes-table');
				var newRow = table.insertRow();

				newRow.innerHTML = `
				<td>${fieldName}</td>
				<td>
				<input type="hidden" name="post_type_fields[${tableName}][${fieldName}][name]" value="${fieldName}">
				<input type="checkbox" name="post_type_fields[${tableName}][${fieldName}][status]" value="1" class="checkbox">
				</td>
				<td>
				<input type="text" class="form-input" name="post_type_fields[${tableName}][${fieldName}][label]" value="${formattedFieldName}">
				</td>
				`;
			}
			fieldNameInput.value = '';
		});
	});
});

function formatFieldName(fieldName) {
	if (fieldName[0] === '_') {
		fieldName = fieldName.substring(1);
	}

	return fieldName.replace(/^[a-z]/, function(match) {
		return match.toUpperCase();
	}).replace(/_/g, ' ');
}

// autocomplete
document.addEventListener('DOMContentLoaded', function() {
	document.querySelectorAll('.new-field-name').forEach(input => {
		input.addEventListener('input', function() {
			var postType = this.getAttribute('data-post-type');
			var autocompleteData = JSON.parse(document.querySelector('.autocomplete-data[data-post-type="' + postType + '"]').textContent);
			var value = this.value.toLowerCase();

			closeAllLists(input);

			if (!value) return false;
			var list = document.createElement("DIV");
			list.setAttribute("class", "autocomplete-items");
			this.parentNode.appendChild(list);

			autocompleteData.forEach(function(item) {
				if (item.toLowerCase().includes(value)) {
					var itemDiv = document.createElement("DIV");
					itemDiv.setAttribute("class", "autocomplete-items__item");
					itemDiv.innerHTML = item; 
					itemDiv.innerHTML += "<input type='hidden' value='" + item + "'>";
					itemDiv.addEventListener("click", function() {
						input.value = this.getElementsByTagName("input")[0].value;
						closeAllLists(input);
					});
					list.appendChild(itemDiv);
				}
			});
		});
	});

	function closeAllLists(el, inputElement) {
		var items = document.getElementsByClassName("autocomplete-items");
		for (var i = 0; i < items.length; i++) {
			if (el != items[i] && el != inputElement) {
				items[i].parentNode.removeChild(items[i]);
			}
		}
	}

	document.addEventListener("click", function (e) {
		closeAllLists(e.target);
	});
});

const syncForm = document.getElementById('syncForm');
if (syncForm) {

	// Alert Modal
	const omniAlertModal = document.getElementById('omniAlertModal');

	// Alert Close Btn
	const closeButton = omniAlertModal.querySelector('.omni-modal__close');
	closeButton.addEventListener('click', function () {
		omniAlertModal.classList.remove('omni-modal--show');
	});

	syncForm.addEventListener('submit', function (event) {
		event.preventDefault();
		let formData = new FormData();
		formData.append('action', 'sync_data_action');

		// Disable Button && add loader class
		let syncFormSubmitButton = event.submitter;
		console.log(syncFormSubmitButton);

		syncFormSubmitButton.disabled = true;
		syncFormSubmitButton.classList.add('btn-omni--loading');

		fetch(ajaxurl, {
			method: 'POST',
			body: formData
		})
			.then(response => response.json())
			.then(data => {
				const lastSyncDateSpan = document.getElementById('last-sync-date');

				// Show Alert Modal
				omniAlertModal.classList.add('omni-modal--show');
				const omniModalText = omniAlertModal.querySelector('.omni-modal__text');

				if (data.success) {
					// Add success class
					omniAlertModal.classList.remove('omni-modal--warning');

					omniAlertModal.classList.add('omni-modal--success');
					omniModalText.innerHTML = "Posts Synchronized successfully!";
					// set current date
					const now = new Date();
					lastSyncDateSpan.innerHTML = now.getFullYear() + '-' +
						('0' + (now.getMonth() + 1)).slice(-2) + '-' +
						('0' + now.getDate()).slice(-2) + ' ' +
						('0' + now.getHours()).slice(-2) + ':' +
						('0' + now.getMinutes()).slice(-2) + ':' +
						('0' + now.getSeconds()).slice(-2);
					lastSyncDateSpan.style.color = "green";
				} else {
					// Add warning class
					omniAlertModal.classList.remove('omni-modal--success');

					omniAlertModal.classList.add('omni-modal--warning');
					omniModalText.innerHTML = "There was an error! Please try again later.";
				}

				// Remove the class after 5 seconds
				setTimeout(function () {
					omniAlertModal.classList.remove('omni-modal--show');
				}, 5000);

				setTimeout(function () {
					// Enable Button && remove loader class
					syncFormSubmitButton.disabled = false;
					syncFormSubmitButton.classList.remove('btn-omni--loading');
				}, 200);
			})
			.catch(error => {
				// ToDo: Alert Modal "--danger"
				console.error('Error:', error);
			});
	});
}


/**
 * Omnimind Settings
 * Dashboard Alert Handler
 */
function omniAlertHandler(alertStatus, alertMessage) {

	// Alert Modal
	const omniAlertModal = document.getElementById('omniAlertModal');

	// Alert Modal Close Btn
	const closeButton = omniAlertModal.querySelector('.omni-modal__close');
	closeButton.addEventListener('click', function () {
		omniAlertModal.classList.remove('omni-modal--show');
	});

	// Alert Modal Message container
	const omniModalText = omniAlertModal.querySelector('.omni-modal__text');

	// Show Alert Modal
	omniAlertModal.classList.add('omni-modal--show');

	// Handle Success
	if (alertStatus === "success") {
		omniAlertModal.classList.add('omni-modal--success');
		omniModalText.innerHTML = alertMessage;
	}

	// Handle Warning
	if (alertStatus === "warning") {
		omniAlertModal.classList.add('omni-modal--warning');
		omniModalText.innerHTML = alertMessage;
	}

	// Hide modal after 5 seconds
	setTimeout(function () {
		omniAlertModal.classList.remove('omni-modal--show');
	}, 5000);
}


/**
 * Load functions on page loaded
 */
document.addEventListener('DOMContentLoaded', function() {
	omniAlertHandler();
});