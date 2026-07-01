<style>
   .navbar {
        background: #2C1459;
        padding: 18px 0;
        color: #fff;
        width: 100%;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .nav-container {
        width: 92%;
        margin: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* LOGO */
    .logo img {
        width: 60px;
    }

    /* DESKTOP MENU */
    .nav-links {
        list-style: none;
        display: flex;
        align-items: center;
        gap: 22px;
    }

    .nav-links li {
        position: relative;
    }

    .nav-links li a {
        text-decoration: none;
        color: #fff;
        font-size: 15px;
        font-weight: 500;
    }

    /* HOVER */
    .nav-links li a:hover {
        color: #ffcb57;
        transition: 0.2s;
    }

    /* DROPDOWN */
    .dropdown-menu {
        display: none;
        position: absolute;
        background: #fff;
        color: #000;
        list-style: none;
        min-width: 185px;
        top: 100%;
        left: 0;
        border-radius: 6px;
        padding: 8px 0;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        z-index: 999;
    }

    .dropdown-menu li a,
    .dropdown-menu li button {
        display: block;
        padding: 8px 15px;
        text-decoration: none;
        color: #000;
        background: none;
        border: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }

    .dropdown-menu li a:hover,
    .dropdown-menu li button:hover {
        background: #f3f3f3;
    }

    /* RIGHT SIDE */
    .right-side {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .profile-btn {
        background: #ff6624;
        padding: 6px 12px;
        color: white;
        border: none;
        cursor: pointer;
    }

    /* HAMBURGER */
    .hamburger {
        display: none;
        font-size: 30px;
        cursor: pointer;
    }

    /* PROFILE DROPDOWN FIX */
    .profile-dropdown {
        position: relative;
    }

    .profile-dropdown .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        display: none;
        background: #fff;
        color: #000;
        min-width: 135px;
        border-radius: 6px;
        padding: 8px 0;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        z-index: 1000;
    }

    .profile-dropdown .dropdown-menu li a,
    .profile-dropdown .dropdown-menu li button {
        display: block;
        padding: 8px 15px;
        color: #000;
        text-decoration: none;
        background: none;
        border: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }

    .profile-dropdown .dropdown-menu li a:hover,
    .profile-dropdown .dropdown-menu li button:hover {
        background: #f3f3f3;
    }

    /* MOBILE CLOSE BUTTON STYLES (New) */
    .mobile-close-btn {
        position: absolute;
        top: 25px;
        right: 20px;
        font-size: 30px;
        color: #fff;
        cursor: pointer;
        z-index: 1002;
        line-height: 1;
        display: none;
        /* Hidden on desktop */
    }

    /* MOBILE RESPONSIVE */
    @media (max-width: 900px) {

        .nav-links {
            position: fixed;
            right: -270px;
            top: 0;
            width: 260px;
            height: 100vh;
            background: #2C1459;
            flex-direction: column;
            text-align: left;
            /* Reduced padding-top to make space for the internal close button */
            padding-top: 60px;
            gap: 22px;
            transition: 0.4s ease;
            z-index: 1000;
        }

        .nav-links.show {
            right: 0;
        }

        .dropdown-menu {
            background: #1C0C41;
            width: 100%;
            position: relative;
            top: 0;
            border-radius: 0;
            box-shadow: none;
        }

        .dropdown-menu li a,
        .dropdown-menu li button {
            color: #fff;
        }

        .dropdown-menu li a:hover,
        .dropdown-menu li button:hover {
            background: #2C1459;
        }

        .nav-links li {
            width: 100%;
            padding-left: 20px;
        }

        .hamburger {
            display: block;
        }

        /* Show the new internal close button on mobile */
        .mobile-close-btn {
            display: block;
        }

        /* Removed old hamburger-to-X trick that wasn't working */
    }

    /* DESKTOP DROPDOWN ON HOVER */
    @media (min-width: 901px) {
        .nav-links li:hover>.dropdown-menu {
            display: block;
        }
    }

    /* Base Login Button */
    .btn-login {
        display: inline-block;
        font-size: 16px;
        font-weight: 600;
        color: #fff;
        border: none;
        padding: 6px 12px;
        background: #01ab365e;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .dropdown-logout-btn {
        background-color: #2c1459 !important;
        color: wheat !important;
        border-radius: 4px;
        padding: 8px 15px !important;
    }

    .dropdown-logout-btn:hover {
        background-color: #1C0C41 !important;
    }
</style>

<nav class="navbar">
    <div class="nav-container">

        <div class="logo">
            <a href="/"><img src="https://studentsoftheyear.com/public/logo.png" alt="Logo"></a>
        </div>

        <ul class="nav-links" id="navMenu">

            {{-- NEW: MOBILE CLOSE BUTTON PLACED INSIDE THE SLIDING MENU --}}
            <div class="mobile-close-btn" id="closeBtn">&#x2715;</div>

            <li><a href="https://studentsoftheyear.com/">Home</a></li>
            <li><a href="https://studentsoftheyear.com/about">About Us</a></li>
            <li><a href="https://studentsoftheyear.com/partners">Partners</a></li>
            <li><a href="https://studentsoftheyear.com/faq">FAQ</a></li>
            <li class="dropdown">
                <a href="https://studentsoftheyear.com/teams" class="dropdown-toggle" data-dropdown-id="team-menu">Team
                    ▼</a>
                <ul class="dropdown-menu" id="team-menu">
                    <li><a href="#">Mentors</a></li>
                    <li><a href="#">Ambassadors</a></li>
                    <li><a href="#">Edu Scholars</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="https://studentsoftheyear.com/events" class="dropdown-toggle"
                    data-dropdown-id="events-menu">Events ▼</a>
                <ul class="dropdown-menu" id="events-menu">
                    <li><a href="#">Season 3</a></li>
                </ul>
            </li>

            <li><a href="https://studentsoftheyear.com/gallery">Gallery</a></li>
            <li><a href="https://studentsoftheyear.com/winners">Winners</a></li>

            <li><a href="https://studentsoftheyear.com/joinsoty">Join SOTY</a></li>




            <li><a href="https://studentsoftheyear.com/blogs">Blog</a></li>
        </ul>

        <div class="right-side">
            <div class="header-right">

                @guest
                    <a href="{{ route('login') }}" class="btn-login">Login</a>
                    <a href="{{ route('registration.index') }}" class="btn-login">Signup</a>
                @endguest

                @auth
                    <div class="profile-dropdown">
                        <button class="profile-btn dropdown-toggle" data-dropdown-id="profile-menu">
                            @if (auth()->user()->student)
                                Student
                            @elseif (auth()->user()->isAdmin())
                                Admin
                            @else
                                User
                            @endif
                            &#9660;
                        </button>

                        <ul class="dropdown-menu" id="profile-menu">
                            @if (auth()->user()->isStudent())
                                <li><a href="{{ route('student.profile') }}">Profile</a></li>
                                <li><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                            @endif

                            @if (auth()->user()->isAdmin())
                                <li><a href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                            @endif

                            <li>
                                <form method="POST" action="{{ route('user.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-logout-btn">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth

            </div>
        </div>

        <div class="hamburger" id="hamburgerBtn">&#9776;</div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const navMenu = document.getElementById('navMenu');
        const closeBtn = document.getElementById('closeBtn');
        const allDropdownToggles = document.querySelectorAll('.dropdown-toggle');
        const allDropdownMenus = document.querySelectorAll('.dropdown-menu');

        const closeAllDropdowns = (excludeMenu = null) => {
            allDropdownMenus.forEach(menu => {
                if (window.innerWidth <= 900) {
                    menu.style.display = 'none';
                } else if (menu !== excludeMenu) {
                    menu.style.display = 'none';
                }
            });
        };

        const closeMainMenu = () => {
            navMenu.classList.remove('show');
            closeAllDropdowns();
        };

        hamburgerBtn.addEventListener('click', () => {
            navMenu.classList.toggle('show');
            if (!navMenu.classList.contains('show')) {
                closeAllDropdowns();
            }
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', closeMainMenu);
        }

        navMenu.querySelectorAll('a, button[type="submit"]').forEach(item => {
            if (!item.classList.contains('dropdown-toggle')) {
                item.addEventListener('click', () => {
                    if (window.innerWidth <= 900) {
                        closeMainMenu();
                    }
                });
            }
        });

        allDropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                if (window.innerWidth <= 900 || e.target.closest('.profile-dropdown')) {
                    e.preventDefault();
                }
                e.stopPropagation();

                const menuId = this.getAttribute('data-dropdown-id');
                const menu = document.getElementById(menuId);

                if (menu) {
                    closeAllDropdowns(menu);
                    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
                }
            });
        });

        document.addEventListener('click', function (e) {
            let isClickInsideDropdown = e.target.closest('.dropdown, .profile-dropdown');
            if (!isClickInsideDropdown) {
                closeAllDropdowns();
            }
            if (window.innerWidth > 900) {
                navMenu.classList.remove('show');
            }
        });
    });
</script>
