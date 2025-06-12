@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/proposal.css') }}" rel="stylesheet">
@endpush

@extends('layout.app')

@section('content')
<div class="proposal-container">
    <header class="proposal-header" style="align-items: center; text-align:center; display:block;">
        <h1>Create Your Company Page</h1>
        <p class="subtitle" style="color: var(--text-light);">This information will be publicly visible to potential partners.</p>
    </header>

    <div class="form-card">
        <form action="{{ route('organizations.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Company Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')<span class="error-message">{{ $message }}</span>@enderror
            </div>
            
            {{-- UPDATED: This is now a dropdown --}}
            <div class="form-group">
                <label for="organization_type">Company Type</label>
                <select name="organization_type" id="organization_type" class="form-control" required>
                    <option value="" disabled selected>Select a type...</option>
                    @foreach($organizationTypes as $type)
                        <option value="{{ $type }}" {{ old('organization_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                @error('organization_type')<span class="error-message">{{ $message }}</span>@enderror
            </div>

            {{-- REMOVED: The Website URL field has been removed --}}

            <div class="form-group">
                <label for="location_id">Primary Location</label>
                <select name="location_id" id="location_id" class="form-control" required>
                    <option value="" disabled selected>Select a location...</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->LocationID }}" {{ old('location_id') == $location->LocationID ? 'selected' : '' }}>{{ $location->LocationName }}</option>
                    @endforeach
                </select>
                @error('location_id')<span class="error-message">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="industry_id">Industry</label>
                <select name="industry_id" id="industry_id" class="form-control" required>
                    <option value="" disabled selected>Select an industry...</option>
                    @foreach($industries as $industry)
                        <option value="{{ $industry->IndustryTypeID }}" {{ old('industry_id') == $industry->IndustryTypeID ? 'selected' : '' }}>{{ $industry->IndustryType }}</option>
                    @endforeach
                </select>
                @error('industry_id')<span class="error-message">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="partnership_type_ids">Partnership Types Offered (select one or more)</label>
                <select name="partnership_type_ids[]" id="partnership_type_ids" multiple>
                    @foreach($partnershipTypes as $pt)
                        <option value="{{ $pt->PartnershipTypeID }}">{{ $pt->PartnershipTypeName }}</option>
                    @endforeach
                </select>
                @error('partnership_type_ids')<span class="error-message">{{ $message }}</span>@enderror
            </div>


            <div class="form-group">
                <label for="description">Company Description</label>
                <textarea id="description" name="description" class="form-control" rows="6" required>{{ old('description') }}</textarea>
                @error('description')<span class="error-message">{{ $message }}</span>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="button button-primary">Create Company</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('#partnership_type_ids',{
                plugins: ['remove_button'],
                create: false,
                placeholder: 'Select partnership types...'
            });
        });
    </script>
@endpush