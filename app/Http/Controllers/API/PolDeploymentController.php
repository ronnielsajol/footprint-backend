<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddVipToPolDeploymentRequest;
use App\Http\Requests\StorePolDeploymentRequest;
use App\Http\Requests\UpdatePolDeploymentRequest;
use App\Http\Resources\PolDeploymentResource;
use App\Http\Resources\VipResource;
use App\Models\PolDeployment;
use App\Models\Vip;
use App\Services\PolDeploymentService;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PolDeploymentController extends Controller
{
  use ApiResponse, AuthorizesRequests;

  protected PolDeploymentService $deploymentService;

  public function __construct(PolDeploymentService $deploymentService)
  {
    $this->deploymentService = $deploymentService;
  }

  /**
   * Display a listing of POL deployments.
   */
  public function index(Request $request): JsonResponse
  {
    $this->authorize('viewAny', PolDeployment::class);

    $filters = $request->only([
      'search',
      'year',
      'month',
      'source',
      'category',
      'asc_type',
      'sort_by',
      'sort_order',
      'per_page'
    ]);

    /** @var \App\Models\User $user */
    $user = $request->user();
    $deployments = $this->deploymentService->getDeployments($user, $filters);

    return $this->successResponse(
      PolDeploymentResource::collection($deployments)->response()->getData(true),
      'POL deployments retrieved successfully'
    );
  }

  /**
   * Store a newly created POL deployment.
   */
  public function store(StorePolDeploymentRequest $request): JsonResponse
  {
    $this->authorize('create', PolDeployment::class);

    /** @var \App\Models\User $user */
    $user = $request->user();
    $deployment = $this->deploymentService->createDeployment($user, $request->validated());

    return $this->successResponse(
      new PolDeploymentResource($deployment),
      'POL deployment created successfully',
      201
    );
  }

  /**
   * Display the specified POL deployment.
   */
  public function show(int $id): JsonResponse
  {
    $deployment = $this->deploymentService->getDeploymentById($id);

    if (!$deployment) {
      return $this->notFoundResponse('POL deployment not found');
    }

    $this->authorize('view', $deployment);

    return $this->successResponse(
      new PolDeploymentResource($deployment),
      'POL deployment retrieved successfully'
    );
  }

  /**
   * Update the specified POL deployment.
   */
  public function update(UpdatePolDeploymentRequest $request, int $id): JsonResponse
  {
    $deployment = $this->deploymentService->getDeploymentById($id);

    if (!$deployment) {
      return $this->notFoundResponse('POL deployment not found');
    }

    $this->authorize('update', $deployment);

    $updated = $this->deploymentService->updateDeployment($deployment, $request->validated());

    return $this->successResponse(
      new PolDeploymentResource($updated),
      'POL deployment updated successfully'
    );
  }

  /**
   * Remove the specified POL deployment.
   */
  public function destroy(int $id): JsonResponse
  {
    $deployment = $this->deploymentService->getDeploymentById($id);

    if (!$deployment) {
      return $this->notFoundResponse('POL deployment not found');
    }

    $this->authorize('delete', $deployment);

    $this->deploymentService->deleteDeployment($deployment);

    return $this->successResponse(
      null,
      'POL deployment deleted successfully'
    );
  }

  /**
   * Get VIPs for a POL deployment.
   */
  public function getVips(int $id): JsonResponse
  {
    $deployment = $this->deploymentService->getDeploymentById($id);

    if (!$deployment) {
      return $this->notFoundResponse('POL deployment not found');
    }

    $this->authorize('view', $deployment);

    $vips = $this->deploymentService->getDeploymentVips($deployment);

    return $this->successResponse(
      VipResource::collection($vips),
      'POL deployment VIPs retrieved successfully'
    );
  }

  /**
   * Add a VIP to a POL deployment.
   */
  public function addVip(AddVipToPolDeploymentRequest $request, int $id): JsonResponse
  {
    $deployment = $this->deploymentService->getDeploymentById($id);

    if (!$deployment) {
      return $this->notFoundResponse('POL deployment not found');
    }

    $this->authorize('update', $deployment);

    $vip = Vip::find($request->validated()['vip_id']);

    if (!$vip) {
      return $this->notFoundResponse('VIP not found');
    }

    $this->deploymentService->addVipToDeployment(
      $deployment,
      $vip,
      $request->validated()['remarks'] ?? null
    );

    return $this->successResponse(
      null,
      'VIP added to POL deployment successfully'
    );
  }

  /**
   * Remove a VIP from a POL deployment.
   */
  public function removeVip(int $id, int $vipId): JsonResponse
  {
    $deployment = $this->deploymentService->getDeploymentById($id);

    if (!$deployment) {
      return $this->notFoundResponse('POL deployment not found');
    }

    $this->authorize('update', $deployment);

    $removed = $this->deploymentService->removeVipFromDeployment($deployment, $vipId);

    if (!$removed) {
      return $this->errorResponse('VIP not found in this deployment', null, 404);
    }

    return $this->successResponse(
      null,
      'VIP removed from POL deployment successfully'
    );
  }
}
