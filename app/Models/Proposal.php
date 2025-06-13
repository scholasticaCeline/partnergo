<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Don't forget to import this

class Proposal extends Model
{
    use HasFactory, HasUuids; // Make sure HasUuids is used here

    protected $primaryKey = 'ProposalID';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
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

    /**
     * The attributes that should be cast to native types.
     * This is the crucial part for your error!
     *
     * @var array
     */
    protected $casts = [
        'StartDate'  => 'datetime',
        'EndDate'    => 'datetime',
        'created_at' => 'datetime', // Laravel typically handles this, but explicit casting is robust
        'updated_at' => 'datetime', // Laravel typically handles this, but explicit casting is robust
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID'); // Assuming UserID is the primary key for User
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

    public function targetOrganization()
    {
        return $this->belongsTo(Organization::class, 'OrganizationID', 'OrganizationID');
    }
}