@if ($crud->hasAccess('read'))
    <a href="{{ url($crud->route.'/'.$entry->getKey().'/reset') }} " class="btn btn-sm btn-link"><i class="la la-arrow-circle-right"></i> Send password reset</a>
@endif
