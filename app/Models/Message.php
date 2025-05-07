<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $primaryKey = 'MessageID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $dates = ['CreatedAt', 'ReadAt'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'SenderID');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'ReceiverID');
    }
}
