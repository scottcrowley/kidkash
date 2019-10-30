@can('update', auth()->user())
    <a href="{{ route('kids.index') }}">Manage Kids</a> 
@endcan
@can('update', auth()->user())
    <a href="{{ route('vendors.index') }}">Manage Vendors</a> 
@endcan