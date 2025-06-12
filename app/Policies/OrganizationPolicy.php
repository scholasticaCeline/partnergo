<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrganizationPolicy
{
    /**
     * Determine whether the user can view the organization's dashboard.
     */
    public function viewDashboard(User $user, Organization $organization): bool
    {
        // A user can view the dashboard IF AND ONLY IF they are an admin.
        return $user->organizations()
                    ->where('organizations.OrganizationID', $organization->OrganizationID)
                    ->wherePivot('IsAdmin', true)
                    ->exists();
    }

    /**
     * Determine whether the user can update the organization.
     */
    public function update(User $user, Organization $organization): bool
    {
        // A user can update/manage IF AND ONLY IF they are an admin.
        return $user->organizations()
                    ->where('organizations.OrganizationID', $organization->OrganizationID)
                    ->wherePivot('IsAdmin', true)
                    ->exists();
    }
}