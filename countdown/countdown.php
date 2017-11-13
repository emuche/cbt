<script type="text/javascript" src="<?php echo $root_path; ?>countdown/jquery.plugin.min.js"></script>
<script type="text/javascript" src="<?php echo $root_path; ?>countdown/jquery.countdown.min.js"></script>
<script type="text/javascript">
	exam_time = $('#exam_time').val()+"s";
	$(function () {
		$('#defaultCountdown').countdown({until: exam_time, format: 'MS', expiryUrl: "end_exam.php", padZeroes: true});
	});
</script>

