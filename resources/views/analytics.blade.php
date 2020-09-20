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

        .one {
            margin-right:15%;
            float: left;
        }

        .two {
            margin-right:15%;
            float:right;
        }

        .three {
            width: 25%;
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
	<div class = "one">
		{!! $chartjs->render() !!}
	</div>
	<div class = "two">
		{!! $chartjs1->render() !!}
	</div>
    <div class = "three">
        {!! $chartjs2->render() !!}
    </div>
    @php
    $redshiftCount = App\redshifts::count();
        Widget::add()->to('before_content')->type('div')->class('row')->content([

        Widget::add()
            ->type('progress')
            ->class('card border-0 text-white bg-success')
            ->progressClass('progress-bar')
            ->value($redshiftCount. ' Redshifts Counted')
            ->progress(80)
            ->onlyHere()



        ]);






    @endphp
@endsection




