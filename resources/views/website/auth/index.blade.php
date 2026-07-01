<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Selection</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* Container */
        .container {
            background: #fff;
            max-width: 1000px;
            width: 90%;
            padding: 50px 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            text-align: center;
            margin: 110px auto;
            position: relative;
            overflow: hidden;
        }


        h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
            color: #333;
        }

        .button-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            position: relative;
            z-index: 1;
        }

        .btn {
            padding: 40px 20px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 15px;
            text-decoration: none;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            min-width: 150px;
        }

        .btn span {
            margin-top: 10px;
            font-size: 14px;
            opacity: 0.9;
        }

        .student {
            background: #4e73df;
        }

        .core-team {
            background: #1cc88a;
        }

        .mentor {
            background: #f6c23e;
            color: #333;
        }

        .ambassador {
            background: #e74a3b;
        }

        .btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2);
            opacity: 0.95;
        }

        /* Responsive Text */
        @media (max-width: 768px) {
            h2 {
                font-size: 28px;
            }

            /* Container */
            .container {
                background: #fff;
                max-width: 1000px;
                width: 90%;
                padding: 50px 30px;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
                text-align: center;
                margin: 110px auto;
                position: relative;
                overflow: hidden;
            }

            .btn {
                padding: 30px 15px;
                font-size: 16px;
            }

            .navbar ul {
                gap: 15px;
            }
        }

        @media (max-width: 480px) {
            h2 {
                font-size: 24px;
            }

            .btn {
                padding: 25px 10px;
                font-size: 14px;
            }

            .navbar {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>

<body>
    <nav class="">
        @include('website.components.header')
    </nav>

    <div class="container">
        <h2>Select Your Registration Type</h2>
        <div class="button-group">
            <a href="{{ route('registration.participant') }}" class="btn student">
                Student Registration
                <span>Join as a participant</span>
            </a>
            <a href="{{ route('registration.core.team') }}" class="btn core-team">
                Core Team Registration
                <span>Join our core team</span>
            </a>
        </div>
    </div>
</body>
</html>
