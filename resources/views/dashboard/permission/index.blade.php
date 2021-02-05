@extends('dashboard.template')
@section('pagetitle', 'Permissions')

@section('content')
@include('dashboard.components.flashmessage')

<div class="ui stackable grid">
	<div class="ten wide column">
		<div class="ui segments">
			<div class="ui grey segment menu">
				<h3>Add Permissions</h3>
			</div>
			<div class="ui segment">
				
				<form action="{{route('permission.store')}}" method="post" class="ui form">
					@csrf
					<div class="fields">
						<div class="ten wide field">
							<label>Nama Permissions</label>
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
				<h3>Assign Permissions</h3>
			</div>
			<div class="ui segment">
				
				<form action="{{route('permission.assign')}}" method="post" class="ui form">
					@csrf
					<div class="field">
						<label>Nama Role</label>
						<select class="ui fluid dropdown" name="role_id">
							@foreach ($roles->where('name', '!=', 'developer') as $role)
							<option value="{{$role->id}}">{{ucwords($role->name)}}</option>
							@endforeach
						</select>
					</div>
					<div class="field">
						<label>Permissions</label>
						<select multiple="" class="ui search dropdown" name="permission_name[]">
							@foreach ($permissions as $permission)
							<option value="{{$permission->name}}">{{$permission->name}}</option>
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

		<div class="ui segments">
			<div class="ui black segment menu">
				<h3>Role With Permission</h3>
			</div>
			<div class="ui segment">
				<div class="ui middle aligned divided list">
					@foreach ($roles->where('name', '!=', 'developer') as $role)
							<div class="item">
								<div class="content">
									<div class="header">{{ucwords($role->name)}}</div>
									<div class="description">{{ucwords($role->getPermissionNames()->implode(', '))}}</div>
								</div>
							</div>
					@endforeach
				</div>
			</div>
		</div>
		
	</div>
	
	<div class="six wide column">
		<div class="ui segments">
			<div class="ui grey segment menu">
				<h3>List Permissions</h3>
			</div>
			<div class="ui segment">
				
				<div class="ui middle aligned divided list">
					@foreach ($permissions as $permission)
					<div class="item">
						<div class="right floated content">
							<a href="{{route('permission.destroy', $permission->id)}}" class="ui mini icon button negative"><i class="ui trash icon"></i></a>
						</div>
						<div class="content">
							<div class="header">{{ucwords($permission->name)}}</div>
							<div class="description">{{ucwords($permission->getRoleNames()->implode(', '))}}</div>
						</div>
					</div>
					@endforeach
					
				</div>
				
			</div>
		</div>
	</div>
	
</div>

@endsection