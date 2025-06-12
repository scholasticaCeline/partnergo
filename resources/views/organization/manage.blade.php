@extends('layout.app')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Manage: {{ $organization->Name }}</h1>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tab Navigation --}}
        <ul class="nav nav-tabs" id="manageTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">General Details</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tags-tab" data-bs-toggle="tab" data-bs-target="#tags" type="button" role="tab">Partnership Tags</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="members-tab" data-bs-toggle="tab" data-bs-target="#members" type="button" role="tab">Member Management</button>
            </li>
        </ul>

        {{-- Tab Content --}}
        <div class="tab-content border border-top-0 p-4" id="manageTabsContent">
            {{-- General Details Tab --}}
            <div class="tab-pane fade show active" id="details" role="tabpanel">
                <h4 class="mb-3">Edit Details</h4>
                <form action="{{ route('organizations.update.details', $organization) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="Name" class="form-label">Organization Name</label>
                        <input type="text" class="form-control" id="Name" name="Name" value="{{ old('Name', $organization->Name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="Description" class="form-label">Description</label>
                        <textarea class="form-control" id="Description" name="Description" rows="5" required>{{ old('Description', $organization->Description) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Details</button>
                </form>
            </div>

            {{-- Partnership Tags Tab --}}
            <div class="tab-pane fade" id="tags" role="tabpanel">
                <h4 class="mb-3">Edit Partnership Tags</h4>
                <p class="text-muted">Select the types of partnerships your organization is interested in.</p>
                <form action="{{ route('organizations.update.tags', $organization) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        @foreach ($allPartnershipTypes as $type)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="partnership_types[]" value="{{ $type->PartnershipTypeID }}" id="type-{{ $type->PartnershipTypeID }}"
                                    @if($organization->partnershipTypes->contains($type->PartnershipTypeID)) checked @endif>
                                <label class="form-check-label" for="type-{{ $type->PartnershipTypeID }}">
                                    {{ $type->PartnershipTypeName }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary">Save Tags</button>
                </form>
            </div>

            {{-- Member Management Tab --}}
            <div class="tab-pane fade" id="members" role="tabpanel">
                <h4 class="mb-3">Manage Members & Roles</h4>
                <form action="{{ route('organizations.update.members', $organization) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <table class="table">
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
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" role="switch" name="admins[{{ $member->UserID }}]" value="1" id="admin-{{ $member->UserID }}"
                                                @if($member->pivot->IsAdmin) checked @endif
                                                @if(auth()->id() == $member->UserID) disabled @endif>
                                                {{-- You can't remove your own admin status --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if(auth()->user()->organizations->count() > 1 && $organization->users->contains(auth()->id()) && $organization->users->where('pivot.IsAdmin', true)->count() == 1)
                        <div class="alert alert-warning small">
                            You are the only admin. You cannot remove your admin status.
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary">Update Roles</button>
                </form>
            </div>
        </div>
    </div>
@endsection