{{-- modal delete --}}
<div id="modal-delete" class="ui modal tiny">
	<div class="header">
		Peringatan!
	</div>
	<div class="content">
		<div class="description">
			<p id="message"></p>
			<div class="ui header">Anda yakin?</div>
		</div>
	</div>
	<form class="actions ui form" id="form-delete" method="POST" action="">
		@csrf
		<input type="hidden" name="id" id="data-id" value="">
		<div class="ui black deny button">
			Batal
		</div>
		<button type="submit" class="ui negative right labeled icon button">
			Ya, hapus!
			<i class="checkmark icon"></i>
		</button>
	</form>
</div>