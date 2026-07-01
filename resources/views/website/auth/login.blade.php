<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #e3f2ff, #f8e8ff);
            min-height: 100vh;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid #ffffff80;
        }

        .login-title {
            font-weight: 700;
            font-size: 28px;
            color: #2C1459;
        }

        .form-control {
            padding: 10px 12px;
            border-radius: 10px;
        }

        .btn-primar {
            border-radius: 10px;
            padding: 10px;
            font-size: 16px;
            background: #2C1459;
            color: white;
        }

        .btn-primar:hover {
            border-radius: 10px;
            padding: 10px;
            font-size: 16px;
            background: #2C1459;
            color: white;
        }

        .btn-outline-secondary {
            border-radius: 8px;
        }

        a {
            font-size: 14px;
        }

        .logo-box {
            width: 70px;
            height: 70px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0px 4px 10px #00000020;
            margin: 0 auto 15px auto;
        }

        .slider {
            width: 100%;
            /* height: 320px; */
            overflow: hidden;
            border-radius: 10px;
            position: relative;
        }

        .slides {
            display: flex;
            width: 100%;
            animation: slide 12s infinite
        }

        .slides img {
            width: 100%;
            /* height: 320px; */
            object-fit: cover;
            flex-shrink: 0;
        }

        @keyframes slide {
            0% {
                transform: translateX(0)
            }

            33% {
                transform: translateX(0)
            }

            38% {
                transform: translateX(-100%)
            }

            66% {
                transform: translateX(-100%)
            }

            71% {
                transform: translateX(-200%)
            }

            100% {
                transform: translateX(-200%)
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">

            <div class="col-md-5 col-lg-4">

                <div class="">
                    <div class="banner">
                        <div class="slider">
                            <div class="slides">
                                <img src="https://studentsoftheyear.com/img/events.png" alt="SOTY Event Banner">
                                <img src="https://studentsoftheyear.com/sliderphoto/175362484139721.png"
                                    alt="SOTY Slider Banner">
                                <img src="https://studentsoftheyear.com/sliderphoto/175362484139721.png"
                                    alt="SOTY Slider Banner 2">
                            </div>
                        </div>
                    </div>
                    {{-- <div class="logo-box">
                        <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" width="38">
                    </div> --}}

                    {{-- <h3 class="text-center login-title mb-3">Welcome Back</h3> --}}
                    <p class="text-center text-muted mt-3">Login to continue your journey</p>

                    <!-- Session Messages -->
                    <div id="messages"></div>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login.submit') }}">
                        @csrf

                        <!-- Login Input -->
                        <div class="mb-3">
                            <label class="form-label">Email / Student ID</label>
                            <input type="text" class="form-control @error('login') is-invalid @enderror"
                                name="login" autocomplete="username" autofocus placeholder="Enter your login ID"
                                value="{{ old('login') }}">
                            @error('login')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label">Password</label>

                            <div class="input-group">
                                <input type="password" class="form-control" name="password" id="password"
                                    autocomplete="current-password" placeholder="Enter password" required>

                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    👁️
                                </button>
                            </div>
                        </div>


                        <!-- Remember Me -->
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primar">Login</button>
                        </div>

                        <!-- Bottom Links -->
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            {{-- <a href="#" class="text-decoration-none">Forgot Password?</a> --}}
                            <a href="{{ route('registration.index') }}" class="btn btn-outline-secondary btn-sm">Sign
                                Up</a>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 4000,
        };

        @if (session('success'))
            toastr.success('{{ session('success') }}');
        @endif

        @if (session('error'))
            toastr.error('{{ session('error') }}');
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
        @endif

        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>

</body>

</html>
