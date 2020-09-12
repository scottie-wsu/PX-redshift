@extends('layouts.app_boot')
@section('title','Login')
@section('content')
	<div class="limiter" >
		<div class="container-login100" style="background-image: url('/images/bg1.jpg')">
			<div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
				<form class="login100-form validate-form" method="POST" action="{{ route('login') }}">
                @csrf    
              

  <span class="login100-form-title p-b-49">
						Login
					</span>

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

					<div class="wrap-input100 validate-input" data-validate="Password is required">
						<span class="label-input100">Password</span>
						<input id="password" type="password" class="input100 form-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="Type your password" required autocomplete="current-password">
						<span class="focus-input100" data-symbol="&#xf190;"></span>
						@error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
					</div>
					
					<div class="text-right p-t-8 p-b-31">
						@if (Route::has('password.request'))
                                    <a  href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
					</div>
					<!-- style="margin-top:50px;" -->
					<div class="container-login100-form-btn" >
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button type="submit" class="login100-form-btn">
								 {{ __('Login') }}
							</button>
						</div>
					</div>

					

					<div class="flex-col-c p-t-155">
						<span class="txt1 p-b-2">
							If your're just looking for a quick estimation then pick
						</span>
						<a class="txt2" href="{{ route('guest') }}">{{ __(' Guest') }}</a>
						
						<span class="txt1 p-b-2">
							Or Sign Up Using
						</span>

						<a class="txt2" href="{{ route('register') }}">{{ __('Register') }}</a>
						
					</div>

					
				</form>
			</div>
		</div>
	</div>

	@endsection
	



