@extends('layouts.app')

@section('content')

<main>
    <div class="container">

      <section class="section register" style="margin-top: 30px;">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
              <div class="card mb-3">
                <div class="card-body">
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                    <p class="text-center small">Enter your email & password to login</p>
                  </div>
                
                  <form class="row g-3 needs-validation" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="col-12">
                    <label for="email" class="col-md-4 col-form-label">{{ __('Email') }}</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        <div class="invalid-feedback">Please enter your email address</div>
                        @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                      <div class="invalid-feedback">Please enter your password!</div>
                      @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                    </div>

                   
                    <div class="col-12">
                      {{-- <button class="btn btn-primary w-100" type="submit">Login</button> --}}

                      <button type="submit" class="btn btn-primary">
                        {{ __('Login') }}
                    </button>
<!-- 
                    @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif -->
                    </div>
                    <!-- <div class="col-12">
                      <p class="small mb-0">Don't have account? <a  href="{{ route('register') }}">{{ __('Register') }}>Create an account</a></p>
                    </div> -->
                  </form>

                </div>
              </div>

              <!-- <div class="credits">
                Designed by <a href="https://webol.co.uk/">Webol</a>
              </div> -->

            </div>
          </div>
        </div>

      </section>

    </div>
  </main>


@endsection
