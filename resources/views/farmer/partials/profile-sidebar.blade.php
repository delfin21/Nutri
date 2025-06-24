<div class="col-md-3 sidebar-nav">
    <a href="{{ route('farmer.profile.show') }}" class="{{ request()->is('farmer/profile') ? 'active' : '' }}">
        <i class="bi bi-person-circle"></i> Account
    </a>
    
    <a href="{{ route('farmer.profile.payout') }}" class="{{ request()->is('farmer/profile/payout') ? 'active' : '' }}">
        <i class="bi bi-bank"></i> Payout
    </a>

    <a href="{{ route('farmer.payouts.index') }}" class="{{ request()->is('farmer/payouts*') ? 'active' : '' }}">
        <i class="bi bi-cash-coin"></i> Receipt Payments
    </a>

    <a href="{{ route('farmer.profile.address') }}" class="{{ request()->is('farmer/profile/address') ? 'active' : '' }}">
        <i class="bi bi-geo-alt-fill"></i> Address
    </a>
</div>

