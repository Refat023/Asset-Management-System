<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $role;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->role = auth()->user()->roles[0];
            return $next($request);
        });
    }

    private function normalizeRolePermissions(Request $request): array
    {
        $permissions = $request->input('permission', []);

        if (!is_array($permissions)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', $permissions), function ($permission) {
            return $permission !== '';
        }));
    }

    private function addRolePermission(Role $role, Request $request): void
    {
        $role->syncPermissions($this->normalizeRolePermissions($request));
    }
    
    public function roles()
    {   
        $roles = Role::all();
        return view("admin.roles.index", compact("roles"));
    }

    public function permissions()
    {
        $permissions = Permission::all();
        return view("admin.roles.permissions.index", compact("permissions"));
    }

    public function permissions_create()
    {
        return view("admin.roles.permissions.create");
    }

    public function permissions_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::firstOrCreate([
            'name' => trim($request->name),
        ]);

        return redirect()->route('permissions.index')
            ->with('season', 'Permission Created!');
    }

    public function permissions_edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.roles.permissions.edit', compact('permission'));
    }

    public function permissions_update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ]);

        $permission = Permission::findOrFail($id);
        $permission->name = trim($request->name);
        $permission->save();

        return redirect()->route('permissions.index')
            ->with('season', 'Permission Updated!');
    }

    public function permissions_destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('season', 'Permission Deleted!');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function roles_create()
    {
        $permission = Permission::all();
        return view("admin.roles.create", compact("permission"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function roles_store(Request $request)
    {
         $request->validate([
             'name' => 'required|string|max:255|unique:roles,name',
         ]);

         $role = Role::create(["name" => trim($request->name)]);
         $this->addRolePermission($role, $request);

         return redirect()->route("roles.index")
                         ->with("season", "Role Created!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::all();
        return view("admin.roles.role_edit", compact("role", "permission"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function roles_update(Request $request, $id)
    {

       $role = Role::find($id);
       $role->name = trim($request->name);
       $role->save();
       $this->addRolePermission($role, $request);

       return redirect()->route("roles.index")
       ->with("season", "Role Updated!");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        $role -> delete();

        return back();
    }
}
