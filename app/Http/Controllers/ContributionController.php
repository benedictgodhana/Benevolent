<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contribution;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ContributionsImport;


class ContributionController extends Controller
{
    public function index()
{
    // Retrieve the authenticated user
    $user = Auth::user();

    // Retrieve contributions associated with the authenticated user
    $contributions = $user-> contributions()->get();

    // Return the contributions as JSON
    return response()->json(['contributions' => $contributions]);
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'code' => 'required|string|max:255',
        ]);

        $contribution = new Contribution($validated);
        $request->user()->contributions()->save($contribution);

        return redirect()->route('contributions.index')->with('success', 'Contribution recorded successfully.');
    }


    public function contribution()
{
    $contributions = Contribution::with('user')->get()->map(function ($contribution) {
        return [
            'id' => $contribution->id,
            'date' => $contribution->date,
            'description' => $contribution->description,
            'amount' => $contribution->amount,
            'user' => [
                'id' => $contribution->user->id,
                'name' => $contribution->user->name,
                'email' => $contribution->user->email,
            ],
        ];
    });

    return Inertia::render('Contributions', [
        'contributions' => $contributions,
    ]);
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:csv,xlsx,xls',
    ]);

    try {
        Excel::import(new ContributionsImport, $request->file('file'));

        return response()->json(['message' => 'Contributions imported successfully']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error importing contributions', 'error' => $e->getMessage()], 500);
    }
}


}
