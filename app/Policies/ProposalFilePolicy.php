<?php

namespace App\Policies;

use App\Models\ProposalFile;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProposalFilePolicy
{
    /**
     * Determine whether the user can download the proposal file.
     */
    public function download(User $user, ProposalFile $proposalFile): bool
    {
        // Get the organization the proposal was sent to.
        $targetOrganization = $proposalFile->proposal->organization;

        // Check if the user is a member of that organization.
        return $targetOrganization->users()->where('users.UserID', $user->UserID)->exists();
    }
}
