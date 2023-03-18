@extends('layouts.auth')
@section('title')
    Reset password
@endsection
@section('content')
    <section class="body-sign">
        <div class="center-sign">
            <a href="/" class="logo pull-left">
                <img src="{{ asset('assets/images/logo.png') }}" height="54" alt="Porto Admin" />
            </a>

            <div class="panel panel-sign">
                <div class="panel-title-sign mt-xl text-right">
                    <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Sign In</h2>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group mb-lg">
                            <input id="email" type="email"
                                class="form-control input-lg @error('email') is-invalid @enderror" name="email"
                                value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="form-group mb-lg">
                            <label for="password">{{ __('Password') }}</label>
                            <input id="password" type="password"
                                class="form-control input-lg @error('password') is-invalid @enderror" name="password"
                                required autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-lg">
                            <label for="password-confirm">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control input-lg"
                                name="password_confirmation" required autocomplete="new-password">

                        </div>

                        <div class="row mb-0">
                            <div class="col-md-12 offset-md-4">
                                <button type="submit" class="btn btn-primary  btn-block">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </section>
    <p style="position: fixed;bottom: 0;width: 100%;" class="text-center text-muted mt-md mb-md">© Copyright 2018. All
        rights reserved to <a href="https://nexuvoice.com">Nexuvoice</a></p>
@endsection
