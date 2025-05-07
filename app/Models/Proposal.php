<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $primaryKey = 'ProposalID';
    public $incrementing = false;
    protected $keyType = 'string';

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'OrganizationID');
    }

    public function files()
    {
        return $this->hasMany(ProposalFile::class, 'ProposalID');
    }

    public function partnerships()
    {
        return $this->hasMany(Partnership::class, 'ProposalID');
    }
}
