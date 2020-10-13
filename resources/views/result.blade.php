@extends('layouts.app_boot')
@section('title','Result')
@section('content')
	<style>
		h2 {
		display: inline-block;
		}
	</style>
	<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" >
		<div class="container">
			<a class="nav-link" href="{{ route('home') }}"><h2 id="redshift" style="color:red">Red</h2><h2 id="redshiftEstimator">shift</h2></a>

			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<!-- Left Side Of Navbar -->
				<li class="nav-item">

				</li>
				<ul class="navbar-nav mr-auto">

				</ul>

				<!-- Right Side Of Navbar -->
				<ul class="navbar-nav ml-auto" >
					<!-- Authentication Links -->
					@guest

						<li class="nav-item">
							<a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
						</li>



					@endguest
				</ul>
			</div>
		</div>
	</nav>

<body>
	<script src="{{ asset('vendor/jquery/jquery-3.2.1.js') }}"></script>
	<script>
		function getCount() {
			$.ajax({
				type: "GET",
				url: "{{ route('guestAjax') }}",
				data: {
					job: {{$jobId}},
				}
			})
				.done(function( data1 ) {
					if($.isNumeric(data1[0])){
						$('#waiting').hide();
						$('#result').show();
						$('#result').text(data1[0]);
					}
					else{
						if(data1 != 'WAITING'){
							if(data1[1] != ''){
								$('#waiting').hide();
								$('#result').show();
								$('#result').html("<a href="+data1[1]+">Results</a>");
							}
							else{
								$('#result').hide();
								$('#waiting').show();
							}
						}



					}
					setTimeout(getCount, 2000);
				});

		}
		getCount();
	</script>

	<div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins" style="background-image: url({{ asset('images/bg1.jpg') }})">
		<div class="wrapper wrapper--w680">
			<div class="card card-4">
				<div class="card-body" >
					<h3>Guest calculation results</h3>
					<br>
					<span id="waitingmsg">This page will update automatically with a result. Please wait and do not refresh the page.</span>
					<br>
					<br>
					<h4>Your result is:</h4>
					<br>
					<b style="font-size: large">
					<div id="waiting" class="spinner-border justify-content-center" role="status">
						<span class="sr-only">Loading...</span>
					</div>
					<span id="result"></span>
					</b>
					<br>
				</div>
			</div>
		</div>
	</div>
</body>


        	@endsection

