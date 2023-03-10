@extends('layouts.auth')
@section('title')
    Login
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
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if (isset($url))
                        <form method="POST" action='{{ url("$url/login") }}' aria-label="{{ __('Login') }}">
                        @else
                            <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                    @endif
                    @csrf
                    <div class="form-group mb-lg">
                        <label>Email</label>
                        <div class="input-group input-group-icon">
                            <input id="email" type="email"
                                class="form-control input-lg @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email" autofocus />
                            <span class="input-group-addon">
                                <span class="icon icon-lg">
                                    <i class="fa fa-user"></i>
                                </span>
                            </span>
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-lg">
                        @if (!isset($url))
                            @if (Route::has('password.request'))
                                <div class="clearfix">
                                    <a href="{{ route('password.request') }}" class="pull-right">Lost Password?</a>
                                </div>
                            @endif
                        @endif
                        <div class="input-group input-group-icon">
                            <input id="password" type="password"
                                class="form-control input-lg @error('password') is-invalid @enderror" name="password"
                                required autocomplete="current-password" />
                            <span class="input-group-addon">
                                <span class="icon icon-lg">
                                    <i class="fa fa-lock"></i>
                                </span>
                            </span>
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                            {{-- <div class="checkbox-custom checkbox-default">
                                    <input id="RememberMe" name="remember" type="checkbox" class="form-check-input"
                                        {{ old('remember') ? 'checked' : '' }} />
                                    <label for="remember">Remember Me</label>
                                </div> --}}
                        </div>
                        <div class="col-sm-4 text-right">
                            <button type="submit" class="btn btn-primary hidden-xs">Sign In</button>
                            <button type="submit" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Sign
                                In</button>
                        </div>
                    </div>
                    @if (!isset($url))
                        @if (Route::has('register'))
                            <p class="text-center">Don't have an account yet? <a href="{{ route('register') }}">Sign
                                    Up!</a>
                        @endif
                    @endif
                    </form>
                </div>
            </div>


        </div>
    </section>

    <p style="position: fixed;bottom: 0;width: 100%;" class="text-center text-muted mt-md mb-md">© Copyright 2018. All
        rights reserved to <a href="https://nexuvoice.com">Nexuvoice</a></p>


@endsection
