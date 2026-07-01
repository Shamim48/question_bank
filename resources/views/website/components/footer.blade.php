<style>
    .footer {
        background: #141221;
        color: white;
        padding: 40px 20px;
        margin-top: 60px;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 25px;
        max-width: 1100px;
        margin: 0 auto 30px auto;
    }

    .footer-grid h4 {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 12px;
        color: #ffcb57;
    }

    .footer-grid p {
        font-size: 13px;
        color: #ccc;
        margin-bottom: 6px;
    }

    .footer-grid a {
        display: block;
        font-size: 13px;
        color: #ccc;
        text-decoration: none;
        margin-bottom: 6px;
    }

    .footer-grid a:hover {
        color: #ffcb57;
    }

    .copyright {
        text-align: center;
        font-size: 13px;
        color: #888;
        border-top: 1px solid #2a2740;
        padding-top: 20px;
        max-width: 1100px;
        margin: 0 auto;
    }
</style>

<footer class="footer">
    <div class="footer-grid">
        <div>
            <h4>Quick Links</h4>
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ route('registration.index') }}">Register</a>
            <a href="{{ route('login') }}">Login</a>
        </div>

        <div>
            <h4>Student</h4>
            @auth
                @if (auth()->user()->isStudent())
                    <a href="{{ route('student.dashboard') }}">Dashboard</a>
                    <a href="{{ route('student.exams') }}">Exams</a>
                    <a href="{{ route('student.results') }}">Results</a>
                @endif
            @endauth
            @guest
                <p>Login to access student features</p>
            @endguest
        </div>

        <div>
            <h4>Contact</h4>
            <p>&#9993; info@questionbank.com</p>
            <p>&#128222; +880 1700-000000</p>
            <p>&#127968; Dhaka, Bangladesh</p>
        </div>
    </div>

    <div class="copyright">
        &copy; {{ date('Y') }} Question Bank. All rights reserved.
    </div>
</footer>
