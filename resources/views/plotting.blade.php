@extends(backpack_view('blank'))
@section('header')
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
@endsection
@section('content')
    <div class="container">
		<h3>Make a Plot</h3>
			<form class="form-inline" action="/admin/plotting" method="post">
				@csrf
				<select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref" name = "selected-plot" require>
					<option selected>Choose statistics</option>
					@php
						foreach($availablePlots as $key => $val) {
					@endphp
					<option value="@php echo $key; @endphp">@php echo $val; @endphp</option>
					@php } @endphp
				</select>
				<select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref" name = "selected-plot-type" require>
					<option selected>Choose graph</option>
					<option value="bar-chart">Bar Chart</option>
					<option value="line-chart">Line Chart</option>
				</select>
				<button type="submit" class="btn btn-success my-1">Plot</button>
				@php  if (isset($invalidForm)) { @endphp
					<div class="col-sm-3">
						<small id="graph-form-error" class="text-danger">
							You must select a statistic and a graph to represent it.
						</small>      
					</div>
				@php } @endphp
			</form>

			@php if (isset($data)) { @endphp
				<script>var graphType = <?php echo json_encode($graphType, JSON_HEX_TAG); ?>;</script>
				<script>var graphTitle = <?php echo json_encode($graphTitle, JSON_HEX_TAG); ?>;</script>
				<script>var graphLabels = <?php echo json_encode($labels, JSON_HEX_TAG); ?>;</script>
				<script>var graphData = <?php echo json_encode($data, JSON_HEX_TAG); ?>;</script>
				<script>var graphXLabel = <?php echo json_encode($graphXLabel, JSON_HEX_TAG); ?>;</script>
				<script>var graphYLabel = <?php echo json_encode($graphYLabel, JSON_HEX_TAG); ?>;</script>
				
				<canvas id="myChart"></canvas>
			@php } else { @endphp
				<h4><img src={{ asset('images/bootstrap-icons-1.0.0/arrow-up-short.svg') }} alt="" width="64" height="64" title="Bootstrap"> Please select statistics you want and in what type of graph</h4>
			@php } @endphp
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js"></script>
	<script src="{{ asset('js/generate-plot.js') }}"></script>
@endsection
