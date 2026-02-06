<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Add New Company</h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('companies.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label">Company Name</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">UUID will be generated automatically</small>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('companies.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-dark">Create Company</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
