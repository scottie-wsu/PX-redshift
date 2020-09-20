@extends(backpack_view('blank'))
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.js"></script>

@section('header')
    <style>
        div{
            padding-bottom: 45px;
        }
        /* Style the button that is used to open and close the collapsible content */
        .collapsible {
            background-color: #eee;
            color: #444;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
        }

        /* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
        .active, .collapsible:hover {
            background-color: #ccc;
        }

        /* Style the collapsible content. Note: hidden by default */
        .content {
            padding: 0 18px;
            display: none;
            overflow: hidden;
            background-color: #f1f1f1;
        }
        .admin{
            color: lightskyblue;
        }
    </style>
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="d-none d-lg-block">
            <ol class="breadcrumb bg-transparent p-0 justify-content-end">
                <li class="breadcrumb-item text-capitalize"><a href="http://127.0.0.1:8000/admin/dashboard">Admin</a></li>
                <li class="breadcrumb-item text-capitalize admin" aria-current="page">Analytics</li>
            </ol>
        </nav>
    <h2>
        <!--<span class="text-capitalize">Analytics</span>
        <small id="datatable_info_stack" class="animated fadeIn" style="display: inline-flex;"><div class="dataTables_info" id="crudTable_info" role="status" aria-live="polite">Here's your breakdown of how the site is being used.</div></small>-->
        <span class="text-capitalize">Quick plots</span>
<small id="datatable_info_stack" class="animated fadeIn" style="display: inline-flex;"><div class="dataTables_info" id="crudTable_info" role="status" aria-live="polite">Here's a quick summary of how the site is being used.</div></small>
    </h2>
</div>

@endsection

@section('content')
		<div class="chart1  col" style="width:400px;height:400px">
			{!! $charts[0]->render() !!}
		</div>
		<div class="chart2  col" style="width:400px;height:400px">
			{!! $charts[1]->render() !!}
		</div>

		<div class="chart3" style="width:400px;height:400px">
			{!! $charts[2]->render() !!}
		</div>
		<div class="chart4" style="width:400px;height:400px">
			{!! $charts[3]->render() !!}
		</div>


    @php
        use App\redshifts;
        use App\calculations;
        use Illuminate\Support\Facades\DB;
        use Carbon\Carbon;

		$read = redshifts::select('calculation_id')->where('status', 'READ')->get()->count();
		$completed = redshifts::select('calculation_id')->where('status', 'COMPLETED')->get()->count();
		$processing = redshifts::select('calculation_id')->where('status', 'PROCESSING')->get()->count();
		$submitted = redshifts::select('calculation_id')->where('status', 'SUBMITTED')->get()->count();

		$working = $processing+$submitted;
		//print_r($working);
		$set = $processing+$submitted+$completed;
		$percentage = $working/$set;
		$progress = (1-$percentage)*100;
		//	src="https://code.jquery.com/jquery-3.5.1.js"
		//	<script src="vendor/pace/pace-1.0.2.js"></script>


	@endphp
	<script src="{{ asset('vendor/jquery/jquery-3.2.1.js') }}"></script>

	<div style="width:240px;">


		<span id="mycount">
			@php print_r("Number of calculations currently queued: " . $submitted) @endphp
		</span>

		<!-- search for pace in bundle.js for options. this.el=document.createElement("div"), was removed -->
		<script>
			function getCount() {

				$.ajax({
					type: "GET",
					url: "{{ route('ajaxcounts') }}",
				})
					.done(function( data ) {
						$('#mycount').html(data);
						setTimeout(getCount, 1000);
					});


			}
			getCount();

		</script>
		<br>
		@php print_r("Number of calculations currently processing: " . $processing) @endphp
		<br>


	</div>

@endsection
