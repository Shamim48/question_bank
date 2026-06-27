<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
    </style>
</head>

<body>
    @include('website.components.header')

    <main class="container">
        @if ($errors->any())
            <div class="alert">
                <ul style="padding-left: 16px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

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
                        <h4>How did you know about us?</h4>
                        <select name="known_from">
                            <option value="">Please Select</option>
                            <option value="Facebook" {{ old('known_from') == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                            <option value="Mentor" {{ old('known_from') == 'Mentor' ? 'selected' : '' }}>Mentor</option>
                            <option value="Ambassador" {{ old('known_from') == 'Ambassador' ? 'selected' : '' }}>Ambassador</option>
                        </select>
                    </div>

                    <div class="card">
                        <h4>Participant Information</h4>
                        <input placeholder="Enter your full name *" type="text" name="name"
                            value="{{ old('name') }}" required />
                        @error('name') <div class="error-text">{{ $message }}</div> @enderror

                        <div class="row">
                            <div>
                                <input placeholder="Enter your email *" type="email" name="email"
                                    value="{{ old('email') }}" required />
                                @error('email') <div class="error-text">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <input placeholder="Enter your phone number" type="text" name="phone"
                                    value="{{ old('phone') }}" />
                                @error('phone') <div class="error-text">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <input placeholder="Enter your institute name" type="text" name="institute_name"
                            value="{{ old('institute_name') }}" />

                        <div class="row">
                            <select name="class">
                                <option value="">Select Class</option>
                                @foreach (['Class 6','Class 7','Class 8','Class 9','Class 10','SSC','HSC','Degree','Masters'] as $cls)
                                    <option value="{{ $cls }}" {{ old('class') == $cls ? 'selected' : '' }}>{{ $cls }}</option>
                                @endforeach
                            </select>

                            <select name="group">
                                <option value="">Select Group</option>
                                @foreach (['Science','Commerce','Humanities','General'] as $grp)
                                    <option value="{{ $grp }}" {{ old('group') == $grp ? 'selected' : '' }}>{{ $grp }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <select name="gender">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <input type="date" name="date_of_birth" placeholder="Date of Birth"
                                value="{{ old('date_of_birth') }}" />
                        </div>
                    </div>

                    <div class="card">
                        <h4>Location</h4>
                        <div class="row">
                            <select id="division" name="division_id">
                                <option value="">Select Division</option>
                                @foreach ($divisions as $d)
                                    <option value="{{ $d->id }}" {{ old('division_id') == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>

                            <select id="district" name="district_id">
                                <option value="">Select District</option>
                                @foreach ($districts as $dis)
                                    <option value="{{ $dis->id }}" {{ old('district_id') == $dis->id ? 'selected' : '' }}>
                                        {{ $dis->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <select id="thana" name="upazilla_id">
                                <option value="">Select Upazilla</option>
                                @foreach ($upazillas as $u)
                                    <option value="{{ $u->id }}" {{ old('upazilla_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" name="address" placeholder="Village / Area"
                                value="{{ old('address') }}" />
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
                        Already registered? <a href="{{ route('user.login') }}">Login Here</a>
                    </p>
                </form>
            </div>
        </div>
    </main>

    @include('website.components.footer')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $('#division').on('change', function () {
            let divisionId = $(this).val();
            $('#district').html('<option value="">Loading...</option>');
            $('#thana').html('<option value="">Select Upazilla</option>');

            if (divisionId) {
                $.get('/get-districts/' + divisionId, function (data) {
                    let options = '<option value="">Select District</option>';
                    data.forEach(d => options += `<option value="${d.id}">${d.name}</option>`);
                    $('#district').html(options);
                });
            } else {
                $('#district').html('<option value="">Select District</option>');
            }
        });

        $('#district').on('change', function () {
            let districtId = $(this).val();
            $('#thana').html('<option value="">Loading...</option>');

            if (districtId) {
                $.get('/get-thanas/' + districtId, function (data) {
                    let options = '<option value="">Select Upazilla</option>';
                    data.forEach(t => options += `<option value="${t.id}">${t.name}</option>`);
                    $('#thana').html(options);
                });
            } else {
                $('#thana').html('<option value="">Select Upazilla</option>');
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
