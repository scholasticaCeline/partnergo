<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Organization extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organizations';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'OrganizationID';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'OrganizationID',
        'Name',
        'Description',
        'OrganizationType',
        'OpenForPartnership',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_organizations', 'OrganizationID', 'UserID')
                ->withPivot('IsAdmin')
                ->using(UserOrganization::class);
    }

    public function senderPartnerships()
    {   
        return $this->hasMany(Partnership::class, 'OrganizationSenderID');
    }

    public function receiverPartnerships()
    {
        return $this->hasMany(Partnership::class, 'OrganizationTargetID');
    }

    public function industries()
    {
        return $this->belongsToMany(IndustryType::class, 'organization_industry_types', 'OrganizationID', 'IndustryTypeID')
                    ->using(OrganizationIndustryType::class); // <-- Add this
    }

    public function partnershipTypes()
    {
        return $this->belongsToMany(PartnershipType::class, 'organization_partnership_types', 'OrganizationID', 'PartnershipTypeID')
                    ->using(OrganizationPartnershipType::class); // <-- Add this
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'organization_locations', 'OrganizationID', 'LocationID')
                    ->using(OrganizationLocation::class); 
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
    public function proposingOrganization()
    {
        return $this->belongsTo(Organization::class, 'ProposingOrganizationID', 'OrganizationID');
    }

}

