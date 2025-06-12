@push('styles')
    {{-- We can reuse the same font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- We'll create a new CSS file for the profile page --}}
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endpush

@extends('layout.app')

@section('content')
<div class="profile-container">
    <header class="profile-header">
        <div class="profile-logo">
            {{ strtoupper(substr($organization->Name, 0, 1)) }}
        </div>
        <div class="profile-header-info">
            <h1>{{ $organization->Name }}</h1>
            <p class="subtitle">{{ $organization->OrganizationType }} @if($organization->locations->isNotEmpty()) · {{ $organization->locations->first()->LocationName }} @endif</p>
        </div>
        <div class="profile-actions">
            <a href="#" class="button button-secondary">Message</a>
            <a href="{{ route('proposals.create', $organization) }}" class="button button-primary">Propose Partnership</a>
        </div>
    </header>

    <div class="profile-body">
        <aside class="profile-sidebar">
            <div class="info-card">
                <h3>Seeking Partnerships In</h3>
                <div class="tag-group">
                    @foreach($organization->partnershipTypes as $pt)
                        <span class="tag tag-partnership">{{ $pt->PartnershipTypeName }}</span>
                    @endforeach
                </div>
            </div>
            <div class="info-card">
                <h3>Industries</h3>
                <div class="tag-group">
                    @foreach($organization->industries as $industry)
                        <span class="tag tag-industry">{{ $industry->IndustryType }}</span>
                    @endforeach
                </div>
            </div>
        </aside>

        <main class="profile-main">
            <div class="info-card">
                <h3>About {{ $organization->Name }}</h3>
                <p>{{ $organization->Description ?: 'No description provided.' }}</p>
            </div>
            <div class="info-card">
                <h3>Contact Information</h3>
                <ul class="contact-list">
                    <li>
                        <strong>Website</strong>
                        <span><a href="{{ $organization->Website }}" target="_blank">{{ $organization->Website }}</a></span>
                    </li>
                    @if($organization->locations->isNotEmpty())
                    <li>
                        <strong>Primary Address</strong>
                        <span>{{ $organization->locations->first()->LocationName }}</span>
                    </li>
                    @endif
                    <li>
                        <strong>Member Since</strong>
                        <span>{{ $organization->created_at->format('F j, Y') }}</span>
                    </li>
                </ul>
            </div>
        </main>
    </div>
</div>
@endsection