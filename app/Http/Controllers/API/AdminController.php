<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AdminService;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    protected AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * Check if the authenticated user is a superadmin.
     */
    protected function checkSuperadmin(): ?JsonResponse
    {
        /** @var \App\Models\User|null $user */
        $user = request()->user();
        if (!$user || !$user->isSuperadmin()) {
            return $this->forbiddenResponse('Only superadmin can manage admins.');
        }
        return null;
    }

    /**
     * Display a listing of POL admins.
     */
    public function index(Request $request): JsonResponse
    {
        if ($error = $this->checkSuperadmin()) {
            return $error;
        }

        $filters = $request->only(['search', 'per_page']);

        $admins = $this->adminService->getAdmins($filters);

        return $this->successResponse(
            UserResource::collection($admins)->response()->getData(true),
            'Admins retrieved successfully'
        );
    }

    /**
     * Store a newly created POL admin.
     */
    public function store(StoreAdminRequest $request): JsonResponse
    {
        if ($error = $this->checkSuperadmin()) {
            return $error;
        }

        $admin = $this->adminService->createAdmin($request->validated());

        return $this->successResponse(
            new UserResource($admin),
            'Admin created successfully',
            201
        );
    }


    public function show(int $id): JsonResponse
    {
        $admin = $this->adminService->getAdminById($id);

        if (!$admin) {
            return $this->notFoundResponse('Admin not found');
        }

        return $this->successResponse(
            new UserResource($admin),
            'Admin retrieved successfully'
        );
    }


    public function update(UpdateAdminRequest $request, int $id): JsonResponse
    {
        $admin = $this->adminService->getAdminById($id);

        if (!$admin) {
            return $this->notFoundResponse('Admin not found');
        }

        $updatedAdmin = $this->adminService->updateAdmin($admin, $request->validated());

        return $this->successResponse(
            new UserResource($updatedAdmin),
            'Admin updated successfully'
        );
    }


    public function destroy(int $id): JsonResponse
    {
        $admin = $this->adminService->getAdminById($id);

        if (!$admin) {
            return $this->notFoundResponse('Admin not found');
        }

        $deleted = $this->adminService->deleteAdmin($admin);

        if (!$deleted) {
            return $this->errorResponse('Cannot delete superadmin', null, 400);
        }

        return $this->successResponse(
            null,
            'Admin deleted successfully'
        );
    }
}
