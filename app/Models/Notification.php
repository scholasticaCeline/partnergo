<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $primaryKey = 'NotificationID';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;
}

