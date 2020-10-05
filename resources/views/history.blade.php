@extends('layouts.app_boot')
@section('title','History')
@section('content')

	<head>
		<style>
			h2 {
				display: inline-block;
			}
		</style>
	</head>

	<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" >
		<div class="container">
			<a class="nav-link" href="{{ route('home') }}"><h2 id="redshift" style="color:red">Red</h2><h2 id="redshiftEstimator">shift</h2></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<!-- Left Side Of Navbar -->
				<ul class="navbar-nav mr-auto">
				</ul>

				<!-- Right Side Of Navbar -->
				<ul class="navbar-nav ml-auto" >
					<!-- Authentication Links -->

					@php
						use App\User;
						use Illuminate\Support\Facades\Auth;
						$user = Auth::user();
						$check = User::select('level')->where('id', $user->id)->get();
						$userChecker = $check[0]->level;

						//return ($userChecker == 1);
					@endphp

					@if($userChecker==1)
						<li class="nav-item">
							<a class="nav-link" href="{{ backpack_url('/') }}">{{ __('Admin Panel') }}</a>
						</li>
					@endif

					<li class="nav-item">
						<a class="nav-link" href="{{ route('history') }}">{{ __('History') }}</a>

					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('MyAccount') }}">{{ __('My Account') }}</a>

					</li>
					<li class="nav-item">

						<a class="nav-link" href="{{ route('logout') }}"
						   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
							{{ __('Logout') }}
						</a>

						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							@csrf
						</form>

					</li>

				</ul>
			</div>
		</div>
	</nav>

	<body style="background-image: url({{ asset('images/bg1.jpg') }})">

	<div class="overflow-auto">
		<div class="table-responsive">
				<table id="historyTableOuter" class="fold-table display">
					<thead>
					<tr>
						<!-- <th></th> -->
						<th>Job name</th>
						<th>Description</th>
						<th>Submitted at</th>
						<th>Duration</th>
					</tr>
					</thead>
					@foreach($jobs as $job)
						@php
							$skipFlag = 0;
							$uniqueJobId = $job->job_id;
							$jobCounterNullCheck = 0;

							//checks that a job actually has some redshifts
							$jobCounterNullCheck = DB::select('SELECT job_id, count(*) as total
									FROM redshifts
									WHERE job_id = '.$uniqueJobId);

							//checks that all redshifts in a job have completed
							if($jobCounterNullCheck[0]->total != 0){
								$jobCounter = DB::select('SELECT job_id, count(*) as total
									FROM redshifts
									WHERE (status = "PROCESSING" OR status = "SUBMITTED")
									AND job_id = '.$uniqueJobId);
							}





							//basically, only show results where ALL redshifts in a job have had the status flag set to
							//COMPLETED OR READ, which is the indication from the API that the calculation row for the redshift
							//has been written.
							if(($jobCounter[0]->total == 0) && ($jobCounterNullCheck != 0)){


								$jobCreatedAt = $job->created_at;
								$jobClosedAt = DB::select('SELECT calculations.created_at FROM calculations
									INNER JOIN redshifts ON calculations.galaxy_id = redshifts.calculation_id
									WHERE redshifts.job_id = '.$uniqueJobId .' ORDER BY calculations.created_at DESC
									LIMIT 1');

								$jobStartTime = strtotime($jobCreatedAt);
								$jobFinishTime = strtotime($jobClosedAt[0]->created_at);
								$intervalSeconds = ($jobFinishTime-$jobStartTime);

								if($intervalSeconds < 60){
									$interval = round($intervalSeconds, 2) . " seconds";
								}
								elseif ($intervalSeconds < 3600){
									//minutes
									$interval = round(($intervalSeconds/60), 2) . " minutes";
								}
								elseif ($intervalSeconds < 86400){
									//hours
									$interval = round(($intervalSeconds/(60*60)), 2) . " hours";
								}
								else{
									//days
									$interval = round(($intervalSeconds/(60*60*24)), 2) . " days";
								}

							}
							else{
								//setting skipflag as continues don't work within the else statement
								//skipflag is 0 ONLY when NO redshifts in a job are submitted/processing,
								// AND when there is at least redshift associated with the job to prevent fresh
								//jobs that have not had values written by the API breaking the page
								$skipFlag = 1;
							}
						@endphp

						@if($skipFlag == 0)

							<tbody>
							<tr class="view">
								<!-- <td></td> -->
								<td>{{ $job->job_name }}</td>
								<td>{{ $job->job_description }}</td>
								<td>@php
									$sqlDate = strtotime($job->created_at);
									echo date("jS M Y, g:i:sA", $sqlDate);
								@endphp</td>
								<td>{{ $interval }}</td>
							</tr>
							<tr class="fold">
							<!-- <td style="display: none"></td> -->
								<td colspan="7" >
									<div class="fold-content">
										<h3>{{ $job->job_name }}</h3>
										<p>{{ $job->job_description }}</p>
										
										<table  id="historyTableInner" class="display">
											<thead>
											<tr>
												<th>Galaxy ID</th>
												<th>Optical u</th>
												<th>Optical v</th>
												<th>Optical g</th>
												<th>Optical r</th>
												<th>Optical i</th>
												<th>Optical z</th>
												<th>Infrared 3.6</th>
												<th>Infrared 4.5</th>
												<th>Infrared 5.8</th>
												<th>Infrared 8.0</th>
												<th>Infrared J</th>
												<th>Infrared H</th>
												<th>Infrared K</th>
												<th>Radio 1.4</th>
												<th>Method</th>
												<th>Redshift result</th>
											</tr>
											</thead>
											<tbody>

											@php
												$calculations = DB::select('SELECT redshifts.*, redshift_result, method_name FROM calculations
													INNER JOIN redshifts ON calculations.galaxy_id = redshifts.calculation_id
													INNER JOIN methods on calculations.method_id = methods.method_id
													WHERE redshifts.job_id = '.$uniqueJobId);
											@endphp
											@foreach($calculations as $calculation)
												<tr>
													<td>{{ $calculation->assigned_calc_id }}</td>
													<td>{{ $calculation->optical_u }}</td>
													<td>{{ $calculation->optical_v }}</td>
													<td>{{ $calculation->optical_g }}</td>
													<td>{{ $calculation->optical_r }}</td>
													<td>{{ $calculation->optical_i }}</td>
													<td>{{ $calculation->optical_z }}</td>
													<td>{{ $calculation->infrared_three_six }}</td>
													<td>{{ $calculation->infrared_four_five }}</td>
													<td>{{ $calculation->infrared_five_eight }}</td>
													<td>{{ $calculation->infrared_eight_zero }}</td>
													<td>{{ $calculation->infrared_J }}</td>
													<td>{{ $calculation->infrared_H }}</td>
													<td>{{ $calculation->infrared_K }}</td>
													<td>{{ $calculation->radio_one_four }}</td>
													<td>{{ $calculation->method_name }}</td>
													<td>{{ $calculation->redshift_result }}</td>
												</tr>
											@endforeach
											</tbody>
										</table>
									</div>
								</td>
								<td style="display: none"></td>
    							<td style="display: none"></td>
    							<td style="display: none"></td>
								
							</tr>

						@endif



					@endforeach

				</tbody>
			</table>
		</div>
	</div>
	</body>
@endsection
