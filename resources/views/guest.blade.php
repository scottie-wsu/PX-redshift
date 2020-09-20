@extends('layouts.app_boot')
@section('title','Guest')
@section('content')

<head>
    <style>
    h2 {
    display: inline-block;
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
 <div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins" style="background-image: url({{ asset('images/bg1.jpg') }})">
        <div class="wrapper wrapper--w680">
            <div class="card card-4">
                <div class="card-body" >
                    <h2 class="title">Guest Calculation Form</h2>
                    <form name="form1" method="POST" action="{{ route('guest') }}" style="align-items:center;">
                        @csrf
                        <div class="row">
                            <div class="col-4">
                                <div class="input-group">
                                    <label class="label text-md-right">Galaxy ID</label>
                                    <input id="assigned_calc_ID" type="text" class="input--style-4" name="assigned_calc_ID" value="{{ old('assigned_calc_ID') }}" required autocomplete="assigned_calc_ID" autofocus>
                                </div>
                            </div>
                            <!-- </div>
                            <div class="row"> -->
                            <div class="col-4">
                                <div class="input-group">
                                    <label class="label text-md-right">Optical U</label>
                                    <input id="optical_u" step="any" type="number" class="input--style-4" name="optical_u" value="{{ old('optical_u') }}" required autocomplete="optical_u" autofocus>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group">
                                    <label class="label text-md-right">Optical V</label>
                                    <input id="optical_v" step="any" type="number" class="input--style-4" name="optical_v" value="{{ old('optical_v') }}" required autocomplete="optical_v" autofocus>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group">
                                    <label class="label text-md-right">Optical R</label>
                                    <input id="optical_r" step="any" type="number" class="input--style-4" name="optical_r" value="{{ old('optical_r') }}" required autocomplete="optical_r" autofocus>
                                </div>
                            </div>
                            <!-- </div>
                             <div class="row"> -->
                            <div class="col-4">
                                <div class="input-group">
                                    <label class="label text-md-right">Optical I</label>
                                    <input id="optical_i" step="any" type="number" class="input--style-4" name="optical_i" value="{{ old('optical_i') }}" required autocomplete="optical_i" autofocus>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group">
                                    <label class="label text-md-right">Optical G</label>
                                    <input id="optical_g" step="any" type="number" class="input--style-4" name="optical_g" value="{{ old('optical_g') }}" required autocomplete="optical_g" autofocus>
                                </div>
                            </div>
                            <!-- </div>
                             <div class="row"> -->
                            <div class="col-4">
                                <div class="input-group">
                                    <label class="label text-md-right">Optical Z</label>
                                    <input id="optical_z" step="any" type="number" class="input--style-4" name="optical_z" value="{{ old('optical_z') }}" required autocomplete="optical_z" autofocus>
                                </div>
                            </div>
                            <!-- </div>
                             <div class="row"> -->
                            <div class="col-4">
                                <div class="input-group">
                                    <label class="label text-md-right">Infrared 3.6</label>
                                    <input id="infrared_three_six" step="any" type="number" class="input--style-4" name="infrared_three_six" value="{{ old('infrared_three_six') }}" required autocomplete="infrared_three_six" autofocus>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group">
                                    <label class="label text-md-right">Infrared 4.5</label>
                                    <input id="infrared_four_five" step="any" type="number" class="input--style-4" name="infrared_four_five" value="{{ old('infrared_four_five') }}" required autocomplete="infrared_four_five" autofocus>
                                </div>
                            </div>
                            <!-- </div>
                             <div class="row"> -->
                            <div class="col-4">
                                <div class="input-group">
                                    <label class="label text-md-right">Infrared 5.8</label>
                                    <input id="infrared_five_eight" step="any" type="number" class="input--style-4" name="infrared_five_eight" value="{{ old('infrared_five_eight') }}" required autocomplete="infrared_five_eight" autofocus>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group">
                                    <label class="label text-md-right">Infrared 8.0</label>
                                    <input id="infrared_eight_zero" step="any" type="number" class="input--style-4" name="infrared_eight_zero" value="{{ old('infrared_eight_zero') }}" required autocomplete="infrared_eight_zero" autofocus>
                                </div>
                            </div>
                            <!-- </div>
                            <div class="row"> -->
                            <div class="col-4">
                                <div class="input-group">
                                    <label class="label text-md-right">Infrared J</label>
                                    <input id="infrared_J" step="any" type="number" class="input--style-4" name="infrared_J" value="{{ old('infrared_J') }}" required autocomplete="infrared_J" autofocus>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group">
                                    <label class="label text-md-right">Infrared H</label>
                                    <input id="infrared_H" step="any" type="number" class="input--style-4" name="infrared_H" value="{{ old('infrared_H') }}" required autocomplete="infrared_H" autofocus>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group">
                                    <label class="label text-md-right">Infrared K</label>
                                    <input id="infrared_K" step="any" type="number" class="input--style-4" name="infrared_K" value="{{ old('infrared_K') }}" required autocomplete="infrared_K" autofocus>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group">
                                    <label class="label text-md-right">Radio 1.4</label>
                                    <input id="radio_one_four" step="any" type="number" class="input--style-4" name="radio_one_four" value="{{ old('radio_one_four') }}" required autocomplete="radio_one_four" autofocus>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="input-group" style="text-align:center">
                                    <label class="label text-md-right">Calculation Method</label>

									@php
										$checkboxes = DB::table('methods')->select('method_id','python_script_path','method_name')->get();
									@endphp

                                    @foreach($checkboxes as $checkbox)
                                        <input name="methods[]" type="checkbox" value="{{ $checkbox->method_id}}" > <label for="methods[]">{{ $checkbox->method_name}}</label></br>
                                @endforeach

                                </div>

                            </div>
                        </div>

                        <div class="p-t-15" style="margin-left:33%">
                            <button class="btn btn--radius-2 btn--blue" type="submit" value="submit">Calculate</button>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

	@endsection





