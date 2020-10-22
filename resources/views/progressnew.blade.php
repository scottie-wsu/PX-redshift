@extends('layouts.app')
@section('title','Progress')
@section('content')

	<head>
		<style>
			h2 {
				display: inline-block;
			}
			body, html {
                height: 100%;
            }

			.custom-margin {
				margin-bottom: 15px;
			}
		</style>
	</head>
		<body>
		<div class="container d-flex h-100">
            <div class="row align-self-center w-100">
                <div class="col-sm-12 mx-auto" id = "checking-col">
                    <h2 align=center>Checking...</h2>
                </div>
                <div class="col-lg-12 mx-auto" id = "processed-col" style = "display: none;">
					<h2>Nothing processing in background, all requests processed! <img src={{ asset('images/bootstrap-icons-1.0.0/emoji-laughing.svg') }} alt="" width="64" height="64" title="Bootstrap"></h2>
					<p>Completed jobs can be viewed on the <b><a href="{{route('history')}}" style="color:#0000EE">History page</a></b>.</p>
                </div>
                <div class="col-lg-12 mx-auto" id = "processing-col" style = "display: none;">
                    <h3>Still processing, please wait...</h3>
                    <div class="progress custom-margin">
						<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id = "completed-progress-bar" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
						<div class="progress-bar progress-bar-striped progress-bar-animated bg-info" id = "processing-progress-bar" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
					</div>
					<h4>Here are some stats:</h4>
					<ul>
						<li>Submitted - <em><span id = "submitted"></span></em></li>
						<li>Processing - <em><span id = "processing"></span></em></li>
						<li>Completed - <em><span id = "completed"></span></em></li>
						<li>Total - <em><span id = "total"></span></em></li>
					</ul>
                </div>
            </div>
        </div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="{{ asset('js/poll-status.js') }}"></script>
	</body>
@endsection
