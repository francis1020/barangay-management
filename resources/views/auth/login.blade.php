@extends('layouts.app')

@section('title', 'MIS Login')

@push('styles')
    <style>
        #main {
            background: #f8f9fc;
            min-height: 100vh;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .split-container {
            display: flex;
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            background: #fff;
            border-radius: 2rem;
            box-shadow: 0 1.5rem 4rem rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeInScale 0.6s ease-out;
        }

        /* Panel A: Visual Branding */
        .visual-panel {
            flex: 1;
            background: var(--sidebar-bg);
            /* #1a2e44 */
            color: white;
            padding: 4rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .visual-panel::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('{{ asset("assets/img/login-graphic.png") }}') center center no-repeat;
            background-size: 80%;
            opacity: 0.15;
        }

        .visual-content {
            position: relative;
            z-index: 1;
        }

        .visual-content h1 {
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            letter-spacing: -1px;
        }

        .visual-content p {
            font-size: 1.1rem;
            opacity: 0.8;
            line-height: 1.6;
        }

        /* Panel B: Login Form */
        .form-panel {
            flex: 1;
            padding: 4.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #fff;
        }

        .form-panel h2 {
            font-weight: 700;
            color: #1a2e44;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .form-panel p.subtitle {
            color: #858796;
            margin-bottom: 2.5rem;
        }

        .form-control {
            border-radius: 1rem;
            padding: 0.85rem 1.25rem;
            border: 2px solid #eaecf4;
            background: #f8f9fc;
            transition: all 0.2s;
        }

        .form-control:focus {
            background: #fff;
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }

        .btn-login {
            background: #0d6efd;
            border: none;
            border-radius: 1rem;
            padding: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .btn-login:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1.5rem rgba(13, 110, 253, 0.2);
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .link-primary {
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .link-primary:hover {
            text-decoration: underline;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @media (max-width: 991.98px) {
            .split-container {
                max-width: 450px;
                flex-direction: column;
                border-radius: 1.5rem;
            }

            .visual-panel {
                padding: 3rem 2rem;
                min-height: 200px;
            }

            .visual-panel::before {
                background-size: 40%;
            }

            .visual-content h1 {
                font-size: 1.8rem;
            }

            .form-panel {
                padding: 2.5rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="split-container">
        <!-- Panel A: Visual Branding -->
        <div class="visual-panel">
            <div class="visual-content">
                <h1>MIS Portal</h1>
                <p>Empowering Barangay Governance through Smart, Data-Driven Management.</p>
                <div class="mt-4">
                    <span class="badge bg-white bg-opacity-10 py-2 px-3 rounded-pill">Secure Access</span>
                </div>
            </div>
        </div>

        <!-- Panel B: Login Form -->
        <div class="form-panel">
            <h2>Sign In</h2>
            <p class="subtitle">Please enter your credentials to continue.</p>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success border-0 rounded-3 mb-4" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold text-muted small text-uppercase">Email Address</label>
                    <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label for="password"
                            class="form-label fw-semibold text-muted small text-uppercase">Password</label>
                    </div>
                    <input id="password" type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror" required
                        autocomplete="current-password" placeholder="••••••••">
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
                        <label class="form-check-label text-muted small" for="remember_me">
                            Keep me signed in
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="link-primary" href="{{ route('password.request') }}">
                            Forgot?
                        </a>
                    @endif
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-login">
                        Access Dashboard
                    </button>
                </div>
            </form>

            <div class="mt-5 pt-3 border-top text-center text-muted small">
                &copy; {{ date('Y') }} Barangay Management System. All rights reserved.
            </div>
        </div>
    </div>
@endsection