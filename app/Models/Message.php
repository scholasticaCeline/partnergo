<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;
    protected $primaryKey = 'MessageID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $dates = ['CreatedAt', 'ReadAt'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'SenderID', 'UserID');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'ReceiverID', 'UserID');
    }
}
