<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;
    protected $primaryKey = 'LocationID';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    public function organizationLocations()
    {
        return $this->hasMany(OrganizationLocation::class, 'LocationID', 'LocationID');
    }

}

