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
                    <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Reset password</h2>
                </div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group mb-lg">
                            <label>Email</label>
                            <input id="email" type="email"
                                class="form-control nput-lg @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="row">
                            <!-- <div class="col-sm-8">
                                </div> -->
                            <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-primary btn-block  ">Send Reset
                                    Link</button>
                                <button type="submit" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Send Reset
                                    Link</button>
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
