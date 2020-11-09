@extends('dashboard.template')
@section('pagetitle', 'Roles')

@section('content')
@include('dashboard.components.flashmessage')

<div class="ui grid">
	<div class="ten wide column">
		<div class="ui segments">
			<div class="ui grey segment menu">
				<h3>Add Roles</h3>
			</div>
			<div class="ui segment">
				
				<form action="{{route('role.store')}}" method="post" class="ui form">
					@csrf
					<div class="fields">
						<div class="ten wide field">
							<label>Nama Role</label>
							<input type="text" name="name">
						</div>
						<div class="six wide field">
							<label>Guard</label>
							<select class="ui fluid dropdown" name="guard_name">
								<option value="web">Web</option>
								<option value="api">API</option>
							</select>
						</div>
					</div>
					<div class="field">
						<button class="ui labeled button icon green">
							<i class="save icon"></i>
							Simpan
						</button>
					</div>
				</form>
			</div>
			
		</div>

		<div class="ui segments">
			<div class="ui grey segment menu">
				<h3>Assign Roles</h3>
			</div>
			<div class="ui segment">
				
				<form action="{{route('role.assign')}}" method="post" class="ui form">
					@csrf
					<div class="field">
						<label>Nama User</label>
						<select class="ui search dropdown" name="user_id">
							<option value="">Cari user</option>
							@foreach ($users as $user)
							<option value="{{$user->id}}">{{ucwords($user->name)}}</option>
							@endforeach
						</select>
					</div>
					<div class="field">
						<label>Roles</label>
						<select multiple="" class="ui dropdown" name="role_name[]">
							@foreach ($roles as $role)
							<option value="{{$role->name}}">{{ucwords($role->name)}}</option>
							@endforeach
						</select>
					</div>
					<div class="field">
						<button class="ui labeled button icon green">
							<i class="check icon"></i>
							Assign
						</button>
					</div>
				</form>
			</div>
			
		</div>

	</div>
	
	<div class="six wide column">
		<div class="ui segments">
			<div class="ui grey segment menu">
				<h3>List Roles</h3>
			</div>
			<div class="ui segment">
				
				<div class="ui middle aligned divided list">
					@foreach ($roles as $role)
					<div class="item">
						<div class="right floated content">
							<a href="{{route('role.destroy', $role->id)}}" class="ui mini icon button negative"><i class="ui trash icon"></i></a>
						</div>
						<div class="content">
							<div class="header">{{ucwords($role->name)}}</div>
							<div class="description">{{$role->users()->count()}} User</div>
						</div>
					</div>
					@endforeach
					
				</div>
				
			</div>
		</div>
	</div>
</div>

@endsection