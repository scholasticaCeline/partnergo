<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationLocation extends Model
{
    protected $primaryKey = 'OrganizationLocationID';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;
}
