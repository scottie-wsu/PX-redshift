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
						$("span[id^=progPercent]").each(function(index) {
							//data[1] is the total galaxies in each job, data[0] is the number of galaxies still processing/submitted per job
							var completed = 100-(data[0][index]/data[1][index]*100);
							completed = completed.toFixed(2);
							$(this).text(completed+"% complete");

						});

						$("div[id^=progBar]").each(function(index) {
							//data[1] is the total galaxies in each job, data[0] is the number of galaxies still processing/submitted per job
							var completed = 100-(data[0][index]/data[1][index]*100);
							completed = completed.toFixed(2);
							$(this).attr("style", "width:"+completed+"%;height:30px");
							$(this).attr("aria-valuenow", completed);
							if(completed > 6){
								$(this).text(completed+"%");
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
						<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
								integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
								crossorigin="anonymous">
						</script>

						<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Paging/1.2.0/jquery.paging.min.js" integrity="sha512-x1BoAGQxys0d0VeupEcP7rfjB+OK70JXajGxx5cPzAI6HgsfwisDACGAwjlM4UnyvOiSpGVitVqo/I5zsoPJrw==" crossorigin="anonymous"></script>


						<h3>Calculations in progress</h3>
						<p>Completed jobs can be viewed on the <b><a href="{{route('history')}}" style="color:#0000EE">History page</a></b>.</p>
						<br>
						<ul id="listitems">
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
						<script>
						$(function() {
							$("#listPage").JPaging({
								pageSize:3
							});
						});
						</script>

						<div id="listitems-pagination" style="display:none">
							<a id="listitems-previous" href="#" class="disabled">&laquo; Previous</a>
							<a id="listitems-next" href="#">Next &raquo;</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
@endsection
