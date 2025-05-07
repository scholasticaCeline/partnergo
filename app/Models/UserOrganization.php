<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOrganization extends Model
{
    protected $primaryKey = 'UserOrganizationID';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;
}
