<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Users in {{ $company->name }}</h2>
            <a href="{{ route('companies.index') }}" class="btn btn-secondary">Back to Companies</a>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="container">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @php
            $isCompanyMember = auth()->user()->companies()->where('company_id', $company->id)->exists();
            @endphp

            @if(!$isCompanyMember)
            <div class="alert alert-warning">
                <strong>Notice:</strong> You are not a member of this company. Only company members can remove users.
            </div>
            @endif

            <!-- Formularz dodawania użytkownika -->
            <!--First user-->
            @if ($company->users()->count() === 0)            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Assign User to Company</h5>
                </div>
                <div class="card-body">
                    @include('companies.partials.assign-user-form')
                </div>
            </div>
            @else
            <!-- captain can assign users-->
            @can('manageMembers', $company)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Assign User to Company</h5>
                </div>
                <div class="card-body">
                    @include('companies.partials.assign-user-form')
                </div>
            </div>
            @endcan
            @endif

            <!-- Lista przypisanych użytkowników -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Assigned Users</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Assigned At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                <tr>
                                    <td>
                                        {{ $user->name }}
                                        @if($user->id === auth()->id())
                                        <span class="badge bg-info">You</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->pivot->created_at ? $user->pivot->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                    <td>
                                        @if($isCompanyMember)
                                        @can('manageMembers', $company)
                                        <form action="{{ route('companies.users.detach', [$company, $user]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove this user from company?')">Remove</button>
                                        </form>
                                        @endcan
                                        @else
                                        <span class="text-muted small">No permission</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No users assigned to this company yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>