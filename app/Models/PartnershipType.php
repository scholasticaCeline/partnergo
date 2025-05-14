<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartnershipType extends Model
{
    use HasFactory;
    use HasFactory;

    protected $primaryKey = 'PartnershipTypeID';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_partnership_types', 'PartnershipTypeID', 'OrganizationID');
    }

    public function partnerships()
    {
        return $this->hasMany(Partnership::class, 'PartnershipTypeID');
    }
}

