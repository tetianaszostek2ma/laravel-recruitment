<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyUserController extends Controller
{
    public function index(Company $company)
    {
        $users = $company->users()->paginate(10);
        $availableUsers = User::whereDoesntHave('companies', function($query) use ($company) {
            $query->where('company_id', $company->id);
        })->get();

        return view('companies.users.index', compact('company', 'users', 'availableUsers'));
    }

    public function attach(Request $request, Company $company)
    {
        if ($company->users()->count() > 0) {
            $this->authorize('manageMembers', $company);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // check if user is first in company
        $isFirst = $company->users()->count() === 0;
        $company->users()->attach($request->user_id, ['is_captain' => $isFirst]);

        return redirect()->route('companies.users.index', $company)
            ->with('success', 'User assigned to company successfully.');
    }

    public function detach(Company $company, User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('companies.users.index', $company)
            ->with('error', 'The captain cannot remove themselves.');
        }
        // Sprawdź czy zalogowany user należy do tej firmy
        if (!auth()->user()->companies()->where('company_id', $company->id)->exists()) {
            abort(403, 'You must be a member of this company to remove users.');
        }

        $company->users()->detach($user->id);

        return redirect()->route('companies.users.index', $company)
            ->with('success', 'User removed from company successfully.');
    }

    public function transferCaptain(Company $company, User $user)
    {
        $this->authorize('manageMembers', $company);

        if (! $company->users()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Selected user is not a member of this company.');
        }

        $currentCaptain = $company->users()
            ->wherePivot('is_captain', true)
            ->first();

        if (! $currentCaptain) {
            return back()->with('error', 'This company has no captain assigned.');
        }

        $company->users()->updateExistingPivot($currentCaptain->id, [
            'is_captain' => false,
        ]);

        $company->users()->updateExistingPivot($user->id, [
            'is_captain' => true,
        ]);

        return back()->with('success', 'Captain role has been transferred.');
    }
}
