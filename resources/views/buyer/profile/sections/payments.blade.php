<div class="card shadow-sm rounded-3 mb-4">
    <div class="card-header bg-warning text-dark fw-bold">
        🏷 Store Credits
    </div>
    <div class="card-body">
        @php
            $credits = \App\Models\StoreCredit::where('buyer_id', Auth::id())->latest()->get();
            $totalCredit = $credits->sum('amount');
        @endphp

        <p><strong>Total Available Credit:</strong> ₱{{ number_format($totalCredit, 2) }}</p>

        @if ($credits->count())
            <ul class="list-group mt-3">
                @foreach ($credits as $credit)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $credit->description ?? 'Credit issued' }}
                        <span class="badge bg-success">₱{{ number_format($credit->amount, 2) }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="alert alert-info mt-3">You have no issued store credits at this time.</div>
        @endif
    </div>
</div>

<div class="card shadow-sm rounded-3">
    <div class="card-header bg-success text-white fw-bold">
        Payment Methods
    </div>
    <div class="card-body">
        <p class="text-muted">Currently, we do not store payment methods. All payments are handled externally through PayMongo upon checkout.</p>

        {{-- Optional future feature --}}
        <div class="mt-4">
            <h6 class="fw-bold mb-3">Preferred Payment Option (Coming Soon)</h6>
            <form>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="payment_method" id="gcash" value="gcash" disabled>
                    <label class="form-check-label" for="gcash">
                        GCash
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="payment_method" id="maya" value="maya" disabled>
                    <label class="form-check-label" for="maya">
                        Maya
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="payment_method" id="card" value="card" disabled>
                    <label class="form-check-label" for="card">
                        Debit/Credit Card
                    </label>
                </div>
                <button type="submit" class="btn btn-success mt-3" disabled>Save Preference</button>
            </form>
        </div>
    </div>
</div>
