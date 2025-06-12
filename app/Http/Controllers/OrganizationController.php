<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Proposal;
use App\Models\Location;
use App\Models\IndustryType;
use App\Models\PartnershipType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class OrganizationController extends Controller
{
    /**
     * Display the specified organization's public profile.
     */
    public function show(Organization $organizametion)
    {
        $organization->load(['industries', 'partnershipTypes', 'locations', 'users']);
        return view('organization.show', ['organization' => $organization]);
    }

    /**
     * Show the form for creating a new organization.
     */
    public function create()
    {
        $locations = Location::orderBy('LocationName')->get();
        $industries = IndustryType::orderBy('IndustryType')->get();
        $organizationTypes = Organization::select('OrganizationType')->distinct()->pluck('OrganizationType');
        $partnershipTypes = PartnershipType::orderBy('PartnershipTypeName')->get();

        return view('organization.create', [
            'locations' => $locations,
            'industries' => $industries,
            'organizationTypes' => $organizationTypes,
            'partnershipTypes' => $partnershipTypes,
        ]);
    }

    /**
     * Store a newly created organization in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'organization_type' => 'required|string|max:100',
            'description' => 'required|string|max:5000',
            'location_id' => 'required|exists:locations,LocationID',
            'industry_id' => 'required|exists:industry_types,IndustryTypeID',
            'partnership_type_ids' => 'required|array|min:1',
            'partnership_type_ids.*' => 'exists:partnership_types,PartnershipTypeID',
        ]);

        $organization = DB::transaction(function () use ($validatedData) {
            $user = Auth::user();

            $organization = Organization::create([
                'OrganizationID' => Str::uuid(),
                'Name' => $validatedData['name'],
                'Description' => $validatedData['description'],
                'OrganizationType' => $validatedData['organization_type'],
                'OpenForPartnership' => 1,
            ]);

            $organization->locations()->attach($validatedData['location_id']);
            $organization->industries()->attach($validatedData['industry_id']);
            $organization->partnershipTypes()->attach($validatedData['partnership_type_ids']);
            $organization->users()->attach($user->UserID, ['IsAdmin' => 1]);

            return $organization;
        });

        return redirect()->route('organizations.show', $organization)
                        ->with('success', 'Your organization page has been created successfully!');
    }

    /**
     * Display the dashboard for a specific organization (Admins only).
     */
    public function dashboard(Organization $organization)
    {
        $this->authorize('viewDashboard', $organization);

        $partnerships = Proposal::where('OrganizationID', $organization->OrganizationID)
                                ->where('ProposalStatus', 'accepted')
                                ->whereNotNull('StartDate')
                                ->get();

        $events = $partnerships->map(function ($partnership) {
            return [
                'title' => $partnership->ProposalTitle,
                'start' => $partnership->StartDate,
                'end'   => $partnership->EndDate,
                'url'   => route('proposals.show', $partnership->ProposalID),
            ];
        });

        $proposalsToUs = Proposal::where('OrganizationID', $organization->OrganizationID)
                                ->with('user', 'proposingOrganization')
                                ->latest('created_at')
                                ->take(5)
                                ->get();

        return view('organization.dashboard', [
            'organization' => $organization,
            'events' => $events,
            'proposalsToUs' => $proposalsToUs,
        ]);
    }

    /**
     * Show the settings/management page for an organization (Admins only).
     */
    public function manage(Organization $organization)
    {
        $this->authorize('update', $organization);

        $organization->load('users', 'partnershipTypes');
        $allPartnershipTypes = PartnershipType::orderBy('PartnershipTypeName')->get();

        return view('organization.manage', [
            'organization' => $organization,
            'allPartnershipTypes' => $allPartnershipTypes,
        ]);
    }

    /**
     * Update the organization's basic details.
     */
    public function updateDetails(Request $request, Organization $organization)
    {
        $this->authorize('update', $organization);

        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Description' => 'required|string|max:5000',
        ]);

        $organization->update($validated);

        return redirect()->route('organization.manage', $organization)->with('success', 'Organization details updated successfully.');
    }

    /**
     * Update the organization's partnership type tags.
     */
    public function updateTags(Request $request, Organization $organization)
    {
        $this->authorize('update', $organization);

        $validated = $request->validate([
            'partnership_types' => 'nullable|array',
            'partnership_types.*' => 'exists:partnership_types,PartnershipTypeID',
        ]);

        $organization->partnershipTypes()->sync($validated['partnership_types'] ?? []);

        return redirect()->route('organization.manage', $organization)->with('success', 'Partnership tags updated successfully.');
    }

    /**
     * Update the roles of organization members.
     */
    public function updateMembers(Request $request, Organization $organization)
    {
        $this->authorize('update', $organization);

        $validated = $request->validate([
            'admins' => 'nullable|array',
            'admins.*' => 'in:1',
        ]);

        $adminUserIDs = array_keys($validated['admins'] ?? []);

        foreach ($organization->users as $user) {
            $isAdmin = in_array($user->UserID, $adminUserIDs);
            $organization->users()->updateExistingPivot($user->UserID, ['IsAdmin' => $isAdmin]);
        }

        return redirect()->route('', $organization)->with('success', 'Member roles updated successfully.');
    }
}