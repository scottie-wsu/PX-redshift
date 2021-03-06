@extends(backpack_view('blank'))
<script src="{{ asset('vendor/jquery/jquery-3.2.1.js') }}"></script>
<style>
    .row {
        color: black;
    }
</style>
<script>
    function getCount1() {
        $.ajax({
            type: "GET",
            url: "{{ route('ajaxcounts1') }}",
        })
            .done(function( data ) {
                $('#mycount1').html(data);
                setTimeout(getCount1, 2000);
            });
    }
    getCount1();
</script>

<script>
    function getCount2() {
        $.ajax({
            type: "GET",
            url: "{{ route('ajaxcounts2') }}",
        })
            .done(function( data ) {
                $('#mycount2').html(data);
                setTimeout(getCount2, 2000);
            });
    }
    getCount2();
</script>

<script>
    function getCount3() {
        $.ajax({
            type: "GET",
            url: "{{ route('ajaxcounts3') }}",
        })
            .done(function( data ) {
                $('#mycount3').html(data);
                setTimeout(getCount3, 2000);
            });
    }
    getCount3();
</script>

<script>
    function getCount4() {
        $.ajax({
            type: "GET",
            url: "{{ route('ajaxcounts4') }}",
        })
            .done(function( data ) {
                $('#mycount4').html(data);
                setTimeout(getCount4, 2000);
            });
    }
    getCount4();
</script>

<script>
    function getCount5() {
        $.ajax({
            type: "GET",
            url: "{{ route('ajaxcounts5') }}",
        })
            .done(function( data ) {
                $('#mycount5').html(data);
                setTimeout(getCount5, 2000);
            });
    }
    getCount5();
</script>

<script>
    function getCount6() {
        $.ajax({
            type: "GET",
            url: "{{ route('ajaxcounts6') }}",
        })
            .done(function( data ) {
                $('#mycount6').html(data);
                setTimeout(getCount6, 2000);
            });
    }
    getCount6();
</script>

<script>
    function getCount7() {
        $.ajax({
            type: "GET",
            url: "{{ route('ajaxcounts7') }}",
        })
            .done(function( data ) {
                $('#mycount7').html(data);
                setTimeout(getCount7, 2000);
            });
    }
    getCount7();
</script>

<script>
    function getCount8() {
        $.ajax({
            type: "GET",
            url: "{{ route('ajaxcounts8') }}",
        })
            .done(function( data ) {
                $('#mycount8').html(data);
                setTimeout(getCount8, 2000);
            });
    }
    getCount8();
</script>


@php

    use App\User;
    use App\redshifts;
    use App\methods;
    use App\Jobs;

        $jobCount = Jobs::select('job_id')->get()->count();
        $redshiftCount = redshifts::select('calculation_id')->get()->count();
        $usersCount = User::select('id')->get()->count();
        $methodCount = methods::select('method_id')->where('removed', '0')->get()->count();

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
            if($set>0){
                $percentage = $working/$set;
            }
            else{
                $percentage = 0;
            }
            $progress = (1-$percentage)*100;

        Widget::add()->to('before_content')->type('div')->class('row')->content([
            // notice we use Widget::make() to add widgets as content (not in a group)

            Widget::add()
                ->type('progress')
                ->class('card border-0 text-white bg-success text-center ')
                ->progressClass('progress-bar')
                ->value("<span id='mycount4'>$redshiftCount</span> calculations completed")
                ->onlyHere(),

             Widget::add()
                ->type('progress')
                ->class('card border-0 text-white bg-warning text-center ')
                ->progressClass('progress-bar')
                ->value("<span id='mycount3'>$jobCount</span> jobs submitted")
                ->onlyHere(),

            Widget::add()
                ->type('progress')
                ->class('card border-0 text-white bg-info text-center ')
                ->value("<span id='mycount5'>$usersCount</span> users registered")
                ->onlyHere(),

            Widget::add()
                ->type('progress')
                ->class('card border-0 text-white bg-danger text-center ')
                ->value("<span id='mycount6'>$methodCount</span> methods available")
                ->onlyHere(),

            Widget::add()
                ->type('progress')
                ->class('card border-0 text-white bg-primary text-center ')
                ->value("<span id='mycount1'>$submitted</span> galaxies yet to be processed")
                ->onlyHere(),

            Widget::add()
                ->type('progress')
                ->class('card border-0 text-white bg-dark text-center ')
                ->value("<span id='mycount2'>$processing</span> galaxies processing")
                ->onlyHere(),


            Widget::add()

                ->type('progress')
                ->class('card border-0 text-white bg-primary text-center ')
                ->value("<span id='mycount7'>No response</span>")
                ->onlyHere(),

            Widget::add()
                ->type('progress')
                ->class('card border-0 text-white bg-primary text-center ')
                ->value("<span id='mycount8'>No response</span>")
                ->onlyHere(),
        ]);



@endphp
@section('content')
@endsection
