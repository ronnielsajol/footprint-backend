<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\Paginatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes, Filterable, Paginatable;

    protected $fillable = [
        'title',
        'event_type',
        'description',
        'event_date',
        'location',
        'created_by',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function vips()
    {
        return $this->belongsToMany(Vip::class, 'event_vips')
            ->withPivot('remarks')
            ->withTimestamps();
    }

    public function ascDirectives()
    {
        return $this->hasMany(AscDirective::class);
    }

    public function ascParticipations()
    {
        return $this->hasMany(AscParticipation::class);
    }

    // Scopes
    public function scopeForUser($query, User $user)
    {
        if ($user->isSuperadmin()) {
            return $query;
        }

        return $query->where('created_by', $user->id);
    }

    public function scopeByCreator($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        });
    }

    public function scopeFilterByType($query, $type)
    {
        if (!$type) {
            return $query;
        }

        return $query->where('event_type', $type);
    }

    public function scopeFilterByStatus($query, $status)
    {
        if (!$status) {
            return $query;
        }

        return $query->where('status', $status);
    }

    public function scopeFilterByDateRange($query, $startDate, $endDate)
    {
        if ($startDate) {
            $query->whereDate('event_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('event_date', '<=', $endDate);
        }

        return $query;
    }
}
