<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IndustryType extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'IndustryTypeID';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    public function organizationIndustryTypes()
    {
        return $this->hasMany(OrganizationIndustryType::class, 'IndustryTypeID', 'IndustryTypeID');
    }

}

