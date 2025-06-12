@extends('layout.app')

@push('styles')
    <link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: '',
                    center: 'title',
                    right: 'prev,next'
                },
                height: 'auto',
                events: {!! json_encode($events) !!},
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: 'short'
                }
            });
            calendar.render();
        });
    </script>
@endpush

@section('content')

    <div class="dashboard">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <div class="welcome">
                    <h1>Welcome, {{ $user->name ?? 'User' }}</h1>
                    <p>Ready to explore new partnership opportunities today?</p>
                </div>
                <div class="header-pattern"></div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="main-container">
            <main class="main-content">
                <!-- Calendar Section -->
                <section class="calendar-section">
                    <h2>Calendar</h2>
                    <div class="calendar-container">
                        <div id="calendar"></div>
                        <!-- Calendar content would go here -->
                    </div>
                </section>

                <!-- Tasks Section -->
                <section class="tasks-section">
                    <h2>Upcoming Tasks</h2>
                    <div class="tasks-grid">
                        <!-- Task items would be populated here -->
                        @for ($i = 0; $i < 4; $i++)
                            <div class="task-item">
                                <!-- Task content would go here -->
                            </div>
                        @endfor
                    </div>
                </section>
            </main>

            <!-- Sidebar -->
            <aside class="sidebar">
                <!-- Proposals Section -->
                <section class="sidebar-section proposals-section">
                    <h2>Your Proposals</h2>
                    <div class="proposals-list">
                        @forelse ($proposals as $proposal)
                            <a href="{{ route('proposals.show', $proposal) }}" class="proposal-item-link">
                                <div class="proposal-item-content">
                                    <h3>{{ Str::limit($proposal->ProposalTitle, 35) }}</h3>
                                    <p>To: {{ $proposal->targetOrganization->Name }}</p>
                                </div>
                                <div class="status-badge status-{{ strtolower($proposal->ProposalStatus) }}">
                                    {{ ucfirst($proposal->ProposalStatus) }}
                                </div>
                            </a>
                        @empty
                            <div class="empty-state-small">
                                <p>You haven't sent any proposals yet.</p>
                            </div>
                        @endforelse
                    </div>
                    <a href="#" class="see-all">See all your proposals <span class="arrow">›</span></a>
                </section>

                <!-- Messages Section -->
                <section class="sidebar-section messages-section">
                    <h2>Recent Message</h2>
                    @if($latestUnreadMessage)
                        <div class="message-item">
                            <div class="message-avatar">
                                <img src="{{ $latestUnreadMessage->sender->avatar_url ?? asset('images/default-avatar.png') }}" alt="Avatar">
                            </div>
                            <div class="message-content">
                                <h3>{{ $latestUnreadMessage->sender->name }}</h3>
                                <p>{{ Str::limit($latestUnreadMessage->content, 60) }}</p>
                            </div>
                            <div class="message-time">{{ $latestUnreadMessage->created_at->format('H:i') }}</div>
                        </div>
                    @else
                        <div class="empty-state-small">
                            <p>No unread messages.</p>
                        </div>
                    @endif
                        <a href="#" class="see-all">See more messages <span class="arrow">›</span></a>
                </section>

                @if($organizations->isNotEmpty())
                    {{-- THIS SECTION SHOWS IF THE USER ALREADY HAS ONE OR MORE COMPANIES --}}
                    <section class="sidebar-section organization-section">
                        <h2>Your Companies</h2>
                        <div class="organization-list">
                            @foreach($organizations as $organization)
                            <div class="company-widget-card">
                                {{-- Top part with logo and name --}}
                                <div class="widget-header">
                                    <div class="organization-logo">
                                        {{ strtoupper(substr($organization->Name, 0, 1)) }}
                                    </div>
                                    <div class="organization-info">
                                        <h3>{{ $organization->Name }}</h3>
                                        <p>{{ $organization->OrganizationType }}</p>
                                    </div>
                                </div>

                                {{-- Bottom part with members and action buttons --}}
                                <div class="widget-footer">
                                    <div class="member-count">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.274.274H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.5 10a.5.5 0 0 0-1 0v3a.5.5 0 0 0 1 0v-3Zm-2-2a.5.5 0 0 0-1 0v5a.5.5 0 0 0 1 0v-5Zm-2-2a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7Z"/>
                                        </svg>
                                        <span>{{ $organization->users_count }} Members</span>
                                    </div>
                                    <div class="widget-actions">
                                        <a href="{{ route('organizations.show', $organization) }}" class="btn btn-secondary btn-sm">View</a>
                                        @if($organization->pivot->IsAdmin ?? true)
                                            <a href="{{ route('organization.dashboard', $organization) }}" class="btn btn-primary btn-sm">Manage</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        {{-- NEW: "Add another company" button at the bottom of the list --}}
                        <div class="widget-cta">
                            <a href="{{ route('organizations.create') }}" class="btn btn-secondary full-width">+ Create a New Company</a>
                        </div>
                    </section>
                @else
                    {{-- THIS SECTION SHOWS IF THE USER HAS NO COMPANIES --}}
                    <section class="sidebar-section cta-section">
                        <h2>Register Your Organization</h2>
                        <div class="cta-content">
                            <p>Create a company page to start discovering new partners and opportunities.</p>
                            <a href="{{ route('organizations.create') }}" class="btn btn-primary full-width">Create Company Page</a>
                        </div>
                    </section>
                @endif

            </aside>
        </div>
    </div>
@endsection