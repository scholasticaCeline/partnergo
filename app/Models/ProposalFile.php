<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalFile extends Model
{
    use HasFactory;

    /**
     * 1. Tell Laravel the exact table name.
     */
    protected $table = 'proposal_files';

    /**
     * 2. Define the Primary Key and its type.
     */
    protected $primaryKey = 'ProposalFileID';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * 3. IMPORTANT: Tell Laravel about your custom timestamp column names.
     */
    const CREATED_AT = 'CreatedAt'; // To match your 'CreatedAt' column
    const UPDATED_AT = 'updated_at'; // To match your 'updated_at' column

    /**
     * 4. List the columns that are "fillable" to allow mass assignment.
     */
    protected $fillable = [
        'ProposalFileID',
        'UploadedBy',
        'ProposalID',
        'FileName',
        'FilePath',
    ];

    /**
     * Defines the relationship back to the proposal.
     */
    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'ProposalID', 'ProposalID');
    }

    public function getRouteKeyName()
    {
        return 'ProposalFileID';
    }
}