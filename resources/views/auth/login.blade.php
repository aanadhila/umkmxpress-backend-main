@extends('layouts.guest')

@section('content')
    <style>
        body {
            background-image: url({{ asset('assets/media/auth/bg4.jpg') }});
        }

        [data-theme="dark"] body {
            background-image: url({{ asset('assets/media/auth/bg4-dark.jpg') }});
        }
    </style>
    <div class="d-flex flex-column flex-column-fluid flex-lg-row">
        <div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">
            <div class="d-flex flex-column">
                <a href="{{ route('home') }}" class="mb-7">
                    <img alt="Logo" src="{{ asset('assets/img/logo.png') }}" />
                </a>
                <h2 class="text-white fw-normal m-0">Branding tools designed for your business</h2>
            </div>
        </div>
        <div class="d-flex flex-center w-lg-50 p-10">
            <div class="card rounded-3 w-md-550px">
                <div class="card-body p-10 p-lg-20">
                    <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="text-center mb-11">
                            <h1 class="text-dark fw-bolder mb-3">Sign In</h1>
                            <div class="text-gray-500 fw-semibold fs-6">Logistic Management</div>
                        </div>
                        <div class="fv-row mb-8">
                            <input type="text" placeholder="Email" id="email" name="email" autocomplete="off" class="form-control bg-transparent @error('email') is-invalid @enderror" required value="{{ old('email') }}" autofocus />
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="fv-row mb-3">
                            <input type="password" placeholder="Password" id="password" name="password" autocomplete="off" class="form-control bg-transparent @error('password') is-invalid @enderror" required />
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        @if (Route::has('password.request'))
                            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                                <div></div>
                                <a href="{{ route('password.request') }}" class="link-primary">Forgot Password ?</a>
                            </div>
                        @endif
                        <div class="d-grid mb-10">
                            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                                <span class="indicator-label">Login</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                        @if (Route::has('register'))
                            <div class="text-gray-500 text-center fw-semibold fs-6">Not a Member yet?
                                <a href="{{ route('register') }}" class="link-primary">Sign up</a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
