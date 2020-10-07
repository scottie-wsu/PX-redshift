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

use App\User;
use App\redshifts;
use App\methods;
use App\Jobs;

	$jobCount = Jobs::select('job_id')->get()->count();
    $redshiftCount = redshifts::select('calculation_id')->get()->count();
    $usersCount = User::select('id')->get()->count();
    $methodCount = methods::select('method_id')->get()->count();

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
            ->value("<span id='mycount'>$redshiftCount</span> Redshifts Completed ")
            ->onlyHere(),

         Widget::add()
            ->type('progress')
            ->class('card border-0 text-white bg-warning text-center ')
            ->progressClass('progress-bar')
            ->value("<span id='mycount'>$jobCount</span> Jobs Submitted")
            ->onlyHere(),

        Widget::add()
            ->type('progress')
            ->class('card border-0 text-white bg-info text-center ')
            ->value("<span id='mycount'>$usersCount</span> Users Registered")
            ->onlyHere(),

        Widget::add()
            ->type('progress')
            ->class('card border-0 text-white bg-danger text-center ')
            ->value("<span id='mycount'>$methodCount</span> Methods Available")
            ->onlyHere(),  
 
        Widget::add()
            ->type('progress')
            ->class('card border-0 text-white bg-primary text-center ')
            ->value("<span id='mycount'>$submitted</span> Galaxies Submitted")
            ->onlyHere(),  

         Widget::add()
            ->type('progress')
            ->class('card border-0 text-white bg-dark text-center ')
            ->value("<span id='mycount'>$processing</span> Galaxies Processing")
            ->onlyHere(),     
	]);
@endphp
@section('content')
@endsection