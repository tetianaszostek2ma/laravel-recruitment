@if($availableUsers->count() > 0)
<form action="{{ route('companies.users.attach', $company) }}" method="POST" class="row g-3">
    @csrf
    <div class="col-md-9">
        <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
            <option value="">Select User...</option>
            @foreach($availableUsers as $user)
            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>
        @error('user_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-dark w-100">Assign User</button>
    </div>
</form>
@else
<p class="text-muted mb-0">All users are already assigned to this company.</p>
@endif