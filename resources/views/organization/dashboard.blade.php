@extends('layout.app')

@push('styles')
    <link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
@endpush

@push('scripts')
    {{-- We ONLY need the main FullCalendar library. No more plugins. --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                // Set the view back to the standard, working 'dayGridMonth'
                initialView: 'dayGridMonth',
                
                headerToolbar: {
                    left: 'today',
                    center: 'title',
                    right: 'prev,next'
                },
                
                height: 'auto',

                // This now points to the simple '$events' variable from your controller
                events: {!! json_encode($events) !!} 
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
                <h2>Partnership Timeline</h2>
                <div class="calendar-container">
                    <div id="calendar"></div>
                </div>
            </section>

            <section class="tasks-section">
                <h2>Active Partnership Deadlines</h2>
                <div class="tasks-grid">
                    @forelse ($upcomingPartnerships as $partnership)
                        <div class="task-item">
                            @php
                                // --- THIS LOGIC IS NOW SAFE ---
                                // Determine the partner organization safely
                                $partnerOrg = null;
                                if ($organization->OrganizationID === $partnership->OrganizationSenderID) {
                                    $partnerOrg = $partnership->targetOrganization;
                                } else {
                                    $partnerOrg = $partnership->senderOrganization;
                                }
                            @endphp
                            <div class="task-header">
                                <h4 class="task-title">{{ $partnership->proposal->ProposalTitle ?? 'Partnership' }}</h4>
                                {{-- Use a ternary operator to safely display the name or a fallback --}}
                                <span class="task-partner">with {{ $partnerOrg ? $partnerOrg->Name : 'An Individual' }}</span>
                            </div>
                            <div class="task-body">
                                <p class="task-countdown">
                                    Ends in: <strong>{{ now()->diffInDays($partnership->EndDate, false) }} days</strong>
                                    <span class="task-date">({{ \Carbon\Carbon::parse($partnership->EndDate)->format('M d, Y') }})</span>
                                </p>
                            </div>
                            <div class="task-footer">
                                <a href="{{ route('proposals.show', $partnership->ProposalID) }}" class="task-link">View Details</a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state-full">
                            <p>This organization has no active partnerships.</p>
                        </div>
                    @endforelse
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
                                {{-- Added a safety check here too --}}
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
            </section>

            @can('update', $organization)
                <section class="sidebar-section cta-section">
                    <h2>Admin Settings</h2>
                    <div class="cta-content">
                        <p>Manage your organization's profile and member roles.</p>
                        {{-- --- THIS ROUTE NAME IS NOW CORRECTED --- --}}
                        <a href="{{ route('organization.manage', $organization) }}" class="btn btn-secondary full-width">Manage Organization</a>
                    </div>
                </section>
            @endcan
        </aside>
    </div>
</div>
@endsection