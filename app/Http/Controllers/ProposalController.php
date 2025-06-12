<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Proposal;
use App\Models\ProposalFile;
use App\Models\PartnershipType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProposalController extends Controller
{
    /**
     * Show the form for creating a new proposal.
     */
    public function create(Organization $organization)
    {
        $user = Auth::user();
        $userOrganizations = $user->organizations;

        $partnershipTypes = $organization->partnershipTypes()->orderBy('PartnershipTypeName')->get();

        return view('user.create_proposals', [
            'organization' => $organization,
            'partnershipTypes' => $partnershipTypes,
            'userOrganizations' => $userOrganizations,
            'dateRequiredTypes' => ['Event Collaboration', 'Sponsorships', 'Campaign/Co-Marketing', 'Content Creation', 'CSR/Community Projects'],
        ]);
    }

    /**
     * Store a newly created proposal in storage.
     */
    public function store(Request $request, Organization $organization)
    {
        // 1. VALIDATION IS NOW STANDARDIZED TO snake_case to match the form
        $validatedData = $request->validate([
            'proposing_organization_id' => 'nullable|exists:organizations,OrganizationID',
            'proposal_title'            => 'required|string|max:255',
            'partnership_type_id'       => 'required|exists:partnership_types,PartnershipTypeID', // Table name is PascalCase as per your ERD
            'description'               => 'required|string|max:5000',
            'start_date'                => 'nullable|date',
            'end_date'                  => 'nullable|date|after_or_equal:start_date',
            'proposal_files'            => 'required|array|min:1',
            'proposal_files.*'          => 'required|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        // 2. CREATE THE PROPOSAL using validated data
        // We map the snake_case request data to your PascalCase database columns
        $proposal = Proposal::create([
            'ProposalID'                => Str::uuid(),
            'UserID'                    => Auth::id(),
            'OrganizationID'            => $organization->OrganizationID,
            'ProposingOrganizationID' => $validatedData['proposing_organization_id'] ?? null,
            'PartnershipTypeID'         => $validatedData['partnership_type_id'],
            'ProposalTitle'             => $validatedData['proposal_title'],
            'Description'               => $validatedData['description'],
            'ProposalStatus'            => 'submitted',
            'StartDate'                 => $validatedData['start_date'] ?? null,
            'EndDate'                   => $validatedData['end_date'] ?? null,
        ]);

        // 3. FILE HANDLING remains the same
        if ($request->hasFile('proposal_files')) {
            foreach ($request->file('proposal_files') as $file) {
                $path = $file->store('proposals', 'private');
                $proposal->files()->create([
                    'ProposalFileID' => Str::uuid(),
                    'UploadedBy'       => Auth::id(),
                    'FileName'       => $file->getClientOriginalName(),
                    'FilePath'       => $path,
                ]);
            }
        }
        
        // 4. REDIRECT to the new proposal's detail page
        return redirect()->route('proposals.show', $proposal)
                        ->with('success', 'Your partnership proposal has been sent!');
    }

    /**
     * Display the specified proposal's profile.
     */
    public function show(Proposal $proposal)
    {
        $proposal->load(['user', 'organization', 'proposingOrganization', 'partnershipType', 'files']);
        return view('proposals.show', ['proposal' => $proposal]);
    }

    /**
     * Download a specific proposal file from private storage.
     */
    public function downloadFile(ProposalFile $proposalFile)
    {
        if (empty($proposalFile->FilePath)) {
            abort(404, 'File not found in storage.');
        }

        if (!Storage::disk('private')->exists($proposalFile->FilePath)) {
            abort(404, 'File not found on disk.');
        }

        return Storage::disk('private')->download($proposalFile->FilePath, $proposalFile->FileName);
    }
}