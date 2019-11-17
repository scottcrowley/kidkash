@can('viewNav', auth()->user())
    <a href="{{ route('kids.index') }}">Manage Kids</a> 
    <a href="{{ route('adults.index') }}">Manage Adults</a> 
    <a href="{{ route('vendors.index') }}">Manage Vendors</a>
    <a href="{{ route('transactions.index') }}">Manage Transactions</a>
@endcan
