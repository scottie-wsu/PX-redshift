@if ($crud->hasAccess('create'))
	<a href="javascript:void(0)" onclick="deleteEntry1()" data-route="{{ url($crud->route.'/'.$entry->getKey()) .'/'.'softDelete' }}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i> {{ trans('backpack::crud.delete') }}</a>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

	<script>

		function deleteEntry1() {
			Swal.fire({
				title: 'Disable this method?',
				text: "This action cannot be undone.",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Delete'
			}).then((result) => {
				if (result.isConfirmed) {
					location.href = "../{{ $crud->route.'/'.$entry->getKey() .'/'.'softDelete' }}";
				}
			})
		}
	</script>

@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}

