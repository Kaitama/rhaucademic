<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
	//
	public function index()
	{
		$roles = Role::all();
		$permissions = Permission::all();
		return view('dashboard.permission.index', ['permissions' => $permissions, 'roles' => $roles]);
	}		
	
	public function store(Request $request)
	{
		
		Permission::create([
			'name' => $request->name,
			'guard_name' => $request->guard_name,
			]);
			
			return back()->with('success', 'Permission berhasil ditambahkan.');
		}
		
		public function destroy($id)
		{
			Permission::find($id)->delete();
			return back()->with('success', 'Permission berhasil dihapus.');
			
		}

		public function assign(Request $request)
		{
			$role = Role::find($request->role_id);
			$permissions = $request->permission_name;
			$role->syncPermissions($permissions);

			return back()->with('success', 'Permission berhasil diset.');
		}
	}
	