@extends('layouts.app')
@section('title','History')
@section('content')

	<head>
		<style>
			h2 {
				display: inline-block;
			}
			.historyButton {
				background: deepskyblue;
				border-radius: 10px;
				position: relative;
				left: 10px;
				width:220px;
				height:50px;
				display: block;
				line-height: 50px;
				color: lightgrey;
				text-align: center;
				padding-left: 10px;
				padding-right: 10px;
				margin-left: 1px;
				margin-top: 10px;
				font-size: 16px;
				opacity: 1.0;
				transition: 0.3s;
			}
			.historyButton:hover {
				opacity: 1.0;
				color: white;
				background: dodgerblue;

			}
			.showLink{
				color: #0000FF;
				transition: 0.1s;
			}
			.showLink:hover{
				text-decoration: underline;
			}
		</style>

	</head>



	<body style="background-image: url({{ asset('images/bg1.jpg') }})">

	<div class="overflow-auto">
		<div class="table-responsive">
				<table id="historyTableOuter" class="fold-table">
					<thead>
					<tr>
						<!-- <th></th> -->
						<th>Job name</th>
						<th>Description</th>
						<th>Submitted at</th>
						<th>Duration</th>
						<th>Download files</th>
					</tr>
					</thead>

					@php
						$rowIndex = 0;
					@endphp

					@foreach($jobs as $job)
						@php
							$skipFlag = 0;
							$uniqueJobId = $job->job_id;
							$jobCounterNullCheck = 0;

							//checks that a job actually has some redshifts
							$jobCounterNullCheck = DB::table('redshifts')->where('job_id', $uniqueJobId)->count();

							//checks that all? redshifts in a job have completed
							if($jobCounterNullCheck != 0){
								$jobCounter = DB::table('redshifts')->where('status', 'PROCESSING')->orWhere('status', 'SUBMITTED')->exists();
							}

							if(isset($jobCounter[0])){
								$jobCounterNullFlag = 0;
							}
							else{
								$jobCounterNullFlag = 1;
							}

							//return(dump($jobCounterNullCheck[0]->total));

							//basically, only show results where ALL redshifts in a job have had the status flag set to
							//COMPLETED OR READ, which is the indication from the API that the calculation row for the redshift
							//has been written.
							if(($jobCounter == true) && ($jobCounterNullCheck != 0)){


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
								// AND when there is at 1 least redshift associated with the job to prevent fresh
								//jobs that have not had values written by the API breaking the page
								$skipFlag = 1;
							}
						@endphp

						@if($skipFlag == 0)
							@csrf
							<tbody>
							<tr id="{{ $job->job_name }}" class="view">
								<!-- <td></td> -->
								<td>{{ $job->job_name }}</td>
								<td>{{ $job->job_description }}</td>
								<td>@php
									$sqlDate = strtotime($job->created_at);
									echo date("g:i:sA, jS M Y", $sqlDate);
								@endphp</td>
								<td>{{ $interval }}</td>
								@php
										$altCount = count(DB::select("SELECT calculations.redshift_alt_result FROM calculations
											INNER JOIN redshifts on calculations.galaxy_id = redshifts.calculation_id
											INNER JOIN jobs on redshifts.job_id = jobs .job_id
											INNER JOIN users on jobs.user_id = users.id
											WHERE (redshifts.status = 'COMPLETED' OR redshifts.status = 'READ')
		  									AND calculations.redshift_alt_result LIKE '%alt_result%'
											AND users.id = " . auth()->id()));

								@endphp
								<td>
									@if($altCount>0)
									
										<form action="{{ route("zipJob") }}" method="post">
											@csrf
											<button class="showLink" name="job_id" value="{{ $job->job_id }}">Download</button>
										</form>
									
									@endif
									</td>

							</tr>
							<tr class="fold">
							<!-- <td style="display: none"></td> -->
								<td colspan="7" >
									<div class="fold-content">
										<h3>{{ $job->job_name }}</h3>
										<p>{{ $job->job_description }}</p>
										
										<div class="row">
											<div class="col-md-2">
											<select class="form-control input--style-4" id="search-column{{ $rowIndex }}">
												<option value="0">Galaxy ID</option>
												<option value="1">Optical u</option>
												<option value="2">Optical v</option>
												<option value="3">Optical g</option>
												<option value="4">Optical r</option>
												<option value="5">Optical i</option>
												<option value="6">Optical z</option>
												<option value="7">Infrared 3.6</option>
												<option value="8">Infrared 4.5</option>
												<option value="9">Infrared 5.8</option>
												<option value="10">Infrared 8.0</option>
												<option value="11">Infrared J</option>
												<option value="12">Infrared H</option>
												<option value="13">Infrared K</option>
												<option value="14">Radio 1.4</option>
												<option value="15">Method</option>
												<option value="16">Redshift result</option>
											</select>
											</div>
											<div class="col-md-3">
											<input class="form-control input--style-4" type="text" id="search-by-column{{ $rowIndex }}" placeholder="Search...">
											</div>
										</div>



										<table  id="historyTableInner{{ $rowIndex }}" class="display">
											@php $rowIndex = $rowIndex+1; @endphp
											
											

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
												<th>Redshift file result</th>
												<th>Embed</th>
											</tr>
											</thead>
											<tbody>

											@php
												$calculations = DB::select('SELECT redshifts.*, redshift_result, redshift_alt_result, method_name FROM calculations
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
													@php
														if(isset($calculation->redshift_alt_result)){
															echo ('<td><a class="showLink" href="' . $calculation->redshift_alt_result . '">Show</td>');
														}
													@endphp
													
													@php
														if(isset($calculation->redshift_alt_result)){
															echo ('<td><a href="' . $calculation->redshift_alt_result . '" data-lightbox="' . $calculation->redshift_alt_result . 'file" ><img class="thumbnail" src="' . $calculation->redshift_alt_result . '"></img></td>');}
													@endphp 
												</tr>
											@endforeach
											</tbody>
										</table>
									</div>
								</td>
								<td style="display: none"></td>
    							<td style="display: none"></td>
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
	@php $tnum = $rowIndex; @endphp
	<script type="text/javascript">
    	var numTables = '<?php echo $tnum ;?>';
	</script>
	
@endsection
