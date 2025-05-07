<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndustryType extends Model
{
    protected $primaryKey = 'IndustryTypeID';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    public function organizationIndustryTypes()
    {
        return $this->hasMany(OrganizationIndustryType::class, 'IndustryTypeID', 'IndustryTypeID');
    }

}

