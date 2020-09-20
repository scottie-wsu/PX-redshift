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
        <span class="text-capitalize">Build your chart</span>
<small id="datatable_info_stack" class="animated fadeIn" style="display: inline-flex;"><div class="dataTables_info" id="crudTable_info" role="status" aria-live="polite">Select from available datasets to create your own plots.</div></small>
    </h2>
</div>

@endsection

@section('content')


	@php
	use App\redshifts;
	use App\calculations;
	use App\User;
	use App\Jobs;
	use Illuminate\Support\Facades\DB;
	use Carbon\Carbon;

	//$jobCountPerInstitution = DB::select('SELECT institution, COUNT(*) as total FROM users INNER JOIN redshifts on users.id = redshifts.user_id GROUP BY users.institution');

	if(isset($request)){
		dump($request);
	}
	else{
		if(isset($leftAxisDataTest)){
			dump($leftAxisDataTest);
		}
	}
	@endphp

	<form name="form1" method="POST" action="{{ route('plotdatapost') }}" style="align-items:center;">
		@csrf

		<label style="color:white" for="chartType">Plot type:</label>
		<br>
		<select name="chartType" id="chartType">
			<option value="bar">Bar</option>
			<option value="pie">Pie</option>
			<option value="line">Line</option>
		</select>
		<br>
		<label style="color:white" for="cars">Left Axis Data:</label>
		<br>
		<select name="leftAxisData" id="leftAxisData">
			<option id="usersCountLeft" value="usersCountLeft">Users count</option>
			<option id="jobCountLeft" value="jobCountLeft">Job count</option>
			<option id="calculationCountLeft" value="calculationCountLeft">Calculation count</option>
			<option id="institutionsCountLeft" value="institutionsCountLeft">Institutions count</option>
			<option id="redshiftResultsLeft" value="redshiftResultsLeft">Redshift results</option>

		</select>



		<button type="button" class="btn btn-default btn-sm">
			<span class="glyphicon glyphicon-plus-sign"></span> Remove second axis
		</button>
		<br>

		<label style="color:white" for="rightAxisData">Right Axis Data:</label>
		<br>
		<select name="rightAxisData" id="rightAxisData">
			<option id="usersCountRight" value="usersCountRight">Users count</option>
			<option id="jobCountRight" value="jobCountRight">Job count</option>
			<option id="calculationCountRight" value="calculationCountRight">Calculation count</option>
			<option id="institutionsCountRight" value="institutionsCountRight">Institutions count</option>
			<option id="redshiftResultsRight" value="redshiftResultsRight">Redshift results</option>

		</select>
		<br>

		<label style="color:white" for="perData">Per:</label>
		<br>
		<select name="perData" id="perData">
			<option value="userPer">User</option>
			<option value="jobPer">Job</option>
			<option value="institutionPer">Institution</option>
			<option value="dayPer">Day</option>
			<option value="methodPer">Method</option>
		</select>
		<br>
		<br>


		<button id="plotButton" type="submit1" class="btn btn-default btn-sm">
			<span class="glyphicon glyphicon-plus-sign"></span> Plot
		</button>
		<p></p>
	</form>



	@php
		if(isset($chartjs)){
			echo('	<div style="width:75%;">'.$chartjs->render(). '</div>');
		}
	@endphp
@endsection
