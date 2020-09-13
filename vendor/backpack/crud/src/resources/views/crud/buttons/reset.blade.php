@if ($crud->hasAccess('update'))
    <a href="{{ url($crud->route.'/'.$entry->getKey().'/reset') }} " class="btn btn-xs btn-default"><i class="fa fa-ban"></i> Reset Password</a>
@endif
