@extends('layouts.app_boot')
@section('title','Guest')
@section('content')

<head>
	<style>
		.btn_upload {
		//background-color: Blue;
			border: 1px solid black;
			color: White;
			text-align: center;
			padding-left: 20px;
			padding-right: 20px;
			margin-left: 1px;
			font-size: 16px;
			opacity: 0.6;
			transition: 0.3s;
		}
		.btn_upload:hover {opacity: 0.9}
		h2 {
			display: inline-block;
		}
		input[type='checkbox'] {
			float: left;
			width: 20px;
		}
		input[type='checkbox'] + label {
			display: block;
			width: 10%;
		}



	</style>
    </head>

    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" >
            <div class="container">
                <a class="nav-link" href="{{ route('home') }}"><h2 id="redshift" style="color:red">Red</h2><h2 id="redshiftEstimator">shift</h2></a>

                                                                                                                                                                                                                                                                                                                                                                                                             <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                <li class="nav-item">

                            </li>
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto" >
                        <!-- Authentication Links -->
                        @guest

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                             </li>



                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

@php
	$checkboxes = DB::table('methods')->select('method_id','python_script_path','method_name', 'method_description')->where('removed', 0)->get();
@endphp
<div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins" style="background-image: url({{ asset('images/bg1.jpg') }})">
	<div class="wrapper" style="width:70%">
		<div class="card card-4">
			<div class="card-body" >
				<h2 class="title">Calculation Form</h2>
				<form name="form1" method="POST" action="{{ route('guest') }}" style="align-items:center;">
					@csrf



					<div class="row">
						<div class="col-4">
							<div class="input-group">
								<label class="label text-md-right">Galaxy ID</label>
								<input id="assigned_calc_ID" type="text" class="input--style-4" name="assigned_calc_ID" value="{{ strval(session()->get('assigned_calc_ID')) }}" required autocomplete="assigned_calc_ID" autofocus>
							</div>
						</div>
						<!-- </div>
						<div class="row"> -->
						<div class="col-4">
							<div class="input-group">
								<label class="label text-md-right">Optical U</label>
								<input id="optical_u" step="any" type="number" class="input--style-4" name="optical_u" value="{{ session()->get('optical_u') }}" required autocomplete="optical_u" autofocus>
							</div>
						</div>
						<div class="col-4">
							<div class="input-group">
								<label class="label text-md-right">Optical V</label>
								<input id="optical_v" step="any" type="number" class="input--style-4" name="optical_v" value="{{ session()->get('optical_v') }}" required autocomplete="optical_v" autofocus>
							</div>
						</div>
						<div class="col-4">
							<div class="input-group">
								<label class="label text-md-right">Optical R</label>
								<input id="optical_r" step="any" type="number" class="input--style-4" name="optical_r" value="{{ session()->get('optical_r') }}" required autocomplete="optical_r" autofocus>
							</div>
						</div>
						<!-- </div>
						 <div class="row"> -->
						<div class="col-4">
							<div class="input-group">
								<label class="label text-md-right">Optical I</label>
								<input id="optical_i" step="any" type="number" class="input--style-4" name="optical_i" value="{{ session()->get('optical_i') }}" required autocomplete="optical_i" autofocus>
							</div>
						</div>
						<div class="col-4">
							<div class="input-group">
								<label class="label text-md-right">Optical G</label>
								<input id="optical_g" step="any" type="number" class="input--style-4" name="optical_g" value="{{ session()->get('optical_g') }}" required autocomplete="optical_g" autofocus>
							</div>
						</div>
						<!-- </div>
						 <div class="row"> -->
						<div class="col-4">
							<div class="input-group">
								<label class="label text-md-right">Optical Z</label>
								<input id="optical_z" step="any" type="number" class="input--style-4" name="optical_z" value="{{ session()->get('optical_z') }}" required autocomplete="optical_z" autofocus>
							</div>
						</div>
						<!-- </div>
						 <div class="row"> -->
						<div class="col-4">
							<div class="input-group">
								<label class="label text-md-right">Infrared 3.6</label>
								<input id="infrared_three_six" step="any" type="number" class="input--style-4" name="infrared_three_six" value="{{ session()->get('infrared_three_six') }}" required autocomplete="infrared_three_six" autofocus>
							</div>
						</div>
						<div class="col-4">
							<div class="input-group">
								<label class="label text-md-right">Infrared 4.5</label>
								<input id="infrared_four_five" step="any" type="number" class="input--style-4" name="infrared_four_five" value="{{ session()->get('infrared_four_five') }}" required autocomplete="infrared_four_five" autofocus>
							</div>
						</div>
						<!-- </div>
						 <div class="row"> -->
						<div class="col-4">
							<div class="input-group">
								<label class="label text-md-right">Infrared 5.8</label>
								<input id="infrared_five_eight" step="any" type="number" class="input--style-4" name="infrared_five_eight" value="{{ session()->get('infrared_five_eight') }}" required autocomplete="infrared_five_eight" autofocus>
							</div>
						</div>
						<div class="col-4">
							<div class="input-group">
								<label class="label text-md-right">Infrared 8.0</label>
								<input id="infrared_eight_zero" step="any" type="number" class="input--style-4" name="infrared_eight_zero" value="{{ session()->get('infrared_eight_zero') }}" required autocomplete="infrared_eight_zero" autofocus>
							</div>
						</div>
						<!-- </div>
						<div class="row"> -->
						<div class="col-4">
							<div class="input-group">
								<label class="label text-md-right">Infrared J</label>
								<input id="infrared_J" step="any" type="number" class="input--style-4" name="infrared_J" value="{{ session()->get('infrared_J') }}" required autocomplete="infrared_J" autofocus>
							</div>
						</div>
						<div class="col-4">
							<div class="input-group">
								<label class="label text-md-right">Infrared H</label>
								<input id="infrared_H" step="any" type="number" class="input--style-4" name="infrared_H" value="{{ session()->get('infrared_H') }}" required autocomplete="infrared_H" autofocus>
							</div>
						</div>
						<div class="col-4">
							<div class="input-group">
								<label class="label text-md-right">Infrared K</label>
								<input id="infrared_K" step="any" type="number" class="input--style-4" name="infrared_K" value="{{ session()->get('infrared_K') }}" required autocomplete="infrared_K" autofocus>
							</div>
						</div>
						<div class="col-4">
							<div class="input-group">
								<label class="label text-md-right">Radio 1.4</label>
								<input id="radio_one_four" step="any" type="number" class="input--style-4" name="radio_one_four" value="{{ session()->get('radio_one_four') }}" required autocomplete="radio_one_four" autofocus>
							</div>
						</div>
					</div>
					<div class="row">
						<label class="label text-md-left">Calculation Method</label>
						<br>
						<br>
					</div>
					<div>
						@foreach($checkboxes as $checkbox)
							<div class="row">
								<label title="{{$checkbox->method_description}}" class="form-check-label" style="padding-left: 1em; line-height: 1em"><input onchange="update_var(this)" name="methods[]" type="checkbox" value="{{ $checkbox->method_id}}" >{{ $checkbox->method_name}}</label>
							</div>
							<br>
						@endforeach
						<!-- This script updates the hidden inputs further below to match the inputs the user changes -->
							<script>
								function update_var(element) {
									var methodValue = element.value;
									fileFunctionName = "method_id_for_files";
									document.getElementById(fileFunctionName.concat(methodValue)).checked = element.checked;
								}
							</script>
							<!-- creating hidden inputs that mirror the visible inputs in the single input form -->
							@php
								$count = \App\methods::count();
								for($i=1;$i<$count+1;$i++){
									echo '<input style="display:none" id="method_id_for_files'.$i.'" name="method_id_for_files'.$i.'" type="checkbox" value="'.$i.'">';
								}

							@endphp
					</div>




					<div class="p-t-15" style="margin-left:33%">
						<button class="btn btn--radius-2 btn--blue" type="submit">Calculate</button>
					</div>
				</form>

				<br>
				@if($errors->any())
					<div class="alert alert-warning"> {{ $errors->first() }}</div>
				@endif
				@if(isset($_GET['methodFail']))
					<div class="alert alert-warning"> At least one method must be selected.</div>
				@endif
		</div>
	</div>
</div>



@endsection





