@extends('layouts.farmer')

@section('title', 'Payout Settings')

@push('styles')
<style>
    .profile-title {
        font-weight: bold;
        font-size: 1.5rem;
        color: #1e4d2b;
        margin-bottom: 1.5rem;
    }
    .sidebar-nav a {
        display: block;
        padding: 0.5rem 1rem;
        font-weight: bold;
        color: #1e4d2b;
        text-decoration: none;
        border-left: 4px solid transparent;
        margin-bottom: 0.5rem;
    }
    .sidebar-nav a.active,
    .sidebar-nav a:hover {
        background-color: #d4edda;
        border-left: 4px solid #1e4d2b;
        border-radius: 5px;
    }
</style>
@endpush

@section('content')
@php $user = Auth::user()->fresh(); @endphp

<div class="container">
    <h2 class="profile-title">PAYOUT SETTINGS</h2>

    <div class="row">
        @include('farmer.partials.profile-sidebar')

        <div class="col-md-9">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your submission:
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('farmer.profile.updatePayout') }}">
                @csrf

                <p class="text-muted small mb-3">We use this info to send your earnings. Please double-check accuracy.</p>

                {{-- ✅ PRIMARY PAYOUT METHOD --}}
                <div class="mb-3">
                    <label for="payout_method" class="form-label">Primary Payout Method <span class="text-danger">*</span></label>
                    <select class="form-select" name="payout_method" id="payout_method" required onchange="updatePayoutFields()">
                        <option value="" disabled {{ $user->payout_method ? '' : 'selected' }}>Select method</option>
                        <option value="GCash" {{ $user->payout_method == 'GCash' ? 'selected' : '' }}>GCash</option>
                        <option value="Bank" {{ $user->payout_method == 'Bank' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="Maya" {{ $user->payout_method == 'Maya' ? 'selected' : '' }}>Maya</option>
                    </select>
                </div>

                <div id="gcash_fields" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="payout_account" pattern="\d{11}" placeholder="e.g. 09XXXXXXXXX"
                            value="{{ old('payout_account', $user->payout_account) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Name (GCash/Maya Account Name) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="payout_name" value="{{ old('payout_name', $user->payout_name) }}">
                    </div>
                </div>

                <div id="bank_fields" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="payout_bank" value="{{ old('payout_bank', $user->payout_bank) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bank Account Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="payout_bank_name" value="{{ old('payout_bank_name', $user->payout_name) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bank Account Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="payout_bank_account" value="{{ old('payout_bank_account', $user->payout_account) }}">
                    </div>
                </div>

                <div class="mb-3">
                    @if($user->payout_verified)
                        <span class="badge bg-success">Verified</span>
                    @else
                        
                    @endif
                </div>

                <hr class="my-4">

                {{-- ✅ SECONDARY PAYOUT METHOD --}}
                <h5>Secondary Payout (Optional)</h5>

                <div class="mb-3">
                    <label class="form-label">Secondary Method</label>
                    <select class="form-select" name="payout_method_secondary" id="payout_method_secondary" onchange="updateSecondaryFields()">
                        <option value="">None</option>
                        <option value="GCash" {{ $user->payout_method_secondary == 'GCash' ? 'selected' : '' }}>GCash</option>
                        <option value="Bank" {{ $user->payout_method_secondary == 'Bank' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="Maya" {{ $user->payout_method_secondary == 'Maya' ? 'selected' : '' }}>Maya</option>
                    </select>
                </div>

                <div id="secondary_gcash_fields" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Secondary Mobile Number</label>
                        <input type="text" class="form-control" name="payout_account_secondary"
                            value="{{ old('payout_account_secondary', $user->payout_account_secondary) }}" placeholder="e.g. 09XXXXXXXXX">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Secondary GCash/Maya Account Name</label>
                        <input type="text" class="form-control" name="payout_name_secondary"
                            value="{{ old('payout_name_secondary', $user->payout_name_secondary) }}" placeholder="e.g. Juan Dela Cruz">
                    </div>
                </div>

                <div id="secondary_bank_fields" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Secondary Bank Name</label>
                        <input type="text" class="form-control" name="secondary_bank_name"
                            value="{{ old('secondary_bank_name', $user->secondary_bank_name) }}" placeholder="e.g. BDO, BPI, Metrobank">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Secondary Bank Account Number</label>
                        <input type="text" class="form-control" name="secondary_bank_account"
                            value="{{ old('secondary_bank_account', $user->secondary_bank_account) }}" placeholder="e.g. 1234567890">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Secondary Bank Account Name</label>
                        <input type="text" class="form-control" name="secondary_bank_account_name"
    value="{{ old('secondary_bank_account_name', $user->secondary_bank_account_name) }}" placeholder="e.g. Juan Dela Cruz">


                    </div>
                </div>

                <button type="submit" class="btn btn-success">Save Changes</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updatePayoutFields() {
    const method = document.querySelector('#payout_method').value;
    document.getElementById('gcash_fields').style.display = 'none';
    document.getElementById('bank_fields').style.display = 'none';

    if (method === 'GCash' || method === 'Maya') {
        document.getElementById('gcash_fields').style.display = 'block';
    } else if (method === 'Bank') {
        document.getElementById('bank_fields').style.display = 'block';
    }
}

function updateSecondaryFields() {
    const method = document.querySelector('#payout_method_secondary').value;
    document.getElementById('secondary_gcash_fields').style.display = 'none';
    document.getElementById('secondary_bank_fields').style.display = 'none';

    if (method === 'GCash' || method === 'Maya') {
        document.getElementById('secondary_gcash_fields').style.display = 'block';
    } else if (method === 'Bank') {
        document.getElementById('secondary_bank_fields').style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    updatePayoutFields();
    updateSecondaryFields();
});

document.querySelector('form').addEventListener('submit', function (e) {
    e.preventDefault();
    updatePayoutFields();
    updateSecondaryFields();
    setTimeout(() => this.submit(), 200);
});
</script>
@endpush
