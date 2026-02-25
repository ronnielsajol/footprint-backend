<?php

namespace App\Services;

use App\Models\AscDirective;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class AscDirectiveService
{
    /**
     * Get all ASC directives for a deployment (polymorphic)
     */
    public function getDirectivesForDeployment(string $deploymentType, int $deploymentId): Collection
    {
        return AscDirective::with(['deployment', 'creator'])
            ->where('deployment_type', $deploymentType)
            ->where('deployment_id', $deploymentId)
            ->orderBy('issued_date', 'desc')
            ->get();
    }

    /**
     * Create a new ASC directive
     */
    public function createDirective(User $user, string $deploymentType, int $deploymentId, array $data): AscDirective
    {
        $data['created_by'] = $user->id;
        $data['deployment_type'] = $deploymentType;
        $data['deployment_id'] = $deploymentId;

        return AscDirective::create($data);
    }

    /**
     * Update an existing ASC directive
     */
    public function updateDirective(AscDirective $directive, array $data): AscDirective
    {
        $directive->update($data);
        return $directive->fresh();
    }

    /**
     * Delete an ASC directive
     */
    public function deleteDirective(AscDirective $directive): bool
    {
        return $directive->delete();
    }

    /**
     * Get ASC directive by ID
     */
    public function getDirectiveById(int $id): ?AscDirective
    {
        return AscDirective::with(['deployment', 'creator'])->find($id);
    }
}
