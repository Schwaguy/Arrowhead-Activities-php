jQuery(document).ready(function($) {
    "use strict"; // Start of use strict
	
	var siteURL = '/'; 
	
	$('#feedback').hide();
	$('#processing').hide();
	$('#response').hide();
	
	// Stop video PLayback on modal close
	$('body').on('hidden.bs.modal', '.video-modal', function () { 
		$('.video-modal').find('video').trigger('pause'); 
	});
		
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
	
	// Post Link
	$('body').on('click','.postLink', function() {
		var linkPage = $(this).attr('href');
		var keys = ($(this).data('keys')).split(',');
		var values = ($(this).data('values')).split(',')
		var postObject = {};
		$.each(keys, function(i, item) {
			postObject[keys[i]] = values[i];
		});
		$.redirect(linkPage, postObject);
		return false;
	});
	
	$('body').on('click','.showPW', function() {
		var pwField = $(this).data('pw-field');
		if ($(pwField).attr('type') === 'password') {
			$(pwField).attr('type', 'text');
		} else {
			$(pwField).attr('type', 'password');
		}
	});
	
	// Check for existing username
	$('body').on('blur', '.input-username', function() {
		var username = $(this).val();
		var fb = $(this).closest('.feedback');
		var ajaxUrl = siteURL + 'ajax/admin/checkUsername.php'; 
		var formData = 'username='+ username;
		//console.log(ajaxUrl +'?'+ formData);
		$.ajax({
			type: 'POST',
			url: ajaxUrl,
			data: formData,
			dataType: 'json'
		})
		.done(function(data){ // if getting done then call.
			//console.log('FEEDBACK: '+ data.output.usernameExists);
			if (data.output.usernameExists == 'yes') {
				$('#username-exists').html(data.output.feedback);
				$('#username-exists').show('slow');
				$('#register-submit').prop('disabled', true);
			} else {
				$('#username-exists').hide('slow');
				$('#register-submit').prop('disabled', false);
			}
		})
		.fail(function(xhr, ajaxOptions, thrownError) { // if fail then getting message
			console.log(xhr.status);
        	console.log(thrownError);
		});
		return false;
	});
	
	// Check for existing User
	$('body').on('blur', '.input-firstName, .input-lastName', function() {
		var form = $(this).parents('.registerForm');
		var firstName = $(form).find('.input-firstName').val();
		var lastName = $(form).find('.input-lastName').val();
		var email = $(form).find('.input-email').val();
		var ajaxUrl = siteURL + 'ajax/admin/checkUserExist.php'; 
		var formData = 'firstName='+ firstName +'&lastName='+ lastName +'&email='+ email;
		if ((firstName) && (lastName)) {
			//console.log(ajaxUrl +'?'+ formData);
			$.ajax({
				type: 'POST',
				url: ajaxUrl,
				data: formData,
				dataType: 'json'
			})
			.done(function(data){ // if getting done then call.
				if (data.output.userExists == 'yes') {
					$('#name-exists').html(data.output.feedback);
					$('#name-exists').show('slow');
				} else {
					$('#name-exists').hide ('slow');
				}
			})
			.fail(function(xhr, ajaxOptions, thrownError) { // if fail then getting message
        		console.log(xhr.status);
        		console.log(thrownError);
			});
		}
		return false;
	});
	
	// Registration Form
	$('#registerForm').validate({
		rules: {
			password: "required",
			password_repeat: {
				equalTo: "#password"
			}
		},
		submitHandler: function(form) {
			var ajaxUrl = siteURL + 'ajax/admin/register.php'; 
			var formData = $(form).serialize();
			//console.log(ajaxUrl);
			//console.log(formData);
			$('#feedback').show();
			$('#processing').show();
			$.ajax({
				type: 'POST',
				url: ajaxUrl,
				data: formData,
				dataType: 'json'
			})
			.done(function(data){ // if getting done then call.
				//$('#feedback').show();
				//$('#processing').show();
				if (data.output.accountCreated) {
					//console.log('Account Created!!!');
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
	$('body').on('click','#btnRegister',function() {
		$('#forgot').removeClass('show');
	});
	
	// Forgot Password Form
	$('#forgotForm').validate({
		rules: {
			usernameForgot: {
				required: function(element) {
					return $("#emailForgot").is(':blank');
				}
			},
			emailForgot: {
				required: function(element) {
					return $("#usernameForgot").is(':blank');
				}
			}
		},
		submitHandler: function(form) {
			var ajaxUrl = siteURL + 'ajax/admin/password.php'; 
			var formData = $(form).serialize();
			//console.log(ajaxUrl);
			//console.log(formData);
			$('#feedback').show();
			$('#processing').show();
			$.ajax({
				type: 'POST',
				url: ajaxUrl,
				data: formData,
				dataType: 'json'
			})
			.done(function(data){ // if getting done then call.
				//$('#feedback').show();
				//$('#processing').show();
				if (data.output.feedback) {
					$('#processing').hide();
					$('#response').show().html(data.output.feedback);
					if (data.output.redirect) {
						window.location.replace(data.output.redirect);
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
	$('body').on('click','#btnForgot',function() {
		$('#register').removeClass('show');
	});
	
	// Reset Password Form
	$('#resetForm').validate({
		rules: {
			password: "required",
			password_repeat: {
				equalTo: "#password"
			}
		},
		submitHandler: function(form) {
			var ajaxUrl = siteURL + 'ajax/admin/password.php'; 
			var formData = $(form).serialize();
			$('#feedback').show();
			$('#processing').show();
			$.ajax({
				type: 'POST',
				url: ajaxUrl,
				data: formData,
				dataType: 'json'
			})
			.done(function(data){ // if getting done then call.
				//$('#feedback').show();
				//$('#processing').show();
				if (data.output.feedback) {
					$('#processing').hide();
					$('#response').show().html(data.output.feedback);
					if (data.output.redirect) {
						window.location.replace(data.output.redirect);
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
			} else if (element.attr('type') == 'radio') {
				error.insertAfter(element.closest('.activity-signup-buttons'));
			} else {
				error.insertAfter(element);
			}
		},
		success: 'valid'
	});
	var form = $('.adminForm');
	form.validate();
	$('body').on('click', '.adminForm .adminBtn', function() {
		//console.log('Admin Link');
		if (form.valid()) {
			$('#feedback').show();
			$('#processing').show();
			var op = $(this).data('op');
			var frm = $(this).closest('.adminForm');
			var ajaxUrl = siteURL + 'ajax/admin/'+ op +'.php'; 
			var formData = 'op=' + op + '&' + $(frm).serialize();
			//console.log(ajaxUrl);
			//console.log(formData);
			$.ajax({
				type: 'POST',
				url: ajaxUrl,
				data: formData,
				dataType: 'json'
			})
			.done(function(data){ // if getting done then call.
				if (data.output.op) {
					if (data.output.op == 'update') {
						$('#processing').hide();
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
						if (data.output.redirect) {
							setTimeout(function() {
								window.location.replace(data.output.redirect);
							}, 2000);
						} else {
							$.when($(deleteID).animate({ backgroundColor: '#fbc7c7' }, 'fast').animate({ opacity: 'hide' }, 'slow')).then(function() { 
								$(deleteID).remove();
								$('#feedback').fadeOut();
							});
						}
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
	
	// Print Report
	$('body').on('click', '.printLink', function() {
		//console.log('PRINT LINK');
		$('#feedback').show();
		$('#processing').show();
		var camper = ($(this).data('camper') ? $(this).data('camper') : '');
		var bunk = ($(this).data('bunk') ? $(this).data('bunk') : '');
		var week = ($(this).data('week') ? $(this).data('week') : '');
		var activity = ($(this).data('activity') ? $(this).data('activity') : '');
		var signup = ($(this).data('signup') ? (($(this).data('signup') == 'no') ? 'false' : 'true') : 'true');
		var date = ($(this).data('date') ? $(this).data('date') : '');
		var ajaxUrl = siteURL + 'ajax/report/print-schedule.php'; 
		var formData = 'camper='+ camper +'&week='+ week +'&bunk='+ bunk +'&activity='+ activity +'&date='+ date +'&signup='+ signup;
		$.ajax({
			type: 'POST',
			url: ajaxUrl,
			data: formData,
			dataType: 'json'
		})
		.done(function(data){ // if getting done then call.
			//console.log('Data Returned');
			if (data.output){
				//console.log('Output Returned');
				var myWindow=window.open('','','');
				var isChrome = false;
				if (navigator.userAgent.toLowerCase().indexOf("chrome") > -1) {
					isChrome = true;
				}
				myWindow.document.write('<!doctype html><html><head><meta charset="utf-8">');
				myWindow.document.write(data.output.header);
				myWindow.document.write('</head><body>');
				if (isChrome) {
					myWindow.document.write('<div class="container"><div class="text-right"><a class="btn btn-primary btn-print" onclick="printPage()">Print Page</a></div></div>');
				}
				myWindow.document.write(data.output.body);
				if (isChrome) {
					myWindow.document.write("<script>function printPage() { window.print(); window.close(); }</script>");
				}
				myWindow.document.write('</body></html>');
				myWindow.document.close();
				myWindow.focus(); // necessary for IE >= 10
				if (!isChrome) {
					myWindow.print();
					myWindow.close();
				}
				$('#feedback').hide();
				$('#processing').hide();
				return true;
			} else {
				//console.log('No Output');
				$('#response').html('No Output');
			}
		})
		.fail(function() { // if fail then getting message
			//console.log('POST FAILED');
			$('#response').html('POST FAILED');
		});
		return false;
	});
	
	function checkReadyState(myWindow,myDelay) {
		if (myWindow.document.readyState == "complete") {
			clearInterval(myDelay);
			myWindow.focus(); // necessary for IE >= 10
			myWindow.print();
			myWindow.close();
		}
	}
	
	// Disable One-Time-Only Options when one is selected
	$('body').on('click','form.scheduleForm .activity-signup-buttons .schedule-button', function() {
		var thisForm = $('form.scheduleForm');
		var $thisBtn = $(this).parent('.schedule-item');
		var $thisBtnGrp = $(this).parents('.activity-signup-buttons');
		$thisBtnGrp.children('.schedule-item').not($thisBtn).each(function(){
			$(this).children('.schedule-button').removeClass('active');
			$(this).children('.schedule-radio').removeClass('active');
			$(this).children('.schedule-radio').removeAttr('checked');
		});
		if ($(this).data('onetime')) {
			var btnClass = '.'+ $(this).data('onetime');
			$(btnClass).not($thisBtn).each(function(){
				$(this).addClass('disabled');
				$(this).children('.schedule-radio').prop('checked', false);
				$(this).children('.schedule-radio').prop('disabled', true);
				$(this).children('.schedule-button').addClass('disabled');
			});
		} else {
			var btnGroup = $(this).closest('.activity-signup-buttons');
			var oneTimers = new Array();
			if ($(btnGroup).find('.onetime').length !== 0) {
				$(btnGroup).find('.onetime .schedule-button').each(function() {
					var btnClass = $(this).data('onetime');
					$(this).prop('checked', false);
					if($.inArray(btnClass, oneTimers) === -1) {
						if ($(this).data('previous')!=='yes') {
							oneTimers.push(btnClass);
						}
					}
				});
				if (oneTimers.length !== 0) {
					oneTimers = filter_array(oneTimers);
					$.each(oneTimers, function(key,value) {
						var onceler = '.'+ value;
						var ckd = 0;
						$(thisForm).find(onceler + ' .schedule-radio').each(function() {
							if ($(this).prop('checked')) {
								ckd = ckd+1;
							} 
						});
						if (ckd === 0) {
							$(onceler).removeClass('disabled');
							$(onceler).children('.schedule-button').removeClass('disabled');
						} 	
					});
				} else {
					console.log('array is empty');
				}
			}
		}
	});
	
	// Remove Empty Elements form Array
	function filter_array(test_array) {
		var index = -1,
			arr_length = test_array ? test_array.length : 0,
			resIndex = -1,
			result = [];
		while (++index < arr_length) {
			var value = test_array[index];

			if (value) {
				result[++resIndex] = value;
			}
		}
		return result;
	}

	// Update Groups when period changed on Activity Admin Froms
	$('body').on('change','form.activity-admin .period-select', function() {
		var selected = $(this).children("option:selected").val(); 
		var ajaxUrl = siteURL + 'ajax/admin/comparePeriodGroup.php'; 
		var formData = "period=" + selected;
		$.ajax({
			type: 'POST',
			url: ajaxUrl,
			data: formData,
			dataType: 'json'
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
			$(this).html('Schedule Complete').attr('disabled', true);	
			var op = $(this).data('op');
			var frm = $(this).closest('.scheduleForm');
			var ajaxUrl = siteURL + 'ajax/schedule/'+ op +'.php'; 
			var formData = 'op=' + op + '&' + $(frm).serialize();
			//console.log(ajaxUrl + '?' + formData);
			$.ajax({
				type: 'POST',
				url: ajaxUrl,
				data: formData,
				dataType: 'json'
			})
			.done(function(data){ // if getting done then call.
				//console.log('Done');
				if (data.output.op) {
					//console.log('Op');
					if (data.output.op == 'update') {
						//console.log('Update');
						$('#processing').hide();
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
						$('#processing').hide();
						$('#response').show().html(data.output.feedback);
						if (data.output.redirect) {
							setTimeout(function() {
								window.location.replace(data.output.redirect);
							}, 2000);
						} else {
							setTimeout(function() {
								$('#feedback').fadeOut();
							}, 2000);
						}
					} 
				} else {
					//console.log('ELSE');
					$('#response').html('No Output');
				}
			})
			.fail(function() { // if fail then getting message
				//console.log('FAIL');
				$('#response').html('POST FAILED');
			});
			return false;
		}
	});
	
	// Link Form Submits
	$('body').on('click', '.submitLink', function() {
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
	
	$('body').on('click', '.btn-clear', function() { 
		var clearFunction = $(this).data('funct'); 
		var alertMessage = $(this).data('alert'); 
		if (confirm(alertMessage)) {
			if (clearFunction === 'clearBunks') {
				clearBunks(this);
			} else if (clearFunction === 'clearSchedule') {
				clearSchedule(this);	
			} else if (clearFunction === 'clearSignups') {
				clearSignups(this);	
			}
		} 
	});
	
	function clearSchedule(thisBtn) {
		$('#feedback').show();
		$('#processing').show();
		var op = $(thisBtn).data('op');
		var user = $(thisBtn).data('user');
		var week = $(thisBtn).data('week');
		var activity = $(thisBtn).data('activity');
		var ajaxUrl = siteURL + 'ajax/schedule/'+ op +'.php';
		//console.log(ajaxUrl);
		var formData = 'user=' + user + '&week=' + week + '&activity=' + activity;
		//console.log(formData);
		$.ajax({
			type: 'POST',
			url: ajaxUrl,
			data: formData,
			dataType: 'json'
		})
		.done(function(data){ // if getting done then call.
			if (data.output.op){
				$('#processing').hide();
				$('#response').show().html(data.output.feedback);
				if (data.output.result == 'success') {
					setTimeout(function() {
						$('#feedback').fadeOut();
						if (data.output.info) {
							if (data.output.info.activity) {
								$('#activity-signups').find('td').each(function() {
									$(this).html('<p class="text-muted"><em>No Signups Yet</em></p>');
								});
							} else if (data.output.info.week) {
								//console.log('WEEK');
								$('#week-' + data.output.info.week).find('.period').each(function() {
									$(this).children('form').removeClass('scheduled-activity');
									var $thisInput = $(this).find('input.event');
									$thisInput.removeClass('btn-block btn-light-green d-block').addClass('btn-light').val('Click to Schedule Activities');
								});
							}
							setTimeout(function() {
								$('#feedback').fadeOut();
							}, 2000);
						} else {
							window.location.reload();
						}
					}, 2000);
				} else {
					setTimeout(function() {
						$('#feedback').fadeOut();
					}, 2000);
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
	
	// Clear All Bunk Assignments
	function clearBunks(thisBtn) {
		$('#feedback').show();
		$('#processing').show();
		var op = $(thisBtn).data('op');
		//var user = $(thisBtn).data('user');
		//var bunk = $(thisBtn).data('bunk');
		var ajaxUrl = siteURL + 'ajax/bunks/'+ op +'.php'; 
		var formData = 'op=' + op;
		//console.log(ajaxUrl);
		//console.log(formData);
		$.ajax({
			type: 'POST',
			url: ajaxUrl,
			data: formData,
			dataType: 'json'
		})
		.done(function(data){ // if getting done then call.
			if (data.output.op){
				$('#processing').hide();
				$('#response').show().html(data.output.feedback);
				if (data.output.result == 'success') {
					setTimeout(function() {
						$('#feedback').fadeOut();
						//console.log(data.output.updateString);
					}, 2000);
				} else {
					//console.log('ERROR');
					setTimeout(function() {
						$('#feedback').fadeOut();
					}, 2000);
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
	
	// Clear All Activity Signups
	function clearSignups(thisBtn) {
		$('#feedback').show();
		$('#processing').show();
		var op = $(thisBtn).data('op');
		var ajaxUrl = siteURL + 'ajax/schedule/'+ op +'.php'; 
		var formData = 'op=' + op;
		//console.log(ajaxUrl);
		//console.log(formData);
		$.ajax({
			type: 'POST',
			url: ajaxUrl,
			data: formData,
			dataType: 'json'
		})
		.done(function(data){ // if getting done then call.
			if (data.output.op){
				$('#processing').hide();
				$('#response').show().html(data.output.feedback);
				if (data.output.result == 'success') {
					setTimeout(function() {
						$('#feedback').fadeOut();
						//console.log(data.output.updateString);
					}, 2000);
				} else {
					//console.log('ERROR');
					setTimeout(function() {
						$('#feedback').fadeOut();
					}, 2000);
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