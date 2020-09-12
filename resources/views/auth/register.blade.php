@extends('layouts.app_boot')
@section('title','Register')
@section('content')
	<div class="limiter" >
		<div class="container-login100" style="background-image: url('/images/bg1.jpg')">
			<div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
				<form class="login100-form validate-form" method="POST" action="{{ route('register') }}">
                @csrf    
              

  <span class="login100-form-title p-b-49">
						Register
					</span>

 						 <div class="wrap-input100 validate-input m-b-23" data-validate = "Name is required">
						<span class="label-input100">{{ __('Name') }}</span>
						<input id="name" class="input100 form-control @error('name') is-invalid @enderror" type="text" name="name" placeholder="Type your name" value="{{ old('name') }}">
                        <span class="focus-input100" data-symbol="&#xf206;"></span>
                        
                                @error('email')
                                
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
					</div>
                      <div class="wrap-input100 validate-input m-b-23" data-validate = "Email is required">
						<span class="label-input100">{{ __('E-Mail Address') }}</span>
						<input id="email" class="input100 form-control @error('email') is-invalid @enderror" type="email" name="email" placeholder="Type your email" value="{{ old('email') }}">
                        <span class="focus-input100" data-symbol="&#xf206;"></span>
                        
                                @error('email')
                                
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
					</div>

					<div class="wrap-input100 validate-input m-b-23" data-validate = "Institution is required">
						<span class="label-input100">{{ __('Institution') }}</span>
						<input id="institution" class="input100 form-control @error('institution') is-invalid @enderror" type="institution" name="institution" placeholder="Type your institution" value="{{ old('institution') }}">
                        <span class="focus-input100" data-symbol="&#xf206;"></span>
                        
                                @error('institution')
                                
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
					</div>
                        

                            

                       <div class="wrap-input100 validate-input m-b-23" data-validate="Password is required">
						<span class="label-input100">Password</span>
						<input id="password" type="password" class="input100 form-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="Type your password" required autocomplete="current-password">
						<span class="focus-input100" data-symbol="&#xf190;"></span>
						@error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
					</div>

					 <div class="wrap-input100 validate-input m-b-23" data-validate="Password is required">
						<span class="label-input100">Confirm Password</span>
						<input  class="input100 form-control " type="password" placeholder="Confirm your password" required  id="password-confirm" name="password_confirmation" required autocomplete="new-password">
						<span class="focus-input100" data-symbol="&#xf190;"></span>
						@error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
					</div>

                       

                      <div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button type="submit" class="login100-form-btn">
								 {{ __('Register') }}
							</button>
						</div>
					</div>


					

					
				</form>
			</div>
		</div>
	</div>

	@endsection
	




