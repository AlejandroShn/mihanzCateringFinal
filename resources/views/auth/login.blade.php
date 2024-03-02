@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/form.css') }}">
<div class="filter">
      <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fw-bolder text-uppercase d-flex justify-content-center">Login</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                    @csrf
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email"  class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus="">

                                                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary w-75">
                                {{ __('Login') }}
                                </button>
                                <div class=" ">
                                    
                                    <a class=" p-3 mr-5 " href="{{ route('password.request') }}">
                                        <i>Forgot Password?</i>
                                    </a>
                                    <i class="mr-5">Don't have account?<a class="" href="{{ route('register') }}">{{ __('Register') }}</a></i>
                                    
                                </div>
                                  <div>  </div>
                                                            </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
