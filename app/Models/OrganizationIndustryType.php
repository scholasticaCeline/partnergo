<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationIndustryType extends Model
{
    use HasFactory;
    protected $primaryKey = 'IndustryTypeID';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'OrganizationID', 'OrganizationID');
    }

    public function industryType()
    {
        return $this->belongsTo(IndustryType::class, 'IndustryTypeID', 'IndustryTypeID');
    }

}

