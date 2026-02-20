@extends('layouts.app')

@section('content')
<h2 class="mb-3">Add Payer</h2>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('payers.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Type</label>
                <select name="payer_type" class="form-select" id="payer_type">
                    <option value="individual" {{ old('payer_type') !== 'business' ? 'selected' : '' }}>Individual</option>
                    <option value="business" {{ old('payer_type') === 'business' ? 'selected' : '' }}>Business</option>
                </select>
            </div>

            <div class="mb-3" id="individual_name">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}">
            </div>

            <div class="mb-3" id="business_name" style="display:none;">
                <label class="form-label">Business Name</label>
                <input type="text" name="business_name" class="form-control" value="{{ old('business_name') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control" value="{{ old('location') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Electoral Area</label>
                <input type="text" name="electoral_area" class="form-control" value="{{ old('electoral_area') }}">
            </div>

            <button class="btn btn-primary">Save</button>
        </form>
    </div>
</div>

<script>
const payerTypeElement = document.getElementById('payer_type');
const individualNameElement = document.getElementById('individual_name');
const businessNameElement = document.getElementById('business_name');

function togglePayerFields() {
    if (payerTypeElement.value === 'individual') {
        individualNameElement.style.display = 'block';
        businessNameElement.style.display = 'none';
    } else {
        individualNameElement.style.display = 'none';
        businessNameElement.style.display = 'block';
    }
}

payerTypeElement.addEventListener('change', togglePayerFields);
togglePayerFields();
</script>
@endsection
