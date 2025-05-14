<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationPartnershipType extends Model
{
    use HasFactory;

    protected $primaryKey = 'OrganizationPartnershipTypeID';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;
}

