<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Models\Message;
use App\Models\Proposal;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'UserID';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'email',
        'phoneNumber',
        'password',
        'avatar'
    ];

    protected $hidden = [
        'password',
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

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'user_organizations', 'UserID', 'OrganizationID')
                ->withPivot('IsAdmin')
                ->using(UserOrganization::class);
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
