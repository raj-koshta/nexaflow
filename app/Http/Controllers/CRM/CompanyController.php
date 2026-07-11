<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\User;
use App\Services\CRM\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $companies = Company::withCount('users')->latest()->get();
            return view('companies.table', compact('companies'))->render();
        }

        return view('companies.index');
    }

    public function store(StoreCompanyRequest $request)
    {
        $company = $this->companyService->createCompany($request->validated());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Company created successfully.',
                'data' => $company
            ]);
        }

        return redirect()->route('companies.index')->with('success', 'Company created successfully.');
    }

    public function show(Company $company)
    {
        $company->load('users');
        $availableUsers = User::whereDoesntHave('companies', function($query) use ($company) {
            $query->where('companies.id', $company->id);
        })->get();

        return view('companies.show', compact('company', 'availableUsers'));
    }

    public function edit(Company $company)
    {
        return response()->json($company);
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $company = $this->companyService->updateCompany($company, $request->validated());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Company updated successfully.',
                'data' => $company
            ]);
        }

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        $this->companyService->deleteCompany($company);

        return response()->json([
            'success' => true,
            'message' => 'Company deleted successfully.'
        ]);
    }

    public function addMember(Request $request, Company $company)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'is_primary' => 'nullable|boolean'
        ]);

        $this->companyService->addMember($company, $request->user_id, $request->is_primary ?? false);

        return back()->with('success', 'User added to company.');
    }

    public function removeMember(Company $company, User $user)
    {
        $this->companyService->removeMember($company, $user->id);

        return back()->with('success', 'User removed from company.');
    }
}
