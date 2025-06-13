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
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); 
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
                <div class="calendar-container">
                    <div id="calendar"></div>
                </div>
            </section>
        </main>

        <aside class="sidebar">
            <section class="sidebar-section proposals-section">
                <h2>Proposals Sent To Us</h2>
                <div class="proposals-list">
                    {{-- This loop now correctly uses the $proposalsToUs variable --}}
                    @forelse ($proposalsToUs as $proposal)
                        <a href="{{ route('proposals.show', $proposal) }}" class="proposal-item-link">
                            <div class="proposal-item-content">
                                <h3>{{ Str::limit($proposal->ProposalTitle, 35) }}</h3>
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

            @can('update', $organization)
                <section class="sidebar-section cta-section">
                    <h2>Admin Settings</h2>
                    <div class="cta-content">
                        <p>Manage your organization's profile, tags, and member roles.</p>
                        <a href="{{ route('organization.manage', $organization) }}" class="btn btn-secondary full-width">Edit Organization Details</a>
                    </div>
                </section>
            @endcan
        </aside>
    </div>
</div>
@endsection