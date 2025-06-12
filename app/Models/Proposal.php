<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Proposal extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'ProposalID';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     * CORRECTED $fillable ARRAY
     */
    protected $fillable = [
        'ProposalID',
        'ProposalTitle',
        'Description',
        'ProposalStatus',
        'StartDate',
        'EndDate',
        'UserID',
        'OrganizationID',
        'ProposingOrganizationID',
        'PartnershipTypeID',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'OrganizationID', 'OrganizationID');
    }

    public function proposingOrganization()
    {
        return $this->belongsTo(Organization::class, 'ProposingOrganizationID', 'OrganizationID');
    }

    public function partnershipType()
    {
        return $this->belongsTo(PartnershipType::class, 'PartnershipTypeID', 'PartnershipTypeID');
    }

    public function files()
    {
        return $this->hasMany(ProposalFile::class, 'ProposalID', 'ProposalID');
    }
}