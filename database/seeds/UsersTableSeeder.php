<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Userprofile;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
	/**
	* Run the database seeds.
	*
	* @return void
	*/
	public function run()
	{
		//
		$user = User::create([
			'level' => 1,
			'name' => 'Khairi Ibnutama',
			'username'	=> 'developer',
			'email'	=> 'mr.ibnutama@gmail.com',
			'password' => bcrypt('password'),
			]
		);
		
		Userprofile::create([
			'user_id' => $user->id,
			]
		);
		
		$dev = Role::create(['name' => 'developer', 'guard_name' => 'web']);
		$user->syncRoles($dev);
		
		// faker user
		factory(App\User::class, 2)->create()->each(function ($usr) {
			$usr->userprofile()->save(factory(Userprofile::class)->make());
		});
	}
}