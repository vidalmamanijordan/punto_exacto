<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $service
    ) {}

    public function index()
    {
        return RoleResource::collection(
            $this->service->getAll()
        );
    }

    public function store(StoreRoleRequest $request)
    {
        $role = $this->service->create($request->validated());
        return new RoleResource($role);
    }

    public function show(Role $role)
    {
        return new RoleResource(
            $role->load('permissions')
        );
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role = $this->service->update(
            $role,
            $request->validated()
        );
        return new RoleResource($role);
    }

    public function destroy(Role $role)
    {
        $this->service->delete($role);

        return response()->json([
            'message' => 'Role eliminado correctamente'
        ]);
    }
}
