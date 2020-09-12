@extends(backpack_view('blank'))


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

    @php
        use App\methods;
        use App\calculations;
        use Illuminate\Support\Facades\DB;
        use Carbon\Carbon;



        $widgets['after_content'][] = [
        'type' => 'div',
        'class' => 'row',
        'content' => [ // widgets

            //institutions chart
              [
                'type' => 'chart',
                'wrapperClass' => 'col-md-6',
                // 'class' => 'col-md-6',
                'controller' => \App\Http\Controllers\Admin\Charts\InstitutionsChartController::class,
                'content' => [
                    'header' => 'Users and jobs completed per institution', // optional
                    // 'body' => 'This chart should make it obvious how many new users have signed up in the past 7 days.<br><br>', // optional

                ]

            ],
        //breakdown of jobs completed per user - not particularly useful right now, need feedback
        [
            'type' => 'chart',
            'wrapperClass' => 'col-md-6',
            // 'class' => 'col-md-6',
            'controller' => \App\Http\Controllers\Admin\Charts\JobsChartController::class,
            'content' => [
                'header' => 'Jobs done per user', // optional
                // 'body' => 'This chart should make it obvious how many new users have signed up in the past 7 days.<br><br>', // optional

            ]
        ],

        //breakdown of calcs per method, pie chart
        [
            'type' => 'chart',
            'wrapperClass' => 'col-md-6',
            // 'class' => 'col-md-6',
            'controller' => \App\Http\Controllers\Admin\Charts\MethodUseChartController::class,
            'content' => [
                'header' => 'Breakdown of all calculations by method used', // optional
                // 'body' => 'This chart should make it obvious how many new users have signed up in the past 7 days.<br><br>', // optional

            ]
        ],

        ////breakdown of calcs per method, pie chart
        //[
            //'type' => 'chart',
            //'wrapperClass' => 'col-md-6',
            //// 'class' => 'col-md-6',
            //'controller' => \App\Http\Controllers\Admin\Charts\WeeklyMethodUseChartChartController::class,
            //'content' => [
                //'header' => 'Breakdown of what methods have been used in the past week', // optional
                //// 'body' => 'This chart should make it obvious how many new users have signed up in the past 7 days.<br><br>', // optional

            //]
        //],

        //redshifts histogram
              [
                'type' => 'chart',
                'wrapperClass' => 'col-md-6',
                // 'class' => 'col-md-8',
                'controller' => \App\Http\Controllers\Admin\Charts\RedshiftsChartController::class,
                'content' => [
                    'header' => 'Redshift Result Frequency', // optional
                    // 'body' => 'This chart should make it obvious how many new users have signed up in the past 7 days.<br><br>', // optional

                ]
            ]
            ]


    ];



    @endphp
@endsection
