<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddOfficerToWAscDeploymentRequest;
use App\Http\Requests\AddVipToWAscDeploymentRequest;
use App\Http\Requests\StoreWAscDeploymentRequest;
use App\Http\Requests\UpdateWAscDeploymentRequest;
use App\Http\Resources\WAscDeploymentResource;
use App\Http\Resources\WAscDeploymentOfficerResource;
use App\Http\Resources\VipResource;
use App\Models\WAscDeployment;
use App\Models\WAscDeploymentOfficer;
use App\Models\Vip;
use App\Services\WAscDeploymentService;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WAscDeploymentController extends Controller
{
  use ApiResponse, AuthorizesRequests;

  protected WAscDeploymentService $deploymentService;

  public function __construct(WAscDeploymentService $deploymentService)
  {
    $this->deploymentService = $deploymentService;
  }

  /**
   * Display a listing of W ASC deployments.
   */
  public function index(Request $request): JsonResponse
  {
    $this->authorize('viewAny', WAscDeployment::class);

    $filters = $request->only([
      'search',
      'year',
      'month',
      'sector',
      'has_socials',
      'has_sortie',
      'asc_attended',
      'llc_attended',
      'psc_attended',
      'sort_by',
      'sort_order',
      'per_page'
    ]);

    /** @var \App\Models\User $user */
    $user = $request->user();
    $deployments = $this->deploymentService->getDeployments($user, $filters);

    return $this->successResponse(
      WAscDeploymentResource::collection($deployments)->response()->getData(true),
      'W ASC deployments retrieved successfully'
    );
  }

  /**
   * Store a newly created W ASC deployment.
   */
  public function store(StoreWAscDeploymentRequest $request): JsonResponse
  {
    $this->authorize('create', WAscDeployment::class);

    /** @var \App\Models\User $user */
    $user = $request->user();
    $deployment = $this->deploymentService->createDeployment($user, $request->validated());

    return $this->successResponse(
      new WAscDeploymentResource($deployment),
      'W ASC deployment created successfully',
      201
    );
  }

  /**
   * Display the specified W ASC deployment.
   */
  public function show(int $id): JsonResponse
  {
    $deployment = $this->deploymentService->getDeploymentById($id);

    if (!$deployment) {
      return $this->notFoundResponse('W ASC deployment not found');
    }

    $this->authorize('view', $deployment);

    return $this->successResponse(
      new WAscDeploymentResource($deployment),
      'W ASC deployment retrieved successfully'
    );
  }

  /**
   * Update the specified W ASC deployment.
   */
  public function update(UpdateWAscDeploymentRequest $request, int $id): JsonResponse
  {
    $deployment = $this->deploymentService->getDeploymentById($id);

    if (!$deployment) {
      return $this->notFoundResponse('W ASC deployment not found');
    }

    $this->authorize('update', $deployment);

    $updated = $this->deploymentService->updateDeployment($deployment, $request->validated());

    return $this->successResponse(
      new WAscDeploymentResource($updated),
      'W ASC deployment updated successfully'
    );
  }

  /**
   * Remove the specified W ASC deployment.
   */
  public function destroy(int $id): JsonResponse
  {
    $deployment = $this->deploymentService->getDeploymentById($id);

    if (!$deployment) {
      return $this->notFoundResponse('W ASC deployment not found');
    }

    $this->authorize('delete', $deployment);

    $this->deploymentService->deleteDeployment($deployment);

    return $this->successResponse(
      null,
      'W ASC deployment deleted successfully'
    );
  }

  /**
   * Get officers for a W ASC deployment.
   */
  public function getOfficers(int $id): JsonResponse
  {
    $deployment = $this->deploymentService->getDeploymentById($id);

    if (!$deployment) {
      return $this->notFoundResponse('W ASC deployment not found');
    }

    $this->authorize('view', $deployment);

    $officers = $this->deploymentService->getDeploymentOfficers($deployment);

    return $this->successResponse(
      WAscDeploymentOfficerResource::collection($officers),
      'W ASC deployment officers retrieved successfully'
    );
  }

  /**
   * Add an officer to a W ASC deployment.
   */
  public function addOfficer(AddOfficerToWAscDeploymentRequest $request, int $id): JsonResponse
  {
    $deployment = $this->deploymentService->getDeploymentById($id);

    if (!$deployment) {
      return $this->notFoundResponse('W ASC deployment not found');
    }

    $this->authorize('update', $deployment);

    $officer = $this->deploymentService->addOfficerToDeployment(
      $deployment,
      $request->validated()['officer_name']
    );

    return $this->successResponse(
      new WAscDeploymentOfficerResource($officer),
      'Officer added to W ASC deployment successfully',
      201
    );
  }

  /**
   * Update an officer.
   */
  public function updateOfficer(AddOfficerToWAscDeploymentRequest $request, int $id, int $officerId): JsonResponse
  {
    $deployment = $this->deploymentService->getDeploymentById($id);

    if (!$deployment) {
      return $this->notFoundResponse('W ASC deployment not found');
    }

    $this->authorize('update', $deployment);

    $officer = WAscDeploymentOfficer::where('id', $officerId)
      ->where('w_asc_deployment_id', $id)
      ->first();

    if (!$officer) {
      return $this->notFoundResponse('Officer not found in this deployment');
    }

    $updated = $this->deploymentService->updateOfficer(
      $officer,
      $request->validated()['officer_name']
    );

    return $this->successResponse(
      new WAscDeploymentOfficerResource($updated),
      'Officer updated successfully'
    );
  }

  /**
   * Remove an officer from a W ASC deployment.
   */
  public function removeOfficer(int $id, int $officerId): JsonResponse
  {
    $deployment = $this->deploymentService->getDeploymentById($id);

    if (!$deployment) {
      return $this->notFoundResponse('W ASC deployment not found');
    }

    $this->authorize('update', $deployment);

    $officer = WAscDeploymentOfficer::where('id', $officerId)
      ->where('w_asc_deployment_id', $id)
      ->first();

    if (!$officer) {
      return $this->notFoundResponse('Officer not found in this deployment');
    }

    $this->deploymentService->removeOfficer($officer);

    return $this->successResponse(
      null,
      'Officer removed from W ASC deployment successfully'
    );
  }

  /**
   * Get VIPs for a W ASC deployment.
   */
  public function getVips(int $id): JsonResponse
  {
    $deployment = $this->deploymentService->getDeploymentById($id);

    if (!$deployment) {
      return $this->notFoundResponse('W ASC deployment not found');
    }

    $this->authorize('view', $deployment);

    $vips = $this->deploymentService->getDeploymentVips($deployment);

    return $this->successResponse(
      VipResource::collection($vips),
      'W ASC deployment VIPs retrieved successfully'
    );
  }

  /**
   * Add a VIP to a W ASC deployment.
   */
  public function addVip(AddVipToWAscDeploymentRequest $request, int $id): JsonResponse
  {
    $deployment = $this->deploymentService->getDeploymentById($id);

    if (!$deployment) {
      return $this->notFoundResponse('W ASC deployment not found');
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
      'VIP added to W ASC deployment successfully'
    );
  }

  /**
   * Remove a VIP from a W ASC deployment.
   */
  public function removeVip(int $id, int $vipId): JsonResponse
  {
    $deployment = $this->deploymentService->getDeploymentById($id);

    if (!$deployment) {
      return $this->notFoundResponse('W ASC deployment not found');
    }

    $this->authorize('update', $deployment);

    $removed = $this->deploymentService->removeVipFromDeployment($deployment, $vipId);

    if (!$removed) {
      return $this->errorResponse('VIP not found in this deployment', null, 404);
    }

    return $this->successResponse(
      null,
      'VIP removed from W ASC deployment successfully'
    );
  }
}
