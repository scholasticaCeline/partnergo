<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationLocation extends Model
{
    use HasFactory;
    protected $primaryKey = 'OrganizationLocationID';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;
}
