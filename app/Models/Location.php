<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $primaryKey = 'LocationID';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    public function organizationLocations()
    {
        return $this->hasMany(OrganizationLocation::class, 'LocationID', 'LocationID');
    }

}

