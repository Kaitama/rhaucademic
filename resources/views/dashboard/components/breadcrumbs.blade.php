{{-- <div class="ui breadcrumb">
	<a class="section">Dashboard</a>
	<i class="right angle icon divider"></i>
	<div class="active section">Index</div>
</div> --}}

@if (count($breadcrumbs))
<div class="ui segment">
	<div class="ui breadcrumb">
		@foreach ($breadcrumbs as $breadcrumb)
		
		@if ($breadcrumb->url && !$loop->last)
		<a class="section" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
		<i class="right angle icon divider"></i>
		@else
		<div class="section active">{{ $breadcrumb->title }}</div>
		@endif
		
		@endforeach
	</div>
</div>
@endif