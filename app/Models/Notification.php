<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;
    protected $primaryKey = 'NotificationID';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;
}

