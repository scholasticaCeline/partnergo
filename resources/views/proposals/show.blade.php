@push('styles')
    <link href="{{ asset('css/showproposal.css') }}" rel="stylesheet">
@endpush

@extends('layout.app')

@section('content')
    <div class="proposal-page-container">
        {{-- Main Content Column --}}
        <div class="main-content">
            <div class="content-box">
                <h1>{{ $proposal->ProposalTitle }}</h1>
                <p style="color: var(--text-muted);">
                    <span class="status-badge
                    @if($proposal->ProposalStatus == 'submitted') status-submitted
                        @elseif($proposal->ProposalStatus == 'accepted') status-accepted
                        @elseif($proposal->ProposalStatus == 'rejected') status-rejected
                        @else status-default
                        @endif">
                        {{ ucfirst($proposal->ProposalStatus) }}
                    </span>
                    &nbsp;&nbsp;·&nbsp;&nbsp;
                    Posted: {{ $proposal->created_at->format('F d, Y') }}
                </p>

                <hr>

                <div>
                    <h5>Proposal Description</h5>
                    <p style="white-space: pre-wrap;">{{ $proposal->Description }}</p>
                </div>

                <hr>

                <div>
                    <h5>Attached Files</h5>
                    @if ($proposal->files->isNotEmpty())
                        <ul class="file-list">
                            @foreach ($proposal->files as $file)
                                <li class="file-list-item">
                                    <span>{{ $file->FileName }}</span>
                                    <a href="{{ route('proposals.files.download', $file) }}">Download</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p style="color: var(--text-muted);">No files were attached to this proposal.</p>
                    @endif
                </div>

            </div>
        </div>

        {{-- Sidebar Column for Details --}}
        <div class="sidebar">
            <div class="content-box">
                <div class="header">
                    <h5>Partnership Details</h5>
                </div>
                <ul class="details-list">
                    <li>
                        <strong>Partnership Type</strong>
                        <span>{{ $proposal->partnershipType->PartnershipTypeName }}</span>
                    </li>

                    @if ($proposal->StartDate)
                        <li>
                            <strong>Proposed Dates</strong>
                            <span>{{ \Carbon\Carbon::parse($proposal->StartDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($proposal->EndDate)->format('M d, Y') }}</span>
                        </li>
                    @endif

                    <li>
                        <strong>Proposal For</strong>
                        {{--
                        - Check if the relationship exists first.
                        - Use ->Name instead of ->OrganizationName
                        --}}
                        <span>{{ $proposal->organization->Name ?? 'N/A' }}</span>
                    </li>

                    @if ($proposal->proposingOrganization)
                        <li>
                            <strong>On Behalf Of</strong>
                            <span>{{ $proposal->proposingOrganization->Name }}</span>
                        </li>
                    @endif

                    <li>
                        <strong>Submitted By</strong>
                        <span>{{ $proposal->user->name }}</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
@endsection