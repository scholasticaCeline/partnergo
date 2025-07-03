<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Proposal;
use App\Models\Partnership; 
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
     * Display a listing of all organization.
     */
    public function index()
    {
        $organization = Organization::with(['locations', 'industries', 'partnershipTypes'])
                                    ->latest('created_at')
                                    ->paginate(10);

        return view('organization.index', compact('organization'));
    }

    /**
     * Display the specified organization's public profile.
     */
    public function show(Organization $organization)
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

        return redirect()->route('organization.show', $organization)
                         ->with('success', 'Your organization page has been created successfully!');
    }

    /**
     * Display the dashboard for a specific organization (Admins only).
     */
    public function dashboard(Organization $organization)
    {
        // 1. Authorize: Ensure the user can view this dashboard
        $this->authorize('viewDashboard', $organization);

        // 2. Fetch all partnerships (pending and active) related to this organization
        $allPartnerships = Partnership::with('proposal')
            ->where(function ($query) use ($organization) {
                $query->where('OrganizationSenderID', $organization->OrganizationID)
                    ->orWhere('OrganizationTargetID', $organization->OrganizationID);
            })
            ->get();
            
        // 3. Prepare data for "Upcoming Tasks" from ACTIVE partnerships
        $upcomingPartnerships = $allPartnerships->where('Status', 'Active')
                                                ->where('EndDate', '>=', now())
                                                ->sortBy('EndDate');

        // 4. PREPARE DATA FOR THE SIMPLE CALENDAR (No more Gantt chart data)
        // We only need one simple $events array now.
        $events = $allPartnerships->map(function ($p) {
            // Assign colors based on status
            $color = match (strtolower($p->Status)) {
                'active' => '#16a34a', // Green
                'pending' => '#f8971d',// Orange
                default => '#6c757d',  // Gray
            };

            return [
                'title' => $p->proposal->ProposalTitle ?? 'Partnership',
                'start' => $p->StartDate,
                'end'   => $p->EndDate, // FullCalendar automatically shows duration with start/end
                'url'   => route('proposals.show', ['proposal' => $p->ProposalID]),
                'color' => $color,
            ];
        });


        // 5. Fetch sidebar data
        $proposalsToUs = Proposal::where('OrganizationID', $organization->OrganizationID)
                                ->with('user', 'proposingOrganization')
                                ->latest()->take(5)->get();


        // 6. Return the view with the correct, simple $events variable
        return view('organization.dashboard', [
            'organization' => $organization,
            'upcomingPartnerships' => $upcomingPartnerships,
            'events' => $events, // <-- We are now passing the simple $events array
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
     * Update the organization's basic details (Name, Description).
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
     * Update the roles of organization members (e.g., IsAdmin status).
     */
    public function updateMembers(Request $request, Organization $organization)
    {
        $this->authorize('update', $organization);

        $validated = $request->validate([
            'admins' => 'nullable|array', // 'admins' array will contain user IDs for whom the checkbox was checked
            'admins.*' => 'in:1', // Ensures values are '1' if present
        ]);

        $adminUserIDsFromRequest = array_keys($validated['admins'] ?? []);

        $proposedAdminCount = 0;
        foreach ($organization->users as $member) {
            if (in_array($member->UserID, $adminUserIDsFromRequest)) {
                $proposedAdminCount++;
            }
        }

        // IMPORTANT: Prevent the update if it would leave the organization with 0 administrators.
        if ($proposedAdminCount === 0) {
            // Return with an error message, which will be caught by @error directive in Blade
            return redirect()->back()->withErrors(['admins' => 'An organization must have at least one administrator. Please ensure at least one member retains admin status.']);
        }

        // Proceed with updating roles if there's at least one admin after the proposed changes
        foreach ($organization->users as $member) {
            $isAdmin = in_array($member->UserID, $adminUserIDsFromRequest);
            $organization->users()->updateExistingPivot($member->UserID, ['IsAdmin' => $isAdmin]);
        }

        return redirect()->route('organization.manage', $organization)->with('success', 'Member roles updated successfully.');
    }
}