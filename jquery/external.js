$(document).ready(function() {


	$('.dropdown-submenu a.test').on("click", function(e){
    	$(this).next('ul').toggle();
    	e.stopPropagation();
    	e.preventDefault();
 	});
	
	var onResize = function(){
		
		$('body').css('padding-top', $('.navbar-fixed-top').height());
	}

	$(window).resize(onResize);

	$(function(){
		onResize();
    	$( "#menu" ).menu();
		$('.date_picker').datepicker({dateFormat: 'dd-mm-yy'});


		if ($('.exam_radio').is(':checked')) {
			var answer = $('.exam_radio').siblings('#correct').val();
			$('#answer').val(answer);
		}	
	});



	$('body').on('change', '#checkbox_select', function(event) {
		event.preventDefault();

		if ($(this).is(':checked')) {
			$(this).siblings('#correct').val(1);
		}else {
			$(this).siblings('#correct').val(0);
		}
	});

	$('body').on('change', '.exam_radio', function(event) {
		event.preventDefault();
			
		if ($(this).is(':checked')) {
			var answer = $(this).siblings('#correct').val();
			$('#answer').val(answer);
			var question_id = $('#question_id').val();
			var exam_id 	= $('#exam_id').val();
			var answer1 	= $('#answer').val();
			var option_id 	= $(this).siblings('#option_id').val();


			$.post('submit_option.php', {answer: answer1, exam_id: exam_id}, function(data) {

				$.post('submit_option.php', {question_id: question_id}, function(data) {

					$.post('submit_option.php', {option_id: option_id}, function(data) {

					});
					
				});

			});
		}	

	});

	$('body').on('click', '#next', function(event) {
		event.preventDefault();
		var next = ($(this).val());
		if (next =='next') {
			
			$.post('exam.php', {next: next}, function(data) {
				$('#exam_page').html(data);

				if ($('.exam_radio').is(':checked')) {
					var answer = $('.exam_radio').siblings('#correct').val();
					$('#answer').val(answer);
				}
			});
		}

		if (next == 'prev') {
			
			$.post('exam.php', {prev: next}, function(data) {
				$('#exam_page').html(data);

				if ($('.exam_radio').is(':checked')) {
					var answer = $('.exam_radio').siblings('#correct').val();
					$('#answer').val(answer);
				}
			});			
		}
	});

	$('body').on('click', '.remove', function(event) {
		event.preventDefault();
		$(this).closest('.option').remove();
		
	});


	$('body').on('click', '.more_option', function(event) {
		event.preventDefault();
		$('.option').last().after('<div class="row option"><div class="col-sm-9"><input type="text" name="option[]" id="option" class="form-control " placeholder="Add an option to select in the question"></div><div class="col-sm-2"><input type="hidden" name="correct[]" value="0" id="correct" class="correct"><input type="checkbox" id="checkbox_select" class="checkbox_select"></div><div></div><div><a href="#" class="remove text-danger"><span class="glyphicon glyphicon-remove"></span></a></div></div>');
	});

	$('body').on('click', '#menu_toggle', function(event) {
		event.preventDefault();
		$('#wrapper').toggleClass('menuDisplayed');

	});

	$('body').on('click', '.close', function(event) {
		event.preventDefault();
		$(this).closest('.alert').remove();
	});
	



	$('body').on('change', '.student_section', function(event) {
		event.preventDefault();


		if ($(this).val() === 'senior high') {
			
			$('.student_level_div').html('<label for="student_level">Select Student Level </label><select class="form-control student_level" id="student_level" name="level"><option value="" disabled selected>Select Student Level</option><option value="ss1">SSS 1</option><option value="ss2">SSS 2</option><option value="ss3">SSS 3</option></select>');

		}else if ($(this).val() === 'junior high') {

			$('.student_level_div').html('<label for="student_level">Select Student Level </label><select class="form-control student_level" id="student_level" name="level"><option value="" disabled selected>Select Student Level</option><option value="jss1">JSS 1</option><option value="jss2">JSS 2</option><option value="jss3">JSS 3</option></select>');	

		}else if ($(this).val() === 'primary') {

			$('.student_level_div').html('<label for="student_level">Select Student Level </label><select class="form-control student_level" id="student_level" name="level"><option value="" disabled selected>Select Student Level</option><option value="primary1">Primary 1</option><option value="primary2">Primary 2</option><option value="primary3">Primary 3</option><option value="primary4">Primary 4</option><option value="primary5">Primary 5</option><option value="primary6">Primary 6</option></select>');

		}else if ($(this).val() === 'nursery') {

			$('.student_level_div').html('<label for="student_level">Select Student Level </label><select class="form-control student_level" id="student_level" name="level"><option value="" disabled selected>Select Student Level</option><option value="nursery1">Nursery 1</option><option value="nursery2">Nursery 2</option><option value="nursery3">Nursery 3</option></select>');

		}
	});

	$('body').on('change', '.student_level', function(event) {
		event.preventDefault();
		if ($('.student_section').val() === 'senior high') {

			$('.student_dept_div').html('<label for="dept">Select Student Department </label><select class="form-control student_dept" id="dept" name="dept"><option value="" disabled selected>Select student Department</option><option value="art">Art</option><option value="science">Science</option></select>');

		}else {
			$('.student_dept_div').html('<label for="dept">Select Student Department </label><select class="form-control student_dept" id="dept" name="dept"><option value="" disabled selected>Select student Department</option><option value="none">None</option></select>');
		}
	});



	$('body').on('change', '.year', function(event) {
		event.preventDefault();
		var year = $(this).val();
		$.post('ajax.php', {year: year}, function(data) {
			$('.term_div').html(data);
		});
	});

	$('body').on('change', '.term', function(event) {
		event.preventDefault();
		var term = $(this).val();
		$.post('ajax.php', {term: term}, function(data) {
			$('.section_div').html(data);
		});
	});

	$('body').on('change', '.term1', function(event) {
		event.preventDefault();
		var term = $(this).val();
		$.post('ajax.php', {term1: term}, function(data) {
			$('.section_div').html(data);
		});
	});

	$('body').on('change', '.section', function(event) {
		event.preventDefault();
		var section = $(this).val();
		$.post('ajax.php', {section: section}, function(data) {
			$('.level_div').html(data);
		});
	});

	$('body').on('change', '.level', function(event) {
		event.preventDefault();
		var level = $(this).val();
		$.post('ajax.php', {level: level}, function(data) {
			$('.dept_div').html(data);
		});

	});


	$('body').on('change', '.dept', function(event) {
		event.preventDefault();
		var level 	= $('.level').val();
		var dept 	= $(this).val();
		var term 	= $('.term').val();
		$.post('ajax.php', {dept: dept, level2: level, term2: term}, function(data) {
			$('.subject_div').html(data);

			$.post('ajax.php', {dept3: dept, level3: level}, function(data) {
				$('.class_label_div').html(data);
			});

		});


		$.post('ajax.php', {dept3: dept, level3: level}, function(data) {
			$('.class_id').val(data);

		});

	});



	$('body').on('change', '#student_dept', function(event) {
		event.preventDefault();
		var level 	= $('.level').val();
		var dept 	= $(this).val();
		$.post('ajax.php', {dept: dept, level2: level}, function(data) {
			$('.subject_div').html(data);

			$.post('ajax.php', {dept: dept3, level: level3}, function(data) {
				$('.class_label_div').html(data);
			});

		});

	});

	$('body').on('change', '.level2', function(event) {
		event.preventDefault();
		var level 		= $(this).val();

		$.post('ajax.php', {level: level}, function(data) {
			$('.dept_div').html(data);

		});
	});

	$('body').on('change', '.write_exam_term', function(event) {
		event.preventDefault();
		var session_id 	= $(this).val();

		$.post('ajax.php', {session_id: session_id}, function(data) {
			$('.subject_div').html(data);

		});
	});





		<!-- Dialog show event handler -->
	$('#confirmDelete').on('show.bs.modal', function (e) {
		$message = $(e.relatedTarget).attr('data-message');
		$(this).find('.modal-body p').text($message);
		$title = $(e.relatedTarget).attr('data-title');
		$(this).find('.modal-title').text($title);
		// Pass form reference to modal for submission on yes/ok
		var form = $(e.relatedTarget).closest('form');
		$(this).find('.modal-footer #confirm').data('form', form);
	});
		<!-- Form confirm (yes/ok) handler, submits form -->
		$('#confirmDelete').find('.modal-footer #confirm').on('click', function(){
		$(this).data('form').submit();
	});
	
});