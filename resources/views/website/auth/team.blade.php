<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Core Team Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
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
            cursor: pointer;
            background: var(--secondary);
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 6px;
            font-weight: 600;
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

        .image-upload-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .image-placeholder {
            width: 130px;
            height: 150px;
            border: 2px dashed #ccc;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
            overflow: hidden;
        }

        .image-placeholder:hover {
            border-color: #dc3545;
        }

        .image-placeholder span {
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }

        #imagePreview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .password-wrapper {
            position: relative;
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
            font-size: 13px;
        }

        input:has(+ .error-text),
        select:has(+ .error-text) {
            border-color: red;
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
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="padding-left: 16px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="title">
            <h2>Core Team Registration</h2>
            <p>Join Our Core Team</p>
        </div>

        <div class="grid">
            <div class="banner">
                <div class="slider">
                    <div class="slides">
                        <img src="https://studentsoftheyear.com/img/events.png" alt="Banner">
                        <img src="https://studentsoftheyear.com/sliderphoto/175362484139721.png" alt="Banner 2">
                        <img src="https://studentsoftheyear.com/sliderphoto/175362484139721.png" alt="Banner 3">
                    </div>
                </div>
            </div>

            <div>
                <form method="POST" action="{{ route('registration.team.submit') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Personal Information -->
                    <div class="card">
                        <h4>Personal Information*</h4>

                        <!-- Image Upload -->
                        <div class="image-upload-wrapper mb-3">
                            <div class="image-placeholder" id="imagePlaceholder"
                                onclick="document.getElementById('imageUpload').click();">
                                <span id="placeholderText">Image Upload</span>
                                <img id="imagePreview" src="#" alt="Profile Preview"
                                    style="display:none; max-width: 100%; border-radius: 8px;">
                            </div>
                            @error('image')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                            <input type="file" id="imageUpload" name="image" accept="image/*"
                                onchange="previewImage(this)" hidden>
                            <small style="color:#777; font-size:12px; margin-top:6px;">Upload Profile Image* (Max: 2MB)</small>
                        </div>

                        <div class="row">
                            <div>
                                <input type="text" name="first_name" value="{{ old('first_name') }}"
                                    placeholder="First Name *">
                                @error('first_name') <div class="error-text">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <input type="text" name="last_name" value="{{ old('last_name') }}"
                                    placeholder="Last Name">
                            </div>
                        </div>

                        <div class="row">
                            <div>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email *">
                                @error('email') <div class="error-text">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                    placeholder="Phone Number">
                                @error('phone') <div class="error-text">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <input type="text" name="whatsapp" placeholder="Whatsapp No"
                                value="{{ old('whatsapp') }}">
                            <input type="text" name="telegram" placeholder="Telegram No"
                                value="{{ old('telegram') }}">
                        </div>

                        {{-- Division + District --}}
                        <div class="row">
                            <div class="form-group">
                                <select id="division" name="division_id">
                                    <option value="">Select Division</option>
                                    @foreach ($divisions as $d)
                                        <option value="{{ $d->id }}"
                                            {{ old('division_id') == $d->id ? 'selected' : '' }}>
                                            {{ $d->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <select id="district" name="district_id">
                                    <option value="">Select District</option>
                                    @foreach ($districts as $dis)
                                        <option value="{{ $dis->id }}"
                                            {{ old('district_id') == $dis->id ? 'selected' : '' }}>
                                            {{ $dis->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Thana + Address --}}
                        <div class="row">
                            <div class="form-group">
                                <select id="thana" name="upazilla_id">
                                    <option value="">Select Upazilla</option>
                                    @foreach ($upazillas as $u)
                                        <option value="{{ $u->id }}"
                                            {{ old('upazilla_id') == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" name="address" placeholder="Village / Area"
                                    value="{{ old('address') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Team Details -->
                    <div class="card">
                        <h4>Team Details*</h4>
                        <div class="row">
                            <select name="role" required>
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                        {{ $role }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="season_id" required>
                                <option value="">Select Season</option>
                                @foreach ($seasons as $season)
                                    <option value="{{ $season->id }}"
                                        {{ old('season_id') == $season->id ? 'selected' : '' }}>
                                        {{ $season->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Institute Details -->
                    <div class="card">
                        <h4>Institute Details*</h4>
                        <div class="row">
                            <input type="text" name="institute_name" placeholder="Institute Name"
                                value="{{ old('institute_name') }}">
                            <input type="text" name="designation" placeholder="Class/Level/Designation"
                                value="{{ old('designation') }}">
                        </div>
                        <div class="row">
                            <input type="text" name="department"
                                placeholder="Group/Department/Subject (Optional)"
                                value="{{ old('department') }}">
                            <input type="text" name="institute_mobile" placeholder="Institute Mobile"
                                value="{{ old('institute_mobile') }}">
                        </div>
                        <div class="row">
                            <input type="email" name="institute_email" placeholder="Institute Email"
                                value="{{ old('institute_email') }}">
                            <input type="text" name="eiin_no" placeholder="Institute EIIN NO"
                                value="{{ old('eiin_no') }}">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="card">
                        <h4>Create Password*</h4>
                        <div class="row divided">
                            <div class="password-wrapper">
                                <input type="password" name="password" id="password" placeholder="Password *"
                                    required>
                                <span class="eye" onclick="togglePassword('password', this)">&#128065;&#65039;</span>
                                @error('password')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="password-wrapper">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    placeholder="Confirm Password *" required>
                                <span class="eye"
                                    onclick="togglePassword('password_confirmation', this)">&#128065;&#65039;</span>
                            </div>
                        </div>
                    </div>

                    <div class="submit">
                        <button type="submit">Register Team</button>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize Select2 on location dropdowns
            $('#division').select2({ placeholder: 'Select Division', allowClear: true });
            $('#district').select2({ placeholder: 'Select District', allowClear: true });
            $('#thana').select2({ placeholder: 'Select Upazilla', allowClear: true });
        });

        // Division -> District
        $('#division').on('change', function () {
            let divisionId = $(this).val();
            $('#district').html('<option value="">Select District</option>').trigger('change');
            $('#thana').html('<option value="">Select Upazilla</option>').trigger('change');

            if (divisionId) {
                $.get('/get-districts/' + divisionId, function (data) {
                    let options = '<option value="">Select District</option>';
                    data.forEach(d => options += `<option value="${d.id}">${d.name}</option>`);
                    $('#district').html(options);
                    $('#district').select2({ placeholder: 'Select District', allowClear: true });
                });
            }
        });

        // District -> Thana
        $('#district').on('change', function () {
            let districtId = $(this).val();
            $('#thana').html('<option value="">Select Upazilla</option>').trigger('change');

            if (districtId) {
                $.get('/get-thanas/' + districtId, function (data) {
                    let options = '<option value="">Select Upazilla</option>';
                    data.forEach(t => options += `<option value="${t.id}">${t.name}</option>`);
                    $('#thana').html(options);
                    $('#thana').select2({ placeholder: 'Select Upazilla', allowClear: true });
                });
            }
        });

        function previewImage(input) {
            const placeholderText = document.getElementById('placeholderText');
            const preview = document.getElementById('imagePreview');

            if (input.files && input.files[0]) {
                if (input.files[0].size > 2048000) {
                    alert('File is too big! Max size is 2MB.');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    placeholderText.style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

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
