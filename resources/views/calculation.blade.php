@extends('layouts.app')
@section('title', 'Calculation Page' )
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


@php
	$checkboxes = DB::table('methods')->select('method_id','python_script_path','method_name', 'method_description')->where('removed', 0)->get();
@endphp


  <div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins" style="background-image: url({{ asset('images/bg1.jpg') }})">
        <div class="wrapper" style="width:70%">
            <div class="card card-4">
                <div class="card-body" >
                    <h2 class="title">Calculation Form</h2>

					<form name="form1" method="POST" action="{{ route('calculation.index') }}" style="align-items:center;">
                        @csrf

						<div class="row">
							<div class="col-5">
								<div class="input-group">
								<label class="label text-sm-left">Job Name</label>
								<input onchange="update_var1(this)" id="job_name" type="text" class="input--style-4" name="job_name" required autocomplete="job_name" autofocus>
								</div>
							</div>
						</div>
						<script>
							function update_var1(element) {
								var jobNameValue = element.value;
								document.getElementById("job_nameFile").value = jobNameValue;
							}
						</script>
					<br>
						<div class="row">
						<div class="col-12">
								<div class="input-group">
									<label class="label text-md-left">Job Description (optional)</label>
									<input onchange="update_var2(this)" id="job_description" type="text" class="input--style-4" name="job_description" value="{{ old('job_description') }}" autofocus>
								</div>
							</div>
						</div>
						<script>
							function update_var2(element) {
								var jobDescValue = element.value;
								document.getElementById("job_descriptionFile").value = jobDescValue;
							}
						</script>
						<br>
						<br>

                        <div class="row">
                            <div class="col-3">
                                <div class="input-group">
                                    <label class="label text-md-right">Galaxy ID</label>
                                    <input id="assigned_calc_ID" type="text" class="input--style-4" name="assigned_calc_ID" value="{{ old('assigned_calc_ID') }}" required autocomplete="assigned_calc_ID" autofocus>
                                </div>
                            </div>
                        <!-- </div>
                        <div class="row"> -->
                            <div class="col-3">
                                <div class="input-group">
                                    <label class="label text-md-right">Optical U</label>
                                    <input id="optical_u" step="any" type="number" class="input--style-4" name="optical_u" value="{{ old('optical_u') }}" required autocomplete="optical_u" autofocus>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group">
                                    <label class="label text-md-right">Optical V</label>
                                    <input id="optical_v" step="any" type="number" class="input--style-4" name="optical_v" value="{{ old('optical_v') }}" required autocomplete="optical_v" autofocus>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group">
                                    <label class="label text-md-right">Optical R</label>
                                    <input id="optical_r" step="any" type="number" class="input--style-4" name="optical_r" value="{{ old('optical_r') }}" required autocomplete="optical_r" autofocus>
                                </div>
                            </div>
                        <!-- </div>
                         <div class="row"> -->
                            <div class="col-3">
                                <div class="input-group">
                                    <label class="label text-md-right">Optical I</label>
                                    <input id="optical_i" step="any" type="number" class="input--style-4" name="optical_i" value="{{ old('optical_i') }}" required autocomplete="optical_i" autofocus>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group">
                                    <label class="label text-md-right">Optical G</label>
                                    <input id="optical_g" step="any" type="number" class="input--style-4" name="optical_g" value="{{ old('optical_g') }}" required autocomplete="optical_g" autofocus>
                                </div>
                            </div>
                        <!-- </div>
                         <div class="row"> -->
                            <div class="col-3">
                                <div class="input-group">
                                    <label class="label text-md-right">Optical Z</label>
                                    <input id="optical_z" step="any" type="number" class="input--style-4" name="optical_z" value="{{ old('optical_z') }}" required autocomplete="optical_z" autofocus>
                                </div>
                            </div>
                        <!-- </div>
                         <div class="row"> -->
                            <div class="col-3">
                                <div class="input-group">
                                    <label class="label text-md-right">Infrared 3.6</label>
                                    <input id="infrared_three_six" step="any" type="number" class="input--style-4" name="infrared_three_six" value="{{ old('infrared_three_six') }}" required autocomplete="infrared_three_six" autofocus>
                                </div>
                            </div>
                             <div class="col-3">
                                <div class="input-group">
                                    <label class="label text-md-right">Infrared 4.5</label>
                                    <input id="infrared_four_five" step="any" type="number" class="input--style-4" name="infrared_four_five" value="{{ old('infrared_four_five') }}" required autocomplete="infrared_four_five" autofocus>
                                </div>
                            </div>
                        <!-- </div>
                         <div class="row"> -->
                            <div class="col-3">
                                <div class="input-group">
                                    <label class="label text-md-right">Infrared 5.8</label>
                                    <input id="infrared_five_eight" step="any" type="number" class="input--style-4" name="infrared_five_eight" value="{{ old('infrared_five_eight') }}" required autocomplete="infrared_five_eight" autofocus>
                                </div>
                            </div>
                             <div class="col-3">
                                <div class="input-group">
                                    <label class="label text-md-right">Infrared 8.0</label>
                                    <input id="infrared_eight_zero" step="any" type="number" class="input--style-4" name="infrared_eight_zero" value="{{ old('infrared_eight_zero') }}" required autocomplete="infrared_eight_zero" autofocus>
                                </div>
                            </div>
                        <!-- </div>
                        <div class="row"> -->
                            <div class="col-3">
                                <div class="input-group">
                                    <label class="label text-md-right">Infrared J</label>
                                    <input id="infrared_J" step="any" type="number" class="input--style-4" name="infrared_J" value="{{ old('infrared_J') }}" required autocomplete="infrared_J" autofocus>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group">
                                    <label class="label text-md-right">Infrared H</label>
                                    <input id="infrared_H" step="any" type="number" class="input--style-4" name="infrared_H" value="{{ old('infrared_H') }}" required autocomplete="infrared_H" autofocus>
                                </div>
                            </div>
                             <div class="col-3">
                                <div class="input-group">
                                    <label class="label text-md-right">Infrared K</label>
                                    <input id="infrared_K" step="any" type="number" class="input--style-4" name="infrared_K" value="{{ old('infrared_K') }}" required autocomplete="infrared_K" autofocus>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group">
                                    <label class="label text-md-right">Radio 1.4</label>
                                    <input id="radio_one_four" step="any" type="number" class="input--style-4" name="radio_one_four" value="{{ old('radio_one_four') }}" required autocomplete="radio_one_four" autofocus>
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
														<input onchange="update_var(this)" name="methods[]" type="checkbox" value="{{ $checkbox->method_id}}" >
															<label title="{{$checkbox->method_description}}" class="form-check-label" style="padding-left: 1em; line-height: 1em" for="methods[]">{{ $checkbox->method_name}}</label>

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
								</div>




                        <div class="p-t-15" style="margin-left:33%">
                            <button class="btn btn--radius-2 btn--blue" type="submit">Calculate</button>
                        </div>
                    </form>


                         <div class="input-group" style = "margin-top: 20px">
                         	<div class="custom-file">

                        		<form enctype="multipart/form-data" action="{{ route('upload') }}" method="POST">

                                    <!-- creating hidden inputs that mirror the visible inputs in the single input form -->
                                    @php
                                        $count = \App\methods::count();
                                        for($i=1;$i<$count+1;$i++){
                                            echo '<input style="display:none" id="method_id_for_files'.$i.'" name="method_id_for_files'.$i.'" type="checkbox" value="'.$i.'">';
                                        }

                                    @endphp

									<input id="job_nameFile" type="hidden" name="job_nameFile">
									<input id="job_descriptionFile" type="hidden" name="job_descriptionFile">

                            	    @csrf
                                    <input type="file" class="custom-file-input" id="fileInput" name="fileToUpload">

   							 	    <label id = "inputLabel" class="custom-file-label" for="fileInput">Choose .csv file
                                    <script>
                                    document.getElementById('fileInput').onchange = function () {
  									    document.getElementById('inputLabel').innerHTML = this.value.replace(/.*[\/\\]/, '');
								    };
                                    </script>
                                    </label>

                            </div>

                            <div class="input-group-append" style="padding-left:15px">
                                <button class ="btn_upload btn--radius-2 btn--blue" style="height:92%" type="submit" value="Upload">Submit File</button>
							</div>
                             </form>

                    	</div>

					@if($errors->any())
						<div class="alert alert-warning"> {{ $errors->first() }}</div>
					@endif
					@if(isset($_GET['methodFail']))
						<div class="alert alert-warning"> At least one method must be selected.</div>
					@endif
					@if(isset($_GET['dataFail']))
						<div class="alert alert-warning"> At least one row with all input fields filled must be submitted.</div>
					@endif
                </div>
            </div>
        </div>
    </div>




 @endsection

