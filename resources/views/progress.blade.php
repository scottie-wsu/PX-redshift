@extends('layouts.app')
@section('title','Calculation Progress')
@section('content')

	<head>
		<style>
			h2 {
				display: inline-block;
			}
			h3 {
				padding-bottom: 10px;
			}
			.progress-label {
				float: left;
				margin-right: 1em;
			}
			.holder {
				margin:15px 0;

			}
			.holder a {
				font-size:12px;
				cursor:pointer;
				margin:0 5px;
				font-family: "Poppins-Regular";
				font-size: 16px;
			}
			.holder a:hover {
				color:black;
				font-weight: bold;
			}
			.holder a.jp-previous {
				margin-right:15px;
			}
			.holder a.jp-next {
				margin-left:15px;
			}
			.holder a.jp-current,a.jp-current:hover {
				color:#0000FF;
				font-weight:bold;
				background-color: white;
			}
			.holder a.jp-disabled {
				color:#bbb;
				background-color: white;

			}
			.holder span {
				margin:5px 5px;
			}
			form {
				float:right;
				margin-right:10px;
			}
			form label {
				margin-right: 5px;
			}
		</style>
	</head>



	<body style="background-image: url({{ asset('images/bg1.jpg') }})">
	<script src="{{ asset('vendor/jquery/jquery-3.2.1.js') }}"></script>


	<script>
		function getCount() {
			$.ajax({
				type: "GET",
				url: "{{ route('progressAjax') }}",
				dataType:"json"
			})
				.done(function( data ) {
					var numProgress = $('.progress').length;

					if(numProgress < data[0].length){
						$('.newJobNotif').attr("style", "");
					}

					$("span[id^=progPercent]").each(function(index) {
						//data[1] is the total galaxies in each job, data[0] is the number of galaxies still processing/submitted per job
						var completed = 100-(data[0][index]/data[1][index]*100);
						completed = completed.toFixed(2);
						if(!$.isNumeric(completed)){
							$(this).text("100% complete");
						}
						else{
							$(this).text(completed+"% complete");

						}

					});

					$("div[id^=progBar]").each(function(index) {
						//data[1] is the total galaxies in each job, data[0] is the number of galaxies still processing/submitted per job
						var completed = 100-(data[0][index]/data[1][index]*100);
						completed = completed.toFixed(2);
						if(!$.isNumeric(completed)){
							$(this).attr("style", "width:100%;height:30px");
							$(this).text("100%");
						}
						else{
							$(this).attr("style", "width:"+completed+"%;height:30px");
							$(this).attr("aria-valuenow", completed);
							if(completed > 6){
								$(this).text(completed+"%");
							}
						}

					});



					setTimeout(getCount, 2000);
				});

		}
		getCount();
	</script>


	<div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins" style="background-image: url({{ asset('images/bg1.jpg') }})">
		<div class="wrapper" style="width:70%">
			<div class="card card-4">
				<div class="card-body" >




					<h3>Calculations in progress</h3>
					<b class="newJobNotif" style="display:none">New jobs have been submitted! Refresh this page to track their progress.</b>
					<p>Completed jobs can be viewed on the <b><a href="{{route('history')}}" style="color:#0000EE">History page</a></b>.</p>
					<br>
					<ul id="itemContainer">
						@foreach($jobsIncomplete as $job)
							<li>
								<div>
									<b class="pull-left">{{$job->job_name}}</b>
									<br>
									<span class="pull-left">Submitted at @php
											$sqlDate = strtotime($job->created_at);
											echo date("g:i:sA, jS M Y", $sqlDate);
										@endphp</span>

									<span id="progPercent{{$job->job_id}}" class="pull-right"></span>
									<br>
									<div class="progress" style="height: 30px;">
										<div id="progBar{{$job->job_id}}" class="progress-bar progress-label" role="progressbar" aria-valuenow="60"
											 aria-valuemin="0" aria-valuemax="60" style="width:0%; height:30px">
										</div>
									</div>
									<br>
								</div>
							</li>
						@endforeach
					</ul>
					<div class="holder"></div>
					<script>
						/* when document is ready */
						$(function() {
							/* initiate plugin */
							$("div.holder").jPages({
								containerID: "itemContainer",
								perPage: 6,
								previous: "Previous",
								next: "Next"
							});
						});
					</script>
				</div>
			</div>
		</div>
	</div>
	</body>
@endsection
