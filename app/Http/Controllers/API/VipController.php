<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVipRequest;
use App\Http\Requests\UpdateVipRequest;
use App\Http\Resources\VipResource;
use App\Models\Vip;
use App\Services\VipService;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VipController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    protected VipService $vipService;

    public function __construct(VipService $vipService)
    {
        $this->vipService = $vipService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Vip::class);

        $filters = $request->only(['search', 'sort_by', 'sort_order', 'per_page']);

        $vips = $this->vipService->getVips($filters);

        return $this->successResponse(
            VipResource::collection($vips)->response()->getData(true),
            'VIPs retrieved successfully'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVipRequest $request): JsonResponse
    {
        $this->authorize('create', Vip::class);

        /** @var \App\Models\User $user */
        $user = $request->user();
        $vip = $this->vipService->createVip($user, $request->validated());

        return $this->successResponse(
            new VipResource($vip),
            'VIP created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Vip $vip): JsonResponse
    {
        $this->authorize('view', $vip);

        $vip = $this->vipService->getVipById($vip->id);

        if (!$vip) {
            return $this->notFoundResponse('VIP not found');
        }

        return $this->successResponse(
            new VipResource($vip),
            'VIP retrieved successfully'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVipRequest $request, Vip $vip): JsonResponse
    {
        $this->authorize('update', $vip);

        $updatedVip = $this->vipService->updateVip($vip, $request->validated());

        return $this->successResponse(
            new VipResource($updatedVip),
            'VIP updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vip $vip): JsonResponse
    {
        $this->authorize('delete', $vip);

        $this->vipService->deleteVip($vip);

        return $this->successResponse(
            null,
            'VIP deleted successfully'
        );
    }

    /**
     * Check if VIP exists
     */
    public function checkExists(Request $request): JsonResponse
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'contact_number' => 'nullable|string',
        ]);

        $vip = $this->vipService->checkVipExists(
            $request->first_name,
            $request->last_name,
            $request->contact_number
        );

        if ($vip) {
            return $this->successResponse(
                new VipResource($vip),
                'VIP found'
            );
        }

        return $this->successResponse(
            null,
            'VIP not found'
        );
    }
}
