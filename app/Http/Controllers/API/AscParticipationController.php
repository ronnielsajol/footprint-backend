<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAscParticipationRequest;
use App\Http\Requests\UpdateAscParticipationRequest;
use App\Http\Resources\AscParticipationResource;
use App\Models\AscParticipation;
use App\Services\AscParticipationService;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class AscParticipationController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    protected AscParticipationService $ascParticipationService;

    public function __construct(AscParticipationService $ascParticipationService)
    {
        $this->ascParticipationService = $ascParticipationService;
    }

    /**
     * Display participations for a deployment
     */
    public function index(string $deploymentType, int $deploymentId): JsonResponse
    {
        // Convert hyphenated route format to underscore format for service
        $deploymentType = str_replace('-', '_', $deploymentType);

        // Validate deployment type
        if (!in_array($deploymentType, ['pol_deployment', 'w_asc_deployment'])) {
            return $this->errorResponse('Invalid deployment type', null, 400);
        }

        $participations = $this->ascParticipationService->getParticipationsForDeployment($deploymentType, $deploymentId);

        return $this->successResponse(
            AscParticipationResource::collection($participations),
            'ASC participations retrieved successfully'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAscParticipationRequest $request, string $deploymentType, int $deploymentId): JsonResponse
    {
        $this->authorize('create', AscParticipation::class);

        // Convert hyphenated route format to underscore format for service
        $deploymentType = str_replace('-', '_', $deploymentType);

        // Validate deployment type
        if (!in_array($deploymentType, ['pol_deployment', 'w_asc_deployment'])) {
            return $this->errorResponse('Invalid deployment type', null, 400);
        }

        /** @var \App\Models\User $user */
        $user = $request->user();
        $participation = $this->ascParticipationService->createParticipation($user, $deploymentType, $deploymentId, $request->validated());

        return $this->successResponse(
            new AscParticipationResource($participation),
            'ASC participation created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(AscParticipation $ascParticipation): JsonResponse
    {
        $this->authorize('view', $ascParticipation);

        $participation = $this->ascParticipationService->getParticipationById($ascParticipation->id);

        if (!$participation) {
            return $this->notFoundResponse('ASC participation not found');
        }

        return $this->successResponse(
            new AscParticipationResource($participation),
            'ASC participation retrieved successfully'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAscParticipationRequest $request, AscParticipation $ascParticipation): JsonResponse
    {
        $this->authorize('update', $ascParticipation);

        $updatedParticipation = $this->ascParticipationService->updateParticipation($ascParticipation, $request->validated());

        return $this->successResponse(
            new AscParticipationResource($updatedParticipation),
            'ASC participation updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AscParticipation $ascParticipation): JsonResponse
    {
        $this->authorize('delete', $ascParticipation);

        $this->ascParticipationService->deleteParticipation($ascParticipation);

        return $this->successResponse(
            null,
            'ASC participation deleted successfully'
        );
    }
}
