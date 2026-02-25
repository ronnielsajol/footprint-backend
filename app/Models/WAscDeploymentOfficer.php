<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WAscDeploymentOfficer extends Model
{
    use HasFactory;

    protected $table = 'w_asc_deployment_officers';

    protected $fillable = [
        'w_asc_deployment_id',
        'officer_name',
    ];

    /**
     * Get the deployment this officer belongs to.
     */
    public function wAscDeployment(): BelongsTo
    {
        return $this->belongsTo(WAscDeployment::class, 'w_asc_deployment_id');
    }
}
