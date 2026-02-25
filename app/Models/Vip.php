<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\Paginatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vip extends Model
{
    use HasFactory, SoftDeletes, Filterable, Paginatable;

    protected $fillable = [
        'first_name',
        'last_name',
        'contact_number',
        'email',
        'birth_date',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function polDeployments()
    {
        return $this->belongsToMany(PolDeployment::class, 'pol_deployment_vips')
            ->withPivot('remarks')
            ->withTimestamps();
    }

    public function wAscDeployments()
    {
        return $this->belongsToMany(WAscDeployment::class, 'w_asc_deployment_vips')
            ->withPivot('remarks')
            ->withTimestamps();
    }

    // Scopes
    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('contact_number', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
