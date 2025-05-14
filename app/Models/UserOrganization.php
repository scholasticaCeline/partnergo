<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserOrganization extends Model
{
    use HasFactory;
    protected $primaryKey = 'UserOrganizationID';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;
}
