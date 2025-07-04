<nav class="navbar">
    <div class="navbar-container">
        <!-- Logo -->
        <div class="logo">
            <a href="{{ route('user.home') }}" class="logo-link">PartnerGO</a>
        </div>
        
        <!-- Search Bar -->
        <div class="search-bar-nav">
            <div class="search-wrapper-nav">
                <div class="search-icon-nav">
                    <svg xmlns="http://www.w3.org/2000/svg" class="search-svg-nav" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" id="search" class="search-input-nav" placeholder="Search">
            </div>
        </div>
        
        <!-- Navigation Links -->
        <div class="nav-links">
            <a href="{{ route('user.home') }}" class="nav-link">Home</a>
            <a href="{{ route('partners') }}" class="nav-link">Find Partners</a>
            <a href="{{ route('proposals.list') }}" class="nav-link">My Proposals</a>
        </div>
        
        <!-- Notification, Message, Profile Picture -->
        <div class="user-section">
            <a href="{{ route('message') }}" class="message-button" title="Messages">
                <img src="{{ asset('assets/messages.png') }}" alt="Messages" class="icon">
            </a>
            
            <div class="notif-dropdown" id="notifDropdown">
                <button class="notif-button" id="notifToggle" aria-haspopup="true" aria-expanded="false" aria-controls="notifMenu" title="Notifications">
                    <img src="{{ asset('assets/notif.png') }}" alt="Notifications" class="icon">
                </button>
                <div class="notif-menu" id="notifMenu" role="menu" aria-labelledby="notifToggle">
                    <div class="notif-header">ALERTS CENTER</div>

                    @forelse($notifications as $notif)
                        <div class="notif-item">
                            <div class="notif-dot" style="background-color: {{ $notif->color ?? '#999' }}"></div>
                            <div class="notif-content">
                                <div class="notif-date">{{ \Carbon\Carbon::parse($notif->created_at)->format('F j, Y') }}</div>
                                <div class="notif-text {{ is_null($notif->read_at) ? 'unread' : '' }}">
                                    {{ $notif->Content }}
                                    @if(is_null($notif->read_at))
                                        <span class="notif-unread-dot"></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="notif-item empty">No notifications</div>
                    @endforelse
                </div>
            </div>



            <div class="dropdown" id="profileDropdown">
                <button class="profile-button" id="dropdownToggle" aria-haspopup="true" aria-expanded="false" aria-controls="dropdownMenu">
                    <img src="{{ asset('assets/profile.png') }}" alt="Profile" class="profile-pic" />
                </button>
                <div class="dropdown-menu" id="dropdownMenu" role="menu" aria-labelledby="dropdownToggle">
                    <a href="#" role="menuitem">Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" role="menuitem">Logout</button>
                    </form>
                </div>
            </div>
        </div>
        
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
            <a href="{{ route('user.home') }}" class="mobile-link">Home</a>
            <a href="{{ route('partners') }}" class="mobile-link">Find Partners</a>
            <a href="{{ route('proposals.list') }}" class="mobile-link">My Proposals</a>
            <a href="{{ route('message') }}" class="message-button" title="Messages">
                <img src="{{ asset('assets/messages.png') }}" alt="Messages" class="icon">
            </a>
            
            <div class="notif-dropdown" id="notifDropdown">
                <button class="notif-button" id="notifToggle" aria-haspopup="true" aria-expanded="false" aria-controls="notifMenu" title="Notifications">
                    <img src="{{ asset('assets/notif.png') }}" alt="Notifications" class="icon">
                </button>
                <div class="notif-menu" id="notifMenu" role="menu" aria-labelledby="notifToggle">
                    <div class="notif-header">ALERTS CENTER</div>

                    @forelse($notifications as $notif)
                        <div class="notif-item">
                            <div class="notif-dot" style="background-color: {{ $notif->color ?? '#999' }}"></div>
                            <div class="notif-content">
                                <div class="notif-date">{{ \Carbon\Carbon::parse($notif->created_at)->format('F j, Y') }}</div>
                                <div class="notif-text {{ is_null($notif->read_at) ? 'unread' : '' }}">
                                    {{ $notif->Content }}
                                    @if(is_null($notif->read_at))
                                        <span class="notif-unread-dot"></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="notif-item empty">No notifications</div>
                    @endforelse
                </div>
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

        const toggle = document.getElementById('dropdownToggle');
        const menu = document.getElementById('dropdownMenu');

        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            const isVisible = menu.classList.contains('visible');
            if (isVisible) {
            menu.classList.remove('visible');
            toggle.setAttribute('aria-expanded', 'false');
            } else {
            menu.classList.add('visible');
            toggle.setAttribute('aria-expanded', 'true');
            }
        });

        // Close dropdown on outside click
        document.addEventListener('click', function () {
            menu.classList.remove('visible');
            toggle.setAttribute('aria-expanded', 'false');
        });

        const notifToggle = document.getElementById('notifToggle');
        const notifMenu = document.getElementById('notifMenu');

        notifToggle.addEventListener('click', () => {
            notifMenu.classList.toggle('show');
        });

        document.addEventListener('click', function (e) {
            if (!notifToggle.contains(e.target) && !notifMenu.contains(e.target)) {
                notifMenu.classList.remove('show');
            }
        });
    });    
</script>
