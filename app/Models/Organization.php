<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $primaryKey = 'OrganizationID';
    public $incrementing = false;
    protected $keyType = 'string';

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_organizations', 'OrganizationID', 'UserID')
                    ->withPivot('UserOrganizationID', 'IsAdmin');
    }

    public function senderPartnerships()
    {
        return $this->hasMany(Partnership::class, 'OrganizationSenderID');
    }

    public function receiverPartnerships()
    {
        return $this->hasMany(Partnership::class, 'OrganizationTargetID');
    }

    public function partnershipTypes()
    {
        return $this->belongsToMany(PartnershipType::class, 'organization_partnership_types', 'OrganizationID', 'PartnershipTypeID')
                    ->withPivot('OrganizationPartnershipTypeID');
    }

    public function industries()
    {
        return $this->belongsToMany(IndustryType::class, 'organization_industry_types', 'OrganizationID', 'IndustryTypeID')
                    ->withPivot('OrganizationIndustryID');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'organization_locations', 'OrganizationID', 'LocationID')
                    ->withPivot('OrganizationLocationID');
    }

    public function userOrganizations()
    {
        return $this->hasMany(UserOrganization::class, 'OrganizationID', 'OrganizationID');
    }
    
    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'OrganizationID', 'OrganizationID');
    }
    
    public function sentPartnerships()
    {
        return $this->hasMany(Partnership::class, 'OrganizationSenderID', 'OrganizationID');
    }
    
    public function receivedPartnerships()
    {
        return $this->hasMany(Partnership::class, 'OrganizationTargetID', 'OrganizationID');
    }
    
    public function organizationPartnershipTypes()
    {
        return $this->hasMany(OrganizationPartnershipType::class, 'OrganizationID', 'OrganizationID');
    }
    
    public function organizationIndustryTypes()
    {
        return $this->hasMany(OrganizationIndustryType::class, 'OrganizationID', 'OrganizationID');
    }
    
    public function organizationLocations()
    {
        return $this->hasMany(OrganizationLocation::class, 'OrganizationID', 'OrganizationID');
    }
    
}

