@if ($crud->hasAccess('create'))
    <a href="{{ url($crud->route.'/'.$entry->getKey().'/softDelete') }} " class="btn btn-sm btn-link"><i class="la la-arrow-circle-right"></i> Soft Delete</a>
@endif
