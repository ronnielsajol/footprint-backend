<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AscDirective extends Model
{
    use HasFactory;

    protected $fillable = [
        'deployment_type',
        'deployment_id',
        'directive_text',
        'issued_by',
        'issued_date',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'issued_date' => 'date',
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
     * Get the user who created this directive.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
