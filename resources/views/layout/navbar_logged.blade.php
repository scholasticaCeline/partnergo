<nav class="navbar">
    <div class="navbar-container">
        <!-- Logo -->
        <div class="logo">
            <a href="{{ route('home') }}" class="logo-link">PartnerGO</a>
        </div>
        
        <!-- Search Bar -->
        <div class="search-bar">
            <div class="search-wrapper">
                <div class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="search-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" id="search" class="search-input" placeholder="Search">
            </div>
        </div>
        
        <!-- Navigation Links -->
        <div class="nav-links">
            <a href="{{ route('home') }}" class="nav-link">Home</a>
            <a href="{{ route('partners') }}" class="nav-link">Find Partners</a>
            <a href="{{ route('proposals') }}" class="nav-link">My Proposals</a>
        </div>
        
        <!-- Notification, Message, Profile Picture -->
        <div class="user-section">
            <button class="message-button" title="Messages">
                <img src="{{ asset('assets/messages.png') }}" alt="Messages" class="icon">
            </button>
            <button class="notif-button" title="Notifications">
                <img src="{{ asset('assets/notif.png') }}" alt="Notifications" class="icon">
            </button>
            <img src="{{ asset('assets/profile.png') }}" alt="Profile" class="profile-pic" title="Profile">
        </div>


        <!-- Notification, Message, Profile Picture -->
        {{-- @auth --}}
        {{-- <div class="user-section">
            <a href="{{ route('notifications') }}" class="notif-button" title="Notifications">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405C18.21 14.79 18 14.29 18 13.764V11a6 6 0 00-12 0v2.764c0 .526-.21 1.026-.595 1.831L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </a>
            <a href="{{ route('messages') }}" class="message-button" title="Messages">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.964 9.964 0 01-4.13-.876L3 21l1.49-3.986A8.963 8.963 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </a>
            <a href="{{ route('profile') }}" class="profile-link">
                <img src="{{ Auth::user()->profile_picture_url ?? '/images/default-profile.jpg' }}"
                    alt="Profile" class="profile-pic" title="Profile">
            </a>
        </div> --}}
        {{-- @endauth --}}
        
        <!-- Mobile menu button -->
        <div class="mobile-menu-button">
            <button type="button" aria-controls="mobile-menu" aria-expanded="false" class="menu-button">
                <span class="sr-only">Open main menu</span>
                <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>
    
    <!-- Mobile menu, show/hide based on menu state -->
    <div class="mobile-menu" id="mobile-menu">
        <div class="mobile-links">
            <a href="{{ route('home') }}" class="mobile-link">Home</a>
            <a href="{{ route('partners') }}" class="mobile-link">Find Partners</a>
            <a href="{{ route('proposals') }}" class="mobile-link">My Proposals</a>
            <div class="auth-mobile-buttons">
                <a href="{{ route('login') }}" class="auth-mobile-button auth-login">
                    Login
                </a>
                <a href="{{ route('register') }}" class="auth-mobile-button auth-signup">
                    Sign Up
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.querySelector('[aria-controls="mobile-menu"]');
        const mobileMenu = document.getElementById('mobile-menu');
        
        menuButton.addEventListener('click', function() {
            const expanded = menuButton.getAttribute('aria-expanded') === 'true';
            menuButton.setAttribute('aria-expanded', !expanded);
            mobileMenu.classList.toggle('hidden');
        });
    });
</script>
