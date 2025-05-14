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
        
        <!-- Login/Sign Up Buttons -->
        <div class="auth-buttons">
            <a href="{{ route('login') }}" class="auth-button auth-login">Login</a>
            <a href="{{ route('register') }}" class="auth-button auth-signup">Sign Up</a>
        </div>
        
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
