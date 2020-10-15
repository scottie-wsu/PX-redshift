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

	@php
		$arrayMethods = [];
		use Illuminate\Support\Facades\DB;
		use App\methods;
		$data = session()->all();
		dump($data);
		$assigned_calc_ID = strval(session()->get('assigned_calc_ID'));
		$optical_u = session()->get('optical_u');
		$optical_v = session()->get('optical_v');
		$optical_g = session()->get('optical_g');
		$optical_r = session()->get('optical_r');
		$optical_i = session()->get('optical_i');
		$optical_z = session()->get('optical_z');
		$infrared_three_six = session()->get('infrared_three_six');
		$infrared_four_five = session()->get('infrared_four_five');
		$infrared_five_eight = session()->get('infrared_five_eight');
		$infrared_eight_zero = session()->get('infrared_eight_zero');
		$infrared_J = session()->get('infrared_J');
		$infrared_H = session()->get('infrared_H');
		$infrared_K = session()->get('infrared_K');
		$radio_one_four = session()->get('radio_one_four');
		$methods = session()->get('methods');
	@endphp

	<script src="{{ asset('vendor/jquery/jquery-3.2.1.js') }}"></script>
	<script>
		function guestResult() {
			var array = {
				data:{
					assigned_calc_ID : "@php echo $assigned_calc_ID @endphp",
					optical_u : @php echo $optical_u @endphp,
					optical_v : @php echo $optical_v @endphp,
					optical_g : @php echo $optical_g @endphp,
					optical_r : @php echo $optical_r @endphp,
					optical_i : @php echo $optical_i @endphp,
					optical_z : @php echo $optical_z @endphp,
					infrared_three_six : @php echo $infrared_three_six @endphp,
					infrared_four_five : @php echo $infrared_four_five @endphp,
					infrared_five_eight : @php echo $infrared_five_eight @endphp,
					infrared_eight_zero : @php echo $infrared_eight_zero @endphp,
					infrared_J : @php echo $infrared_J @endphp,
					infrared_H : @php echo $infrared_H @endphp,
					infrared_K : @php echo $infrared_K @endphp,
					radio_one_four : @php echo $radio_one_four @endphp,
				},
			};
			array.methods = [];
			@foreach($methods as $method)
			@php
				echo 'array.methods.push('.$method.');
';
			@endphp
			@endforeach

			var arrayNew = JSON.stringify(array);

			$.ajax({
				type: "POST",
				url: "https://redshift-01.cdms.westernsydney.edu.au/redshift/api/guest/",
				dataType: 'json',
				contentType: "application/json; charset=utf-8",
				traditional: true,
				data: arrayNew,
				processData: false,
				headers: {
					//'Access-Control-Allow-Origin': '*',
				},
			})
				.done(function( data1 ) {
					$("#waiting").hide();
					@foreach($methods as $method)
					@php
						$arrayMethods[] = methods::select('method_name')->where('method_id', $method)->first();
					@endphp
					@endforeach
					var test = data1.result[2].redshift_result;
					console.log(test);

					console.log(array.methods);
					array.methods.forEach(function(currentValue, index, arr){
						if(data1.result[currentValue] == null){
							console.log(currentValue);
							console.log('test');
							console.log(data1.result[currentValue]);

						}
						else{
							var redshift_result = data1.result[currentValue].redshift_result;
							var redshift_alt_result = data1.result[currentValue].redshift_alt_result;

							console.log(currentValue);
							console.log('elsehere');
							console.log(data1.result[currentValue].redshift_result);
							if(redshift_result != null){
								$("#resultArea").after("<div id='result"+currentValue+"'>Result for method {{$arrayMethods[0]->method_name}} is "+redshift_result+"</div><br>");
							};
							if(redshift_alt_result != null){
								$("#resultArea").after("<div id='result"+currentValue+"'>File result for method {{$arrayMethods[0]->method_name}} is "+redshift_alt_result+"</div><br>");
							};
						};


					});
					//setTimeout(getCount, 2000);
				});


		}
		guestResult();
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
						<span id="resultArea"></span>
					</b>
					<br>
				</div>
			</div>
		</div>
	</div>
	</body>


@endsection

