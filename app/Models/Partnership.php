<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partnership extends Model
{
    protected $primaryKey = 'PartnershipID';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

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
}

