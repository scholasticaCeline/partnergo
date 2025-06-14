<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Proposal;
use App\Models\ProposalFile;
use App\Models\Partnership;
use App\Models\PartnershipType;
use App\Http\Controllers\NotificationController; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProposalController extends Controller
{
    /**
     * Display a paginated list of proposals related to the user.
     */
    public function index()
    {
        $user = Auth::user();
        $userOrganizationIds = $user->organizations()->pluck('organizations.OrganizationID');

        $proposals = Proposal::with(['user', 'organization', 'proposingOrganization', 'partnershipType'])
            ->where(function ($query) use ($user, $userOrganizationIds) {
                $query->where('UserID', $user->UserID)
                    ->orWhereIn('OrganizationID', $userOrganizationIds);
            })
            ->latest('created_at')
            ->paginate(10);

        return view('proposals.list', compact('proposals'));
    }

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
     * CORRECTED: Store a new proposal AND its corresponding PENDING partnership.
     */
    public function store(Request $request, Organization $organization)
    {
        $validatedData = $request->validate([
            'proposing_organization_id' => 'nullable|exists:organizations,OrganizationID',
            'proposal_title' => 'required|string|max:255',
            'partnership_type_id' => 'required|exists:partnership_types,PartnershipTypeID',
            'description' => 'required|string|max:5000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'proposal_files' => 'nullable|array',
            'proposal_files.*' => 'required|file|mimes:pdf,doc,docx,jpg,png,zip|max:10240',
        ]);

        $proposal = DB::transaction(function () use ($validatedData, $organization, $request) {
            $user = Auth::user();

            $newProposal = Proposal::create([
                'ProposalID' => Str::uuid(),
                'UserID' => $user->UserID,
                'OrganizationID' => $organization->OrganizationID,
                'ProposingOrganizationID' => $validatedData['proposing_organization_id'] ?? null,
                'PartnershipTypeID' => $validatedData['partnership_type_id'],
                'ProposalTitle' => $validatedData['proposal_title'],
                'Description' => $validatedData['description'],
                'ProposalStatus' => 'submitted',
                'StartDate' => $validatedData['start_date'] ?? null,
                'EndDate' => $validatedData['end_date'] ?? null,
            ]);

            Partnership::create([
                'PartnershipID' => Str::uuid(),
                'ProposalID' => $newProposal->ProposalID,
                'OrganizationSenderID' => $newProposal->ProposingOrganizationID, // This could be null
                'OrganizationTargetID' => $newProposal->OrganizationID,
                'PartnershipTypeID' => $newProposal->PartnershipTypeID,
                'Status' => 'Pending', 
                'StartDate' => $newProposal->StartDate,
                'EndDate' => $newProposal->EndDate,
                'CreatedAt' => now(),
            ]);

            if ($request->hasFile('proposal_files')) {
                foreach ($request->file('proposal_files') as $file) {
                    $path = $file->store('proposals', 'private');
                    $newProposal->files()->create([
                        'ProposalFileID' => Str::uuid(),
                        'UploadedBy'     => $user->UserID, // Use correct UserID property
                        'FileName'       => $file->getClientOriginalName(),
                        'Filepath'       => $path,
                    ]);
                }
            }

            NotificationController::createForAdmins(
                $organization->OrganizationID,
                "New proposal submitted: \"{$newProposal->ProposalTitle}\""
            );
            
            return $newProposal;
        });

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
     * CORRECTED: Accept a proposal and ACTIVATE the existing partnership.
     */
    public function accept(Request $request, Proposal $proposal)
    {
        $this->authorize('update', $proposal->organization);

        DB::transaction(function () use ($proposal) {
            $proposal->update(['ProposalStatus' => 'accepted']);

            Partnership::where('ProposalID', $proposal->ProposalID)
                    ->update(['Status' => 'Active']);
        });

        return redirect()->route('proposals.show', $proposal)
                        ->with('success', 'Proposal accepted! The partnership is now active.');
    }

    /**
     * CORRECTED: Reject a proposal and update the partnership status.
     */
    public function reject(Request $request, Proposal $proposal)
    {
        $this->authorize('update', $proposal->organization);

        DB::transaction(function () use ($proposal) {
            $proposal->update(['ProposalStatus' => 'rejected']);

            Partnership::where('ProposalID', $proposal->ProposalID)
                    ->update(['Status' => 'Rejected']);
        });

        return redirect()->route('proposals.show', $proposal)
                        ->with('success', 'The proposal has been rejected.');
    }

    /**
     * Download a specific proposal file from private storage.
     */
    public function downloadFile(ProposalFile $proposalFile)
    {
        if (empty($proposalFile->Filepath) || !Storage::disk('private')->exists($proposalFile->Filepath)) {
            abort(404, 'File not found on disk.');
        }

        return Storage::disk('private')->download($proposalFile->Filepath, $proposalFile->FileName);
    }
}