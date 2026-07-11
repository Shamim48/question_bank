<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1f4f86;
            --secondary: #c7862d;
            --dark: #0e1a2b;
            --light: #f5f7fb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif
        }

        body {
            background: #fff;
            color: #333
        }

        a {
            text-decoration: none;
            color: inherit
        }

        .container {
            padding: 40px 6%;
        }

        .title {
            margin-bottom: 30px;
        }

        .title h2 {
            color: var(--primary);
            font-size: 28px
        }

        .title p {
            font-size: 15px;
            color: #555
        }

        .grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 30px;
        }

        .banner {
            border: 2px solid red;
            border-radius: 10px;
            padding: 10px;
            height: 330px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .banner img {
            max-width: 100%;
        }

        .card {
            background: #fff;
            border: 1px solid slategray;
            border-radius: 10px;
            padding: 18px;
            margin-bottom: 18px;
            box-shadow: rgb(187 187 187 / 35%) 0px 5px 5px;
        }

        .card h4 {
            font-size: 15px;
            margin-bottom: 10px;
            color: var(--primary);
        }

        select,
        input {
            width: 100%;
            margin-bottom: 10px;
            color: #757575;
            padding: 10px 12px;
            border: 1px solid #d6dbe6;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .submit {
            margin-top: 20px;
            text-align: right;
        }

        .submit button {
            background: var(--secondary);
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
        }

        .text-center a {
            color: red;
        }

        .slider {
            width: 100%;
            height: 320px;
            overflow: hidden;
            border-radius: 10px;
            position: relative;
        }

        .slides {
            display: flex;
            width: 300%;
            animation: slide 12s infinite
        }

        .slides img {
            width: calc(100% / 3);
            height: 320px;
            object-fit: cover;
            flex-shrink: 0;
        }

        @keyframes slide {
            0%   { transform: translateX(0) }
            33%  { transform: translateX(0) }
            38%  { transform: translateX(-33.333%) }
            66%  { transform: translateX(-33.333%) }
            71%  { transform: translateX(-66.666%) }
            100% { transform: translateX(-66.666%) }
        }

        @media(max-width:900px) {
            .grid { grid-template-columns: 1fr }
        }

        @media(max-width:450px) {
            .divided {
                display: flex;
                flex-direction: column
            }
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .eye {
            position: absolute;
            right: 12px;
            top: 42%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            user-select: none;
        }

        .alert {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
            border-color: #badbcc;
        }

        .error-text {
            color: red;
            font-size: 12px;
            margin-top: -8px;
            margin-bottom: 8px;
        }

        /* Select2 overrides to match form style */
        .select2-container {
            width: 100% !important;
            margin-bottom: 10px;
        }

        .select2-container--default .select2-selection--single {
            height: auto;
            padding: 10px 12px;
            border: 1px solid #d6dbe6;
            border-radius: 6px;
            font-size: 14px;
            color: #757575;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #757575;
            padding: 0;
            line-height: normal;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            top: 0;
            right: 8px;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            padding: 8px 10px;
            border-radius: 4px;
            border: 1px solid #d6dbe6;
            font-size: 13px;
        }

        .select2-dropdown {
            border: 1px solid #d6dbe6;
            border-radius: 6px;
            font-size: 14px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #1f4f86;
        }
    </style>
</head>

<body>
    @include('website.components.header')

    <main class="container">
        <div class="title">
            <h2>Join As a Participant</h2>
            <p>Fill in the form below to register as a student</p>
        </div>

        <div class="grid">
            <div class="banner">
                <div class="slider">
                    <div class="slides">
                        <img src="https://studentsoftheyear.com/img/events.png" alt="SOTY Event Banner">
                        <img src="https://studentsoftheyear.com/sliderphoto/175362484139721.png" alt="SOTY Slider Banner">
                        <img src="https://studentsoftheyear.com/img/events.png" alt="SOTY Event Banner">
                    </div>
                </div>
            </div>

            <div>
                <form method="POST" action="{{ route('registration.participant.submit') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="card">
                        <h4>Referral Code (optional)</h4>
                        <input placeholder="Enter referral code, if any" type="text" name="referral_code"
                            value="{{ old('referral_code', request('ref')) }}" />
                        @error('referral_code') <div class="error-text">{{ $message }}</div> @enderror
                    </div>

                    <div class="card">
                        <h4>How did you know about us?</h4>
                        <select name="known_from">
                            <option value="">Please Select</option>
                            <option value="Facebook" {{ old('known_from') == 'Facebook' ? 'selected' : '' }}>Facebook
                            </option>
                            <option value="Mentor" {{ old('known_from') == 'Mentor' ? 'selected' : '' }}>Mentor</option>
                            <option value="Ambassador" {{ old('known_from') == 'Ambassador' ? 'selected' : '' }}>
                                Ambassador
                            </option>
                        </select>
                    </div>

                    <div class="card">
                        <h4>Joining events</h4>
                        <select name="season_id">
                            <option value="">Please Select Season</option>
                            @foreach ($seasons as $season)
                                <option value="{{ $season->id }}"
                                    {{ old('season_id') == $season->id ? 'selected' : '' }}>
                                    {{ $season->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="card">
                        <h4>Participate Information</h4>
                        <div class="row">
                            <input placeholder="Enter your first name" type="text" name="first_name"
                                value="{{ old('first_name') }}" required />
                            <input placeholder="Enter your last name" type="text" name="last_name"
                                value="{{ old('last_name') }}" />
                        </div>
                        <div class="row">
                            <input placeholder="Enter your email" type="email" name="email"
                                value="{{ old('email') }}" />
                            <input placeholder="Enter your phone number" type="text" name="phone"
                                value="{{ old('phone') }}" />
                        </div>
                        <input placeholder="Enter your institute name" type="text" name="institute_name" value="{{ old('institute_name') }}" />
                        <div class="row">

                            <select name="class_id" id="classSelect">
                                <option value="">Please Select Class</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" data-group="{{ $class->group_id }}"
                                        {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>

                            <select id="groupSelect" name="group_id" class="form-control">
                                <option value="">Select Group</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}"
                                        data-id="{{ $group->id }}"
                                        {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                        {{ $group->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <h4>Password</h4>
                        <div class="row divided">
                            <div class="password-wrapper">
                                <input type="password" name="password" id="password" placeholder="Password *" required />
                                <span class="eye" onclick="togglePassword('password', this)">&#128065;&#65039;</span>
                            </div>
                            <div class="password-wrapper">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    placeholder="Confirm Password *" required />
                                <span class="eye" onclick="togglePassword('password_confirmation', this)">&#128065;&#65039;</span>
                            </div>
                        </div>
                        @error('password') <div class="error-text">{{ $message }}</div> @enderror
                    </div>

                    <div class="submit">
                        <button type="submit">Submit</button>
                    </div>
                    <p class="text-center" style="margin-top: 12px; font-size: 14px;">
                        Already registered? <a href="{{ route('login') }}">Login Here</a>
                    </p>
                </form>
            </div>
        </div>
    </main>

    @include('website.components.footer')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

        $(document).ready(function () {
            // Class → Group auto-select
            $('#classSelect').on('change', function () {
                const groupId = $(this).find(':selected').data('group');
                if (groupId) {
                    $('#groupSelect option[data-id="' + groupId + '"]').prop('selected', true);
                } else {
                    $('#groupSelect').val('');
                }
            });

            if ($('#classSelect').val()) {
                $('#classSelect').trigger('change');
            }
        });

        function togglePassword(id, el) {
            const input = document.getElementById(id);
            if (input.type === 'password') {
                input.type = 'text';
                el.textContent = '🙈';
            } else {
                input.type = 'password';
                el.textContent = '👁️';
            }
        }
    </script>
</body>

</html>
