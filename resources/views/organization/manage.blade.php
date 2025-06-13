@push('styles')
    <link href="{{ asset('css/manage.css') }}" rel="stylesheet">
@endpush

@extends('layout.app')

@section('content')
    <div class="custom-container page-padding">
        <h1 class="page-title mb-4">Manage: {{ $organization->Name }}</h1>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="custom-alert custom-alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tab Navigation --}}
        <ul class="custom-nav-tabs" id="manageTabs" role="tablist">
            <li class="custom-nav-item" role="presentation">
                <button class="custom-nav-link active" id="details-tab" data-tab-target="#details" type="button" role="tab">General Details</button>
            </li>
            <li class="custom-nav-item" role="presentation">
                <button class="custom-nav-link" id="tags-tab" data-tab-target="#tags" type="button" role="tab">Partnership Tags</button>
            </li>
            <li class="custom-nav-item" role="presentation">
                <button class="custom-nav-link" id="members-tab" data-tab-target="#members" type="button" role="tab">Member Management</button>
            </li>
        </ul>

        {{-- Tab Content --}}
        <div class="custom-tab-content content-border p-4" id="manageTabsContent">
            {{-- General Details Tab --}}
            <div class="custom-tab-pane show active" id="details" role="tabpanel">
                <h4 class="section-title mb-3">Edit Details</h4>
                <form action="{{ route('organization.update.details', $organization) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-group mb-3">
                        <label for="Name" class="form-label">Organization Name</label>
                        <input type="text" class="custom-form-control" id="Name" name="Name" value="{{ old('Name', $organization->Name) }}" required>
                        @error('Name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="Description" class="form-label">Description</label>
                        <textarea class="custom-form-control" id="Description" name="Description" rows="5" required>{{ old('Description', $organization->Description) }}</textarea>
                        @error('Description')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="custom-btn custom-btn-primary">Save Details</button>
                </form>
            </div>

            {{-- Partnership Tags Tab --}}
            <div class="custom-tab-pane fade" id="tags" role="tabpanel">
                <h4 class="section-title mb-3">Edit Partnership Tags</h4>
                <p class="text-muted small-text">Select the types of partnerships your organization is interested in.</p>
                <form action="{{ route('organization.update.tags', $organization) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-group mb-3">
                        @foreach ($allPartnershipTypes as $type)
                            <div class="custom-form-check">
                                <input class="custom-form-check-input" type="checkbox" name="partnership_types[]" value="{{ $type->PartnershipTypeID }}" id="type-{{ $type->PartnershipTypeID }}"
                                    @if($organization->partnershipTypes->contains($type->PartnershipTypeID)) checked @endif>
                                <label class="custom-form-check-label" for="type-{{ $type->PartnershipTypeID }}">
                                    {{ $type->PartnershipTypeName }}
                                </label>
                            </div>
                        @endforeach
                        @error('partnership_types')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="custom-btn custom-btn-primary">Save Tags</button>
                </form>
            </div>

            {{-- Member Management Tab --}}
            <div class="custom-tab-pane fade" id="members" role="tabpanel">
                <h4 class="section-title mb-3">Manage Members & Roles</h4>
                <form action="{{ route('organization.update.members', $organization) }}" method="POST" id="memberManagementForm">
                    @csrf
                    @method('PATCH')

                    {{-- Server-side validation error for 'admins' --}}
                    @error('admins')
                        <div class="custom-alert custom-alert-danger mb-3">
                            {{ $message }}
                        </div>
                    @enderror

                    {{-- Client-side error message will appear here --}}
                    <div id="lastAdminError" class="custom-alert custom-alert-danger mb-3" style="display: none;">
                        An organization must have at least one administrator. You cannot remove the last admin's status.
                    </div>

                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th class="text-center">Admin Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($organization->users as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td class="text-center">
                                        <div class="custom-form-check-switch d-inline-block">
                                            <input class="custom-form-check-input-switch" type="checkbox" name="admins[{{ $member->UserID }}]" value="1" id="admin-{{ $member->UserID }}"
                                                data-user-id="{{ $member->UserID }}"
                                                {{-- Client-side disabled if current user is this member AND they are the only admin --}}
                                                @if($member->pivot->IsAdmin && $organization->users->where('pivot.IsAdmin', true)->count() == 1 && auth()->id() == $member->UserID) disabled @endif
                                                @if($member->pivot->IsAdmin) checked @endif>
                                            <label class="custom-form-check-label-switch" for="admin-{{ $member->UserID }}"></label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- The specific warning message can still remain for UX guidance --}}
                    @if(auth()->user()->organizations->count() > 1 && $organization->users->contains(auth()->id()) && $organization->users->where('pivot.IsAdmin', true)->count() == 1)
                        <div class="custom-alert custom-alert-warning small-alert mb-3">
                            You are the only admin for this organization. You cannot remove your own admin status.
                        </div>
                    @endif

                    <button type="submit" class="custom-btn custom-btn-primary">Update Roles</button>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPTS INCLUDED DIRECTLY --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Tab Navigation Logic ---
            const tabButtons = document.querySelectorAll('.custom-nav-link');
            const tabPanes = document.querySelectorAll('.custom-tab-pane');

            function activateTab(tabId) {
                tabButtons.forEach(button => {
                    button.classList.remove('active');
                });
                tabPanes.forEach(pane => {
                    pane.classList.remove('show', 'active');
                });

                const activeButton = document.querySelector(`[data-tab-target="${tabId}"]`);
                const activePane = document.querySelector(tabId);

                if (activeButton) {
                    activeButton.classList.add('active');
                }
                if (activePane) {
                    activePane.classList.add('show', 'active');
                }

                history.pushState(null, '', `#${tabId.substring(1)}`);
            }

            tabButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const targetTabId = this.dataset.tabTarget;
                    activateTab(targetTabId);
                });
            });

            const initialHash = window.location.hash;
            if (initialHash) {
                const targetButton = document.querySelector(`[data-tab-target="${initialHash}"]`);
                if (targetButton) {
                    activateTab(initialHash);
                } else {
                    activateTab('#details');
                }
            } else {
                activateTab('#details');
            }

            // --- Client-side Admin Role Management Logic ---
            const adminSwitches = document.querySelectorAll('.custom-form-check-input-switch');
            const lastAdminErrorDiv = document.getElementById('lastAdminError');
            const memberManagementForm = document.getElementById('memberManagementForm');

            // Pass initial admin count from PHP to JavaScript
            const initialAdminCount = {{ $organization->users->where('pivot.IsAdmin', true)->count() }};
            
            // Get the current authenticated user's ID, properly quoted for JavaScript
            const currentAuthUserId = {{ Js::from(auth()->id()) }}; // <-- FIX HERE

            // Function to count currently checked admin switches (after a change)
            function countCheckedAdmins() {
                let count = 0;
                adminSwitches.forEach(sw => {
                    if (sw.checked) {
                        count++;
                    }
                });
                return count;
            }

            // Add event listener to each admin switch
            adminSwitches.forEach(sw => {
                sw.addEventListener('change', function(event) {
                    // Check if the current user is the one whose switch is being toggled
                    const isCurrentUser = (event.target.dataset.userId == currentAuthUserId);
                    
                    // Determine if the action would result in 0 admins
                    const currentAdminsAfterToggle = countCheckedAdmins(); // Already reflects the new state

                    if (currentAdminsAfterToggle === 0) {
                        // This means the user attempted to uncheck the last admin switch.
                        // Revert the switch state and show error.
                        event.target.checked = !event.target.checked; // Revert the switch state
                        lastAdminErrorDiv.style.display = 'block'; // Show the specific error message
                    } else {
                        lastAdminErrorDiv.style.display = 'none'; // Hide error if valid
                    }

                    // Re-evaluate the disabled state of the current user's switch
                    // This is important if you enable other admins then try to remove yourself
                    adminSwitches.forEach(s => {
                        const sIsCurrentUser = (s.dataset.userId == currentAuthUserId); // <-- FIX HERE
                        if (sIsCurrentUser) {
                            // Disable if current user is the last admin AND their switch is currently checked
                            s.disabled = (countCheckedAdmins() === 1 && s.checked);
                        }
                    });

                });
            });

            // Prevent form submission if client-side validation is active
            memberManagementForm.addEventListener('submit', function(event) {
                if (countCheckedAdmins() === 0) {
                    event.preventDefault(); // Prevent form submission
                    lastAdminErrorDiv.style.display = 'block'; // Ensure error is visible
                    lastAdminErrorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' }); // Scroll to error
                }
            });

            // Initial setup for the disabled state of the current user's own switch
            const currentUserSwitch = document.querySelector(`.custom-form-check-input-switch[data-user-id="${currentAuthUserId}"]`); // <-- FIX HERE
            if (currentUserSwitch) {
                // Disable if current user is the only admin AND their switch is currently checked
                currentUserSwitch.disabled = (initialAdminCount === 1 && currentUserSwitch.checked);
            }
        });
    </script>
@endsection