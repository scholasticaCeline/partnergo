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

    protected $fillable = [
        'NotificationID',
        'TargetType',
        'TargetID',
        'Content',
    ];
    
    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }
}

