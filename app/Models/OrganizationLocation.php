<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;

/**
 * This class MUST extend Pivot to work correctly.
 */
class OrganizationLocation extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organization_locations'; // Make sure this is your exact pivot table name

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'OrganizationLocationID';

    /**
     * Indicates if the IDs are auto-incrementing.
     * This MUST be false for UUIDs.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The "booted" method of the model.
     * This ensures the UUID is automatically generated.
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