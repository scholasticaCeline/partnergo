<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProposalFile extends Model
{
    use HasFactory;
    protected $primaryKey = 'ProposalFileID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $dates = ['CreatedAt'];

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'UploadedBy', 'UserID');
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'ProposalID', 'ProposalID');
    }

}

