<?php

namespace App\Services;

use App\Models\AscParticipation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class AscParticipationService
{
    /**
     * Get all ASC participations for a deployment (polymorphic)
     */
    public function getParticipationsForDeployment(string $deploymentType, int $deploymentId): Collection
    {
        return AscParticipation::with(['deployment', 'creator'])
            ->where('deployment_type', $deploymentType)
            ->where('deployment_id', $deploymentId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Create a new ASC participation
     */
    public function createParticipation(User $user, string $deploymentType, int $deploymentId, array $data): AscParticipation
    {
        $data['created_by'] = $user->id;
        $data['deployment_type'] = $deploymentType;
        $data['deployment_id'] = $deploymentId;

        return AscParticipation::create($data);
    }

    /**
     * Update an existing ASC participation
     */
    public function updateParticipation(AscParticipation $participation, array $data): AscParticipation
    {
        $participation->update($data);
        return $participation->fresh();
    }

    /**
     * Delete an ASC participation
     */
    public function deleteParticipation(AscParticipation $participation): bool
    {
        return $participation->delete();
    }

    /**
     * Get ASC participation by ID
     */
    public function getParticipationById(int $id): ?AscParticipation
    {
        return AscParticipation::with(['deployment', 'creator'])->find($id);
    }
}
