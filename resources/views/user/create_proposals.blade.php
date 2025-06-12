@push('styles')
    <link href="{{ asset('css/proposal.css') }}" rel="stylesheet">
@endpush

@extends('layout.app')

@section('content')
<div class="proposal-container">
    <header class="proposal-header" style="align-items: center; text-align:center; display:block;">
        <h1>Partnership Proposal to {{ $organization->Name }}</h1>
        <p class="subtitle" style="color: var(--text-light);">{{ $organization->OrganizationType }} @if($organization->locations->isNotEmpty()) · {{ $organization->locations->first()->LocationName }} @endif</p>
    </header>

    <div class="form-card">
        <form action="{{ route('proposals.store', $organization) }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Proposing Organization (name="proposing_organization_id") --}}
            @if($userOrganizations->isNotEmpty())
                <div class="form-group">
                    <label for="proposing_organization_id">Proposing on Behalf Of</label>
                    <select name="proposing_organization_id" id="proposing_organization_id" class="form-control">
                        <option value="">Proposing as an Individual</option>
                        @foreach($userOrganizations as $org)
                            <option value="{{ $org->OrganizationID }}" {{ old('proposing_organization_id') == $org->OrganizationID ? 'selected' : '' }}>{{ $org->Name }}</option>
                        @endforeach
                    </select>
                    @error('proposing_organization_id')<span class="error-message">{{ $message }}</span>@enderror
                </div>
            @endif

            {{-- Proposal Title (name="proposal_title") --}}
            <div class="form-group">
                <label for="proposal_title">Proposal Title</label>
                <input type="text" id="proposal_title" name="proposal_title" class="form-control" value="{{ old('proposal_title') }}" required>
                @error('proposal_title') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            {{-- Partnership Type (name="partnership_type_id") --}}
            <div class="form-group">
                <label for="partnership_type_id">Type of Partnership</label>
                <select name="partnership_type_id" id="partnership_type_id" class="form-control" required>
                    <option value="" disabled selected>Select a partnership type...</option>
                    @foreach($partnershipTypes as $type)
                        <option value="{{ $type->PartnershipTypeID }}" data-requires-dates="{{ in_array($type->PartnershipTypeName, $dateRequiredTypes) ? 'true' : 'false' }}" {{ old('partnership_type_id') == $type->PartnershipTypeID ? 'selected' : '' }}>
                            {{ $type->PartnershipTypeName }}
                        </option>
                    @endforeach
                </select>
                @error('partnership_type_id') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            {{-- Proposal Details (name="description") --}}
            <div class="form-group">
                <label for="description">Proposal Details</label>
                <textarea id="description" name="description" class="form-control" rows="8" required>{{ old('description') }}</textarea>
                @error('description') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            {{-- Date Fields Wrapper --}}
            <div id="date-fields-wrapper" style="display: none;">
                <div class="form-group">
                    <label for="start_date">Proposed Start Date</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                    @error('start_date') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="end_date">Proposed End Date</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                    @error('end_date') <span class="error-message">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- File Upload --}}
            <div class="form-group">
                <label for="proposal_files">Attach Files</label>
                <p class="form-hint">You must upload at least one document.</p>
                <input type="file" id="proposal_files" name="proposal_files[]" class="form-control-file" multiple required>
                @error('proposal_files') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            {{-- Form Actions --}}
            <div class="form-actions">
                <a href="{{ route('organizations.show', $organization) }}" class="button button-secondary">Cancel</a>
                <button type="submit" class="button button-primary">Send Proposal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    {{-- This JavaScript logic does not need to change --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const partnershipTypeDropdown = document.getElementById('partnership_type_id');
        const dateFieldsWrapper = document.getElementById('date-fields-wrapper');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        function toggleDateFields() {
            if (!partnershipTypeDropdown || !dateFieldsWrapper) return;
            const selectedOption = partnershipTypeDropdown.options[partnershipTypeDropdown.selectedIndex];
            if (!selectedOption || selectedOption.value === "") {
                dateFieldsWrapper.style.display = 'none';
                return;
            };
            const requiresDates = selectedOption.getAttribute('data-requires-dates') === 'true';
            dateFieldsWrapper.style.display = requiresDates ? 'block' : 'none';
            startDateInput.required = requiresDates;
            endDateInput.required = requiresDates;
        }

        partnershipTypeDropdown.addEventListener('change', toggleDateFields);
        toggleDateFields();
    });
    </script>
@endpush