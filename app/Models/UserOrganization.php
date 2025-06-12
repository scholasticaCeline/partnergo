<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;

class UserOrganization extends Pivot
{
    use HasFactory;
    /**
     * The table associated with the model.
     * This must match your database table name exactly.
     * @var string
     */
    protected $table = 'user_organizations';

    /**
     * The primary key for the model.
     * @var string
     */
    protected $primaryKey = 'UserOrganizationID';

    /**
     * Indicates if the IDs are auto-incrementing.
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key.
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The "booted" method to auto-generate UUIDs.
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid();
            }
        });
    }
}