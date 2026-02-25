<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAscDirectiveRequest;
use App\Http\Requests\UpdateAscDirectiveRequest;
use App\Http\Resources\AscDirectiveResource;
use App\Models\AscDirective;
use App\Services\AscDirectiveService;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class AscDirectiveController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    protected AscDirectiveService $ascDirectiveService;

    public function __construct(AscDirectiveService $ascDirectiveService)
    {
        $this->ascDirectiveService = $ascDirectiveService;
    }

    /**
     * Display directives for a deployment
     */
    public function index(string $deploymentType, int $deploymentId): JsonResponse
    {
        // Convert hyphenated route format to underscore format for service
        $deploymentType = str_replace('-', '_', $deploymentType);

        // Validate deployment type
        if (!in_array($deploymentType, ['pol_deployment', 'w_asc_deployment'])) {
            return $this->errorResponse('Invalid deployment type', null, 400);
        }

        $directives = $this->ascDirectiveService->getDirectivesForDeployment($deploymentType, $deploymentId);

        return $this->successResponse(
            AscDirectiveResource::collection($directives),
            'ASC directives retrieved successfully'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAscDirectiveRequest $request, string $deploymentType, int $deploymentId): JsonResponse
    {
        $this->authorize('create', AscDirective::class);

        // Convert hyphenated route format to underscore format for service
        $deploymentType = str_replace('-', '_', $deploymentType);

        // Validate deployment type
        if (!in_array($deploymentType, ['pol_deployment', 'w_asc_deployment'])) {
            return $this->errorResponse('Invalid deployment type', null, 400);
        }

        /** @var \App\Models\User $user */
        $user = $request->user();
        $directive = $this->ascDirectiveService->createDirective($user, $deploymentType, $deploymentId, $request->validated());

        return $this->successResponse(
            new AscDirectiveResource($directive),
            'ASC directive created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(AscDirective $ascDirective): JsonResponse
    {
        $this->authorize('view', $ascDirective);

        $directive = $this->ascDirectiveService->getDirectiveById($ascDirective->id);

        if (!$directive) {
            return $this->notFoundResponse('ASC directive not found');
        }

        return $this->successResponse(
            new AscDirectiveResource($directive),
            'ASC directive retrieved successfully'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAscDirectiveRequest $request, AscDirective $ascDirective): JsonResponse
    {
        $this->authorize('update', $ascDirective);

        $updatedDirective = $this->ascDirectiveService->updateDirective($ascDirective, $request->validated());

        return $this->successResponse(
            new AscDirectiveResource($updatedDirective),
            'ASC directive updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AscDirective $ascDirective): JsonResponse
    {
        $this->authorize('delete', $ascDirective);

        $this->ascDirectiveService->deleteDirective($ascDirective);

        return $this->successResponse(
            null,
            'ASC directive deleted successfully'
        );
    }
}
