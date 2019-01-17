jQuery(document).ready(function($) {
    "use strict"; // Start of use strict
	
	$('#feedback').hide();
	$('#processing').hide();
	$('#response').hide();
		
	// Mobile Menu Button
	$('.navbar-toggle').click(function(){
		$(this).toggleClass('open');
		$('.side-collapse').toggleClass('show');
	});
	
	// Mobile Slide Out Menu
	var sideslider = $('[data-toggle=collapse-side]');
	var sel = sideslider.attr('data-target');
	var sel2 = sideslider.attr('data-target-2');
	sideslider.click(function(){
		$(sel).toggleClass('in');
		$(sel2).toggleClass('out');
	});
	
	$('body').on('click', '.datepicker', function() {
		$(this).datepicker({
			showButtonPanel: true,
			dateFormat: 'mm/dd/yy',
			changeMonth: true,
			changeYear: true,
			showAnim: 'slideDown'
		});
		$(this).datepicker('show');
	});
	
	/*$('.timepicker').timepicker({
		timeFormat: 'h:mm p',
		interval: 60,
		minTime: '9',
		maxTime: '6:00pm',
		defaultTime: '9',
		startTime: '9:00am',
		dynamic: false,
		dropdown: true,
		scrollbar: true
	});*/
	
	// Handle Combo Boxes
	if ($('input[list]').length > 0){
		document.querySelector('input[list]').addEventListener('input', function(e) {
			var input = e.target,
				list = input.getAttribute('list'),
				options = document.querySelectorAll('#' + list + ' option'),
				hiddenInput = document.getElementById(input.id + '-hidden'),
				inputValue = input.value;
			hiddenInput.value = inputValue;
			for(var i = 0; i < options.length; i++) {
				var option = options[i];
				if (option.getAttribute('data-onetime')==1) {
					$('.oneTime .oneTimeCheck').prop('checked', true);	
				} else {
					$('.oneTime .oneTimeCheck').prop('checked', false);
				}
				if(option.innerText === inputValue) {
					hiddenInput.value = option.getAttribute('data-value');
					//$('.oneTime').addClass('hide');
					break;
				} else {
					$('.oneTime .oneTimeCheck').prop('checked', false);
				}
			}
		});
	}
	
	$(function () {
		$('[data-toggle="tooltip"]').tooltip();
	});
	
	
	/*$('#tabs').responsiveTabs({
		startCollapsed: 'accordion',
		animation: 'slide',
		duration: 500,
		scrollToAccordion: true
	});*/
	
	// Registration Form
	$('#registerForm').validate({
		rules: {
			password: "required",
			password_repeat: {
				equalTo: "#password"
			}
		},
		submitHandler: function(form) {
			var ajaxUrl = '/ajax/admin/register.php'; 
			var formData = $(form).serialize();
			$.ajax({
				type: 'POST',
				url: ajaxUrl,
				data: formData
			})
			.done(function(data){ // if getting done then call.
				$('#feedback').show();
				$('#processing').show();
				if (data.output.accountCreated) {
					$('#processing').hide();
					$('#response').show().html(data.output.feedback);
					if (data.output.accountCreated == 'true') {
						if (data.output.redirect) {
							window.location.replace(data.output.redirect);
						}
						if (data.output.updateString) {
							$('#searchTable').append(data.output.updateString);
							$('#registerForm').trigger('reset');
							$('#addUser').removeClass('show');
						}
					} else {
						console.log('FAILURE');
					}
					setTimeout(function() {
						$('#feedback').fadeOut();
					}, 2000);
				} else {
					console.log('NO ACCOUNT');
				}
			})
			.fail(function() { 
				console.log('FAILURE 3');
			});
			return false;
		}
	});
	
	// Handle Admin Forms
	var checkboxes = '';
	var checkbox_names = '';
	if ($('.require-one')[0]) {
		checkboxes = $('.require-one');
		checkbox_names = $.map(checkboxes, function(e, i) { return $(e).attr("name") }).join(" ");
	}
	$.validator.addMethod('require-one', function(value) { return $('.require-one:checked').size() > 0; }, 'Please check at least one');
	$.validator.setDefaults({
		debug: true,
		groups: { checks: checkbox_names },
		rules: {
			password_repeat: {
				equalTo: "#password"
			}
		},
		errorPlacement: function(error, element) {
			if (element.attr('type') == 'checkbox') {
				error.insertAfter('.checkbox-group');
			} else {
				error.insertAfter(element);
			}
		},
		success: 'valid'
	});
	var form = $('.adminForm');
	form.validate();
	$('body').on('click', '.adminForm .adminBtn', function() {
		if (form.valid()) {
			$('#feedback').show();
			$('#processing').show();
			var op = $(this).data('op');
			var frm = $(this).closest('.adminForm');
			var ajaxUrl = '/ajax/admin/'+ op +'.php'; 
			var formData = 'op=' + op + '&' + $(frm).serialize();
			//formData+ "&op=" + op;
			$.ajax({
				type: 'POST',
				url: ajaxUrl,
				data: formData
			})
			.done(function(data){ // if getting done then call.
				if (data.output.op){
					if (data.output.op == 'update') {
						$('#processing').hide();
						console.log(data.output.feedback);
						$('#response').show().html(data.output.feedback);
						if (data.output.redirect) {
							setTimeout(function() {
								$('#feedback').hide();
								window.location.replace(data.output.redirect);
							}, 2000);
						} else {
							setTimeout(function() {
								$('#feedback').fadeOut();
							}, 2000);
						}
					} else if (data.output.op == 'add') {
						$('#list-group-edit').append(data.output.updateString);
						$('#processing').hide();
						$('#response').show().html(data.output.feedback);
						$('.add-form').trigger('reset');
						$('.collapse-form').removeClass('show');
						if (data.output.redirect) {
							setTimeout(function() {
								window.location.replace(data.output.redirect);
							}, 2000);
						} else {
							setTimeout(function() {
								$('#feedback').fadeOut();
							}, 2000);
						}
					} else if (data.output.op == 'delete') {
						$('#processing').hide();
						$('#response').show().html(data.output.feedback);
						var deleteID = '#form-'+ data.output.update;
						$.when($(deleteID).animate({ backgroundColor: '#fbc7c7' }, 'fast').animate({ opacity: 'hide' }, 'slow')).then(function() { 
							$(deleteID).remove();
							$('#feedback').fadeOut();
						});
					}
				} else {
					$('#response').html('No Output');
				}
			})
			.fail(function() { // if fail then getting message
				$('#response').html('POST FAILED');
			});
			return false;
		}
	});
	
	// Update Groups when period changed on Activity Admin Froms
	$('body').on('change','form.activity-admin .period-select', function() {
		var selected = $(this).children("option:selected").val(); 
		var ajaxUrl = '/ajax/admin/comparePeriodGroup.php'; 
		var formData = "period=" + selected;
		$.ajax({
			type: 'POST',
			url: ajaxUrl,
			data: formData
		})
		.done(function(data){ 
			if (data.output.groups){
				var grps = (data.output.groups).split(',');
				$('form.activity-admin .group-select').val(grps);
			}
			if (data.output.days){
				$.each(data.output.days,function(key,value) {
					if (parseInt(value) === 0) {
						$('form.activity-admin .'+ key +' input').attr('disabled', true);
						$('form.activity-admin .'+ key +' input').prop('checked', false);
						$('form.activity-admin .'+ key).addClass('text-muted');
					} else {
						$('form.activity-admin .'+ key +' input').attr("disabled", false);
						$('form.activity-admin .'+ key).removeClass('text-muted');
					}
				});
			}
		})
		.fail(function() { // if fail then getting message
			console.log('fail');
		});
	});
	
	// Handle Scheduling Forms
	var scheduleForm = $('.scheduleForm');
	scheduleForm.validate();
	$('body').on('click', '.scheduleForm .scheduleBtn', function() {
		if (scheduleForm.valid()) {
			$('#feedback').show();
			$('#processing').show();
			var op = $(this).data('op');
			var frm = $(this).closest('.scheduleForm');
			var ajaxUrl = '/ajax/schedule/'+ op +'.php'; 
			var formData = 'op=' + op + '&' + $(frm).serialize();
			
			console.log(ajaxUrl);
			console.log(formData);
			
			$.ajax({
				type: 'POST',
				url: ajaxUrl,
				data: formData
			})
			.done(function(data){ // if getting done then call.
				if (data.output.op){
					if (data.output.op == 'update') {
						$('#processing').hide();
						$('#response').show().html(data.output.feedback);
						console.log(data.output.feedback);
						if (data.output.redirect) {
							setTimeout(function() {
								$('#feedback').hide();
								window.location.replace(data.output.redirect);
							}, 2000);
						} else {
							setTimeout(function() {
								$('#feedback').fadeOut();
							}, 2000);
						}
					} else if (data.output.op == 'add') {
						console.log(data.output.feedback);
						//$('#list-group-edit').append(data.output.updateString);
						$('#processing').hide();
						$('#response').show().html(data.output.feedback);
						//$('.add-form').trigger('reset');
						//$('.collapse-form').removeClass('show');
						if (data.output.redirect) {
							setTimeout(function() {
								window.location.replace(data.output.redirect);
							}, 2000);
						} else {
							setTimeout(function() {
								$('#feedback').fadeOut();
							}, 2000);
						}
					} /*else if (data.output.op == 'delete') {
						$('#processing').hide();
						$('#response').show().html(data.output.feedback);
						var deleteID = '#form-'+ data.output.update;
						$.when($(deleteID).animate({ backgroundColor: '#fbc7c7' }, 'fast').animate({ opacity: 'hide' }, 'slow')).then(function() { 
							$(deleteID).remove();
							$('#feedback').fadeOut();
						});
					}*/
				} else {
					$('#response').html('No Output');
				}
			})
			.fail(function() { // if fail then getting message
				$('#response').html('POST FAILED');
			});
			return false;
		}
	});
	
	// Link Form Submits
	$('body').on('click', '.submitLink', function() {
		console.log('Clicky');
		$(this).parent('form').submit();
		return false;
	});
	
	$('body').on('click', '.scroll-link', function(event) {
		var anchorLink = $(this).attr('href');
		$('html,body').stop().animate({
			scrollTop: ($(anchorLink).offset().top - 50)
		}, 1250, 'easeInOutExpo');
		event.preventDefault();
	});

	// Data Tables
	$('.table-data-table').DataTable();
	$('.dataTables_length').addClass('bs-select');
	
	
	/*$('body').on('click', '.schedule-radio', function() {
		console.log('click id: '+ $(this).attr('id'));
		//$(this).closest('.radio-list').children('.schedule-button').not(this).removeClass('active'); 
		//$(this).parent('.schedule-button').addClass('active');
	});*/
	
	
	/*$('body').on('click', '.btn-register', function() { 
		$('#register .first-input').focus();
	});*/
});