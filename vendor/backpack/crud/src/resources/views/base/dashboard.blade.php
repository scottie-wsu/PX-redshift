@extends(backpack_view('blank'))
<script src="{{ asset('vendor/jquery/jquery-3.2.1.js') }}"></script>
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


@php
	$jobCount = App\Jobs::count();
    $redshiftCount = App\redshifts::count();
    $usersCount = App\User::count();
    $methodCount = App\methods::count();

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

    Widget::add()->to('before_content')->type('div')->class('row')->content([
		// notice we use Widget::make() to add widgets as content (not in a group)

		Widget::add()
            ->type('progress')
            ->class('card border-0 text-white bg-success text-center ')
            ->progressClass('progress-bar')
            ->value($redshiftCount. ' Redshifts Counted out of ' . $jobCount. ' Jobs')
            ->onlyHere(),

        Widget::add()
            ->type('progress')
            ->class('card border-0 text-white bg-success text-center ')
            ->value($usersCount. ' Users Registerd')
            ->onlyHere(),

        Widget::add()
            ->type('progress')
            ->class('card border-0 text-white bg-success text-center ')
            ->value($methodCount. ' Methods Registered')
            ->onlyHere(),  
 
        Widget::add()
            ->type('progress')
            ->class('card border-0 text-white bg-success text-center ')
            ->value("<span id='mycount'>$submitted</span> Jobs Submitted")
            ->onlyHere(),  
	]);
@endphp
@section('content')
@endsection