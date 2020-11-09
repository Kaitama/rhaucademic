@if (session('success'))
<div class="ui icon success message">
	<i class="check icon"></i>
	<div class="content">
		<div class="header">
			Sukses.
		</div>
		<p>{{session('success')}}</p>
	</div>
	<i class="close icon"></i>
</div>
@endif

@if (session('error'))
<div class="ui icon error message">
	<i class="exclamation circle icon"></i>
	<div class="content">
		<div class="header">
			Gagal.
		</div>
		<p>{{session('error')}}</p>
	</div>
	<i class="close icon"></i>
</div>
@endif

@if ($errors->any())
<div class="ui icon error message">
	<i class="exclamation circle icon"></i>
	<div class="content">
		<div class="header">Error</div>
		<ul class="list">
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	<i class="close icon"></i>
</div>
@endif