@extends('layout.app')

@push('styles')
    {{-- You can reuse the userdashboard.css or create a new one --}}
    <link href="{{ asset('css/proposallist.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <div class="header">
            <h1>All Partnership Proposals</h1>
            {{-- @auth
                @if ($userOrganizations->isNotEmpty())
                    <a href="{{ route('proposals.create', $userOrganizations->first()) }}" class="button button-primary">
                        Propose Partnership
                    </a>
                    {{-- A better approach might be: --}}
                    {{-- <form action="{{ route('proposals.create_selection') }}" method="GET">
                        <label for="org_select">Propose for:</label>
                        <select name="organization_id" id="org_select">
                            @foreach($userOrganizations as $org)
                                <option value="{{ $org->OrganizationID }}">{{ $org->OrganizationName }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="button button-primary">Create Proposal</button>
                    </form> --}}
                    {{-- You would then need a 'proposals.create_selection' route and method to handle this.
                @else
                    <p>You need to be part of an organization to create a proposal.</p>
                @endif
            @endauth --}}
        </div>

        @forelse ($proposals as $proposal)
            <div class="proposal-card">
                <h2>
                    <a href="{{ route('proposals.show', $proposal->ProposalID) }}" style="text-decoration: none; color: inherit;">
                        {{ $proposal->ProposalTitle }}
                    </a>
                </h2>
                
                <p><strong>Proposed to:</strong> {{ $proposal->organization->Name ?? 'N/A' }}</p>
                @if ($proposal->proposingOrganization)
                    <p><strong>Proposed by:</strong> {{ $proposal->proposingOrganization->Name }}</p>
                @endif
                <p><strong>Partnership Type:</strong> {{ $proposal->partnershipType->PartnershipTypeName ?? 'N/A' }}</p>
                <p>
                    <strong>Status:</strong>
                    <span class="status status-{{ Str::slug($proposal->ProposalStatus) }}">
                        {{ $proposal->ProposalStatus }}
                    </span>
                </p>
                <p>{{ Str::limit($proposal->Description, 150) }}</p>

                <div class="meta-info">
                    Submitted on: {{ $proposal->created_at->format('M d, Y') }}
                    @if ($proposal->StartDate && $proposal->EndDate)
                        <br>
                        Project Dates: {{ $proposal->StartDate->format('M d, Y') }} - {{ $proposal->EndDate->format('M d, Y') }}
                    @elseif ($proposal->StartDate)
                        <br>
                        Proposed Start Date: {{ $proposal->StartDate->format('M d, Y') }}
                    @endif
                </div>
            </div>
        @empty
            <p>No proposals found.</p>
        @endforelse

        <div class="pagination-links">
            {{ $proposals->links() }} {{-- Displays pagination links --}}
        </div>
    </div>
@endsection