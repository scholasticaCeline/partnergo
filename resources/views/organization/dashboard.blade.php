@extends('layout.app')

@push('styles')
    {{-- You can reuse the userdashboard.css or create a new one --}}
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
                // The $events variable is now populated by your new controller method
                events: {!! json_encode($events) !!},
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); // don't let the browser navigate
                    if (info.event.url) {
                        window.open(info.event.url, "_blank");
                    }
                }
            });
            calendar.render();
        });
    </script>
@endpush

@section('content')
<div class="dashboard">
    <header class="header">
        <div class="header-content">
            <div class="welcome">
                <h1>{{ $organization->Name }} Dashboard</h1>
                <p>An overview of your organization's partnerships and proposals.</p>
            </div>
            <div class="header-pattern"></div>
        </div>
    </header>

    <div class="main-container">
        <main class="main-content">
            <section class="calendar-section">
                <h2>Partnership Calendar</h2>
                <div class="calendar-container">
                    <div id="calendar"></div>
                </div>
            </section>
        </main>

        <aside class="sidebar">
            <section class="sidebar-section proposals-section">
                <h2>Proposals Sent To Us</h2>
                <div class="proposals-list">
                    @forelse ($proposalsToUs as $proposal)
                        <a href="{{ route('proposals.show', $proposal) }}" class="proposal-item-link">
                            <div class="proposal-item-content">
                                <h3>{{ Str::limit($proposal->ProposalTitle, 35) }}</h3>
                                {{-- Show who it's from --}}
                                <p>From: {{ $proposal->proposingOrganization->Name ?? $proposal->user->name }}</p>
                            </div>
                            <div class="status-badge status-{{ strtolower($proposal->ProposalStatus) }}">
                                {{ ucfirst($proposal->ProposalStatus) }}
                            </div>
                        </a>
                    @empty
                        <div class="empty-state-small">
                            <p>You have no incoming proposals right now.</p>
                        </div>
                    @endforelse
                </div>
                <a href="#" class="see-all">See all incoming proposals <span class="arrow">›</span></a>
            </section>

            {{-- This @can block uses the OrganizationPolicy we created earlier --}}
            {{-- Only users with the 'update' permission (admins) will see these buttons --}}
            @can('update', $organization)
                <section class="sidebar-section cta-section">
                    <h2>Admin Settings</h2>
                    <div class="cta-content">
                        <p>Manage your organization's profile, tags, and member roles.</p>
                        {{-- Button to the "manage" page for editing details and tags --}}
                        <a href="{{ route('organization.manage', $organization) }}" class="btn btn-secondary full-width">Edit Organization Details</a>
                        
                        {{-- Button to the same "manage" page, but linking to the members tab --}}
                        <a href="{{ route('organization.manage', $organization) }}#members" class="btn btn-secondary full-width" style="margin-top: 10px;">Manage Members & Admins</a>
                    </div>
                </section>
            @endcan
        </aside>
    </div>
</div>
@endsection