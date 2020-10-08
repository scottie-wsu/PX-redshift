@extends('layouts.app')
@section('title','Calculation Progress')
@section('content')

	<head>
		<style>
			h2 {
				display: inline-block;
			}
		</style>
	</head>


	<body style="background-image: url({{ asset('images/bg1.jpg') }})">
		<script src="{{ asset('vendor/jquery/jquery-3.2.1.js') }}"></script>

		<span id="counter">
			@php print_r("Number of calculations currently queued: " . $inProgress) @endphp
		</span>

		<!-- search for pace in bundle.js for options. this.el=document.createElement("div"), was removed -->
		<script>
			function getCount() {
				$.ajax({
					type: "GET",
					url: "{{ route('progressAjax') }}",
				})
					.done(function( data ) {
						$('#counter').html(data);
						setTimeout(getCount, 2000);
					});
			}
			getCount();
		</script>

	</body>
@endsection
