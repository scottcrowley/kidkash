@can('viewNav', auth()->user())
    <a href="{{ route('users.index') }}">Manage Users</a> 
    <a href="{{ route('vendors.index') }}">Manage Vendors</a>
    <a href="{{ route('transactions.index') }}">Manage Transactions</a>
    <a href="{{ route('cards.index') }}">Manage Cards</a>
@endcan
