@extends('layouts.auth')
@section('title')
    Register
@endsection
@section('content')
    <section class="body-sign">
        <div class="center-sign">
            <a href="/" class="logo pull-left">
                <img src="{{ asset('assets/images/logo.png') }}" height="54" alt="Porto Admin" />
            </a>
            <div class="panel panel-sign">
                <div class="panel-title-sign mt-xl text-right">
                    <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Sign Up</h2>
                </div>
                <div class="panel-body">
                    @if (isset($url))
                        <form method="POST" action='{{ url("$url/register") }}' aria-label="{{ __('Register') }}">
                        @else
                            <form method="POST" action="{{ route('register') }}" aria-label="{{ __('Register') }}">
                    @endif
                    @csrf
                    <div class="form-group mb-lg">
                        <label>Name</label>
                        <input id="name" type="text"
                            class="form-control input-lg @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name') }}" required autocomplete="name" autofocus />
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-lg">
                        <label>E-mail Address</label>
                        <input id="email" type="email"
                            class="form-control input-lg @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" required autocomplete="email" />
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-lg">
                        <label>Password</label>
                        <input id="password" type="password"
                            class="form-control input-lg @error('password') is-invalid @enderror" name="password" required
                            autocomplete="new-password" />
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mb-lg">

                        <label>Password Confirmation</label>
                        <input id="password-confirm" type="password" class="form-control input-lg"
                            name="password_confirmation" required autocomplete="new-password" />
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                            <div class="checkbox-custom checkbox-default">
                                <input id="AgreeTerms" name="agreeterms" type="checkbox" required />
                                <label for="AgreeTerms">I agree with <a href="#">terms of use</a></label>
                            </div>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button type="submit" class="btn btn-primary hidden-xs">Sign Up</button>
                            <button type="submit" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Sign
                                Up</button>
                        </div>
                    </div>


                    <p class="text-center">Already have an account? <a href="{{ route('login') }}">Sign In!</a>

                        </form>
                </div>
            </div>


        </div>
    </section>
    <p class="text-center text-muted mt-md mb-md">© Copyright 2018. All
        rights reserved to <a href="https://nexuvoice.com">Nexuvoice</a></p>
@endsection
