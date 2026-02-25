<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AscParticipation extends Model
{
    use HasFactory;

    protected $fillable = [
        'deployment_type',
        'deployment_id',
        'participation_details',
        'personnel_count',
        'resources_deployed',
        'remarks',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'personnel_count' => 'integer',
        ];
    }

    /**
     * Get the parent deployment model (polymorphic).
     */
    public function deployment(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who created this participation.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
