@push('styles')
    {{-- We'll add a new font from Google to match the design --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- This points to the new CSS file content provided below --}}
    <link href="{{ asset('css/find.css') }}" rel="stylesheet">
@endpush

@extends('layout.app')

@section('content')
<div class="header-bg">
    <header class="page-header">
        <h1>Search for Partner</h1>
        <p class="subtitle">Find the partner that shares your vision and will help you build it.</p>
        
        <form action="{{ route('partners') }}" method="GET" class="search-form">
            <div class="search-bar">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                </svg>
                <input type="text" class="search-input" name="search" placeholder="Job title or keyword" value="{{ $filters['search'] ?? '' }}">
                
                {{-- The user wanted to keep the filter button functionality --}}
                <button type="button" class="filter-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                    </svg>
                    <span>More filters</span>
                </button>
                
                <button type="submit" class="search-submit-button">Search</button>
            </div>

            <div class="filter-panel">
                <h2>Filter Options</h2>
                <div class="filter-options">
                    <select name="industry" class="filter-select">
                        <option value="">All Industries</option>
                        @foreach($industries as $industry)
                            <option value="{{ $industry->IndustryTypeID }}" {{ ($filters['industry'] ?? '') == $industry->IndustryTypeID ? 'selected' : '' }}>
                                {{ $industry->IndustryType }}
                            </option>
                            @endforeach
                        </select>

                        <select name="organization_type" class="filter-select">
                        <option value="">All Organization Types</option>
                        @foreach($organizationTypes as $orgType)
                            <option value="{{ $orgType }}" {{ ($filters['organization_type'] ?? '') == $orgType ? 'selected' : '' }}>
                                {{ $orgType }}
                            </option>
                        @endforeach
                    </select>

                    <select name="partnership_type" class="filter-select">
                        <option value="">All Partnership Types</option>
                        @foreach($partnershipTypes as $pt)
                            <option value="{{ $pt->PartnershipTypeID }}" {{ ($filters['partnership_type'] ?? '') == $pt->PartnershipTypeID ? 'selected' : '' }}>
                                {{ $pt->PartnershipTypeName }}
                            </option>
                        @endforeach
                    </select>
                    
                    <select name="location" class="filter-select">
                        <option value="">All Locations</option>
                            @foreach($locations as $location)
                            <option value="{{ $location->LocationID }}" {{ ($filters['location'] ?? '') == $location->LocationID ? 'selected' : '' }}>
                                {{ $location->LocationName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="checkbox-container">
                    <input type="checkbox" id="open_for_partnership" name="open_for_partnership" value="1" {{ isset($filters['open_for_partnership']) ? 'checked' : '' }}>
                    <label for="open_for_partnership">Open for Partnership</label>
                </div>
                <div class="filter-actions">
                    <a href="{{ route('partners') }}" class="clear-btn">Clear All Filters</a>
                    <button type="submit" class="apply-btn">Apply Filters</button>
                </div>
            </div>
        </form>
    </header>
</div>

<div class="container">
    <main>
        <div class="results-header">
            <span class="results-count">{{ $organizations->total() }} companies available</span>
        </div>

        <div class="companies-grid">
            @forelse($organizations as $org)
            <a href="{{ route('organization.show', $org) }}" class="company-card-link">    
                <div class="company-card">
                    <div class="company-logo">
                        {{ strtoupper(substr($org->Name, 0, 1)) }}
                    </div>

                    <div class="company-info">
                        <h3 class="company-name">{{ $org->Name }}</h3>
                        <div class="company-details">
                            <span>{{ $org->OrganizationType }}</span>
                        </div>
                        <div class="company-tags">
                            @foreach($org->industries->take(2) as $industry)
                                <span class="tag tag-industry">{{ $industry->IndustryType }}</span>
                            @endforeach
                            @foreach($org->partnershipTypes->take(2) as $pt)
                                <span class="tag tag-partnership">{{ $pt->PartnershipTypeName }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="company-location">
                        @if($org->locations->isNotEmpty())
                        <span class="tag location-tag">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                            </svg>
                            {{ $org->locations->first()->LocationName }}
                        </span>
                        @endif
                    </div>
                </div>
            </a>
            
            @empty
                <div class="no-results">
                    <h3>No organizations found.</h3>
                    <p>Try adjusting your search or filter criteria.</p>
                </div>
            @endforelse
        </div>
        
        <div class="pagination-container">
            {{ $organizations->links() }}
        </div>
    </main>
</div>

{{-- This new script toggles the visibility of the filter panel --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButton = document.querySelector(".filter-button");
        const filterPanel = document.querySelector(".filter-panel");

        if (filterButton && filterPanel) {
            filterButton.addEventListener("click", function() {
                filterPanel.classList.toggle("is-visible");
            });
        }
    });
</script>
@endsection