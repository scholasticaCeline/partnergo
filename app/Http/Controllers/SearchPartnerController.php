<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\IndustryType;
use App\Models\PartnershipType;
use App\Models\Location;

class SearchPartnerController extends Controller
{
    public function index(Request $request)
    {
        $query = Organization::query();

        // Handle search input
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('Name', 'like', "%{$searchTerm}%");
        }

        // Handle 'industry' filter using the correct table name
        if ($request->filled('industry')) {
            $query->whereHas('industries', function ($q) use ($request) {
                $q->where('industry_types.IndustryTypeID', $request->input('industry'));
            });
        }

        // Handle 'organization_type' filter
        if ($request->filled('organization_type')) {
            $query->where('OrganizationType', $request->input('organization_type'));
        }

        // Handle 'partnership_type' filter using the correct table name
        if ($request->filled('partnership_type')) {
            $query->whereHas('partnershipTypes', function ($q) use ($request) {
                $q->where('partnership_types.PartnershipTypeID', $request->input('partnership_type'));
            });
        }
        
        // Handle 'location' filter using the correct table name
        if ($request->filled('location')) {
            $query->whereHas('locations', function ($q) use ($request) {
                $q->where('locations.LocationID', $request->input('location'));
            });
        }

        // Handle 'open_for_partnership' checkbox
        if ($request->has('open_for_partnership')) {
            $query->where('OpenForPartnership', 1);
        }

        // Execute the final query
        $organizations = $query->with(['industries', 'partnershipTypes', 'locations'])
                               ->paginate(6) 
                               ->withQueryString();

        // --- THIS IS THE CORRECTED SECTION FOR THE NEW ERROR ---
        // Fetch filter options using the capitalized Model Names
        $industries = IndustryType::orderBy('IndustryType')->get();
        $partnershipTypes = PartnershipType::orderBy('PartnershipTypeName')->get();
        $locations = Location::orderBy('LocationName')->get();
        $organizationTypes = Organization::select('OrganizationType')->distinct()->pluck('OrganizationType');


        // Return the view with all the necessary data
        return view('user.find_partners', [
            'organizations' => $organizations,
            'industries' => $industries,
            'partnershipTypes' => $partnershipTypes,
            'locations' => $locations,
            'organizationTypes' => $organizationTypes,
            'filters' => $request->all()
        ]);
    }
}