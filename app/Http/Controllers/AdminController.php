<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use App\Models\User;
use App\Models\Contribution;

class AdminController extends Controller
{
    public function index()
{
    // Fetch total users count
    $totalUsers = User::count();

    // Fetch total contributions amount
    $totalContributions = Contribution::sum('amount');

    // Fetch monthly collection amount for the current month
    $monthlyCollection = Contribution::whereMonth('created_at', now()->month)->sum('amount');

    // Fetch contributions data grouped by month for the last 9 months
    $contributionsData = Contribution::selectRaw('DATE_FORMAT(created_at, "%b %Y") as month_year, SUM(amount) as total_amount')
        ->groupBy('month_year')
        ->orderByRaw('MIN(created_at)') // Order by the minimum created_at within each group
        ->limit(9)
        ->get();

    return Inertia::render('Admin/AdminIndex', [
        'totalUsers' => $totalUsers,
        'totalContributions' => $totalContributions,
        'monthlyCollection' => $monthlyCollection,
        'contributionsData' => $contributionsData,
    ]);
}



    public function users(): Response
    {
        $user = auth()->user(); // Assuming you're using Laravel's authentication
        $errors = []; // Example empty errors array

        return Inertia::render('Users', [
            'auth' => [
                'user' => $user,
            ],
            'errors' => $errors,
        ]);
    }

    public function manageRoles(): Response
    {
        return Inertia::render('ManageRoles');
    }

    public function contributions()
{

    return Inertia::render('Contributions');
}

    public function dashboard(): Response
    {
        return Inertia::render('Dashboard');
    }

    public function adminProfile(): Response
    {
        return Inertia::render('Admin/Profile');
    }



    public function adminContribution(): Response
    {
        return Inertia::render('Admin/Contribution');
    }


    public function adminNotification(): Response
    {
        return Inertia::render('Admin/Notifications');
    }



    public function adminSetting(): Response
    {
        return Inertia::render('Admin/Settings');
    }

}
