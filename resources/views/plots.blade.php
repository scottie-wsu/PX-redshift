@extends('layouts.app')
@section('title','Plots')
@section('content')

	<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<style>
			h2 {
				display: inline-block;
			}
			html {
                height: 100%;
            }

			.container-custom {
				height: 50%;
				width: 50%;
				margin: 5rem auto;
				border: 1px red dotted;
				padding: 5rem auto;
			}

			.custom-margin {
				margin-bottom: 15px;
			}
		</style>
	</head>
		<body>
		<div class="container">
            <h2>Make a Plot</h2>
			<form class="form-inline" action="/fetch-plot" method="post">
			@csrf <!-- {{ csrf_field() }} -->
				<select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref" name = "selected-plot">
					@php
					
					foreach($availablePlots as $key => $val) {
					@endphp
					<option value="@php echo $key; @endphp">@php echo $val; @endphp</option>
					@php } @endphp
				</select>
				<button type="submit" class="btn btn-primary my-1">Plot</button>
			</form>
			@php if (isset($data)) {
			echo json_encode($labels) . PHP_EOL;
			echo json_encode($data) . PHP_EOL;
			@endphp
			<script>var chartTitle = <?php echo json_encode($title, JSON_HEX_TAG); ?>;</script>
			<script>var chartLabels = <?php echo json_encode($labels, JSON_HEX_TAG); ?>;</script>
			<script>var chartData = <?php echo json_encode($data, JSON_HEX_TAG); ?>;</script>
			<canvas id="myChart"></canvas>
			@php } @endphp

        </div>

		<!-- // $jobCountPerUser = DB::select("select count(jobs.job_id) as 'job count', users.id from jobs, users where jobs.user_id = users.id GROUP by users.id");
// $jobCountPerInstitution = DB::select("select count(jobs.job_id) as 'job count', users.institution from jobs, users where jobs.user_id = users.id GROUP by users.institution");
// $userCountPerInstitution = DB::select("select count(users.id) as 'users_count', users.institution from users GROUP by users.institution");
// $calculationCountPerUser = DB::select("select count(calculations.real_calculation_id) as 'calculations_count', users.id from users, jobs, redshifts, calculations where calculations.galaxy_id = redshifts.calculation_id AND redshifts.job_id = jobs.job_id AND jobs.user_id = users.id GROUP by users.id");
// $calculationCountPerJob = DB::select("select count(calculations.real_calculation_id) as 'calculations_count', redshifts.job_id from redshifts, calculations where calculations.galaxy_id = redshifts.calculation_id GROUP by redshifts.job_id");
// $calculationCountPerInstitution = DB::select("select count(calculations.real_calculation_id) as 'calculations_count', users.institution from users, jobs, redshifts, calculations where calculations.galaxy_id = redshifts.calculation_id AND redshifts.job_id = jobs.job_id AND jobs.user_id = users.id GROUP by users.institution");
// $calculationCountPerMethod = DB::select("select count(calculations.real_calculation_id) as 'calculations_count', calculations.method_id from calculations GROUP by calculations.method_id"); -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js"></script>
		<script src="{{ asset('js/generate-plot.js') }}"></script>
	</body>
@endsection
