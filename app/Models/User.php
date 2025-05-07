<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'UserID';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'Username',
        'Email',
        'Password',
    ];

    protected $hidden = [
        'Password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'Password' => 'hashed',
        ];
    }

    // Automatically generate UUID when creating
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }

    public function userOrganizations()
    {
        return $this->hasMany(UserOrganization::class, 'UserID', 'UserID');
    }
    
    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'UserID', 'UserID');
    }
    
    public function proposalFiles()
    {
        return $this->hasMany(ProposalFile::class, 'UploadedBy', 'UserID');
    }
    
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'SenderID', 'UserID');
    }
    
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'ReceiverID', 'UserID');
    }
    
}
