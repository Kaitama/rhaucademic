<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\User;

class RoleController extends Controller
{
		//
	public function index()
	{
		$users = User::all();
		$roles = Role::all();
		return view('dashboard.role.index', ['roles' => $roles, 'users' => $users]);
	}		

	public function store(Request $request)
	{
		
		Role::create([
			'name' => $request->name,
			'guard_name' => $request->guard_name,
		]);

		return back()->with('success', 'Role berhasil ditambahkan.');
	}

	public function destroy($id)
	{
		Role::find($id)->delete();
		return back()->with('success', 'Role berhasil dihapus.');

	}

	public function assign(Request $request)
	{
		$user = User::find($request->user_id);
		$roles = $request->role_name;
		$user->syncRoles($roles);
		return back()->with('success', 'Role user berhasil diset.');
	}
}