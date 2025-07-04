<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partnership extends Model
{
    use HasFactory;
    protected $primaryKey = 'PartnershipID';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'PartnershipID',
        'ProposalID',
        'OrganizationSenderID',
        'OrganizationTargetID',
        'PartnershipTypeID',
        'Status',
        'StartDate',
        'EndDate',
        'CreatedAt',
    ];

    protected $dates = ['CreatedAt', 'StartDate', 'EndDate'];

    public function sender()
    {
        return $this->belongsTo(Organization::class, 'OrganizationSenderID');
    }

    public function target()
    {
        return $this->belongsTo(Organization::class, 'OrganizationTargetID');
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'ProposalID');
    }

    public function partnershipType()
    {
        return $this->belongsTo(PartnershipType::class, 'PartnershipTypeID');
    }
    public function senderOrganization()
    {
        return $this->belongsTo(Organization::class, 'OrganizationSenderID', 'OrganizationID');
    }

    public function targetOrganization()
    {
        return $this->belongsTo(Organization::class, 'OrganizationTargetID', 'OrganizationID');
    }
}

