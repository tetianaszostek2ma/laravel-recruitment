<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Edit Company</h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('companies.update', $company) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label">UUID</label>
                                    <input type="text" class="form-control" value="{{ $company->uuid }}" disabled>
                                    <small class="text-muted">UUID cannot be changed</small>
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Company Name</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $company->name) }}"
                                           required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('companies.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-dark">Update Company</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
