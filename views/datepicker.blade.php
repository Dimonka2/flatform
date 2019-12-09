@section('footer')
@parent
<script>
	$(document).ready(function () {
		$('.datepicker').datepicker({
		dateFormat: '{{ config('app.date_format_js') }}'
		});
	});
</script>
@endsection