<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'profile'])->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'), // Extract only the role names
                'profile' => $user->profile ? [
                    'surname' => $user->profile->surname,
                    'otherNames' => $user->profile->otherNames,
                    'dept' => $user->profile->dept,
                    'employmentType' => $user->profile->employmentType,
                    'employeeNo' => $user->profile->employeeNo,
                    'dateOfBirth' => $user->profile->dateOfBirth,
                    'sex' => $user->profile->sex,
                    'religion' => $user->profile->religion,
                    'telR' => $user->profile->telR,
                    'telCell' => $user->profile->telCell,
                    'currentAddress' => $user->profile->currentAddress,
                    'residence' => $user->profile->residence,
                    'postalAddress' => $user->profile->postalAddress,
                    'homeDistrict' => $user->profile->homeDistrict,
                    'fatherName' => $user->profile->fatherName,
                    'fatherDOB' => $user->profile->fatherDOB,
                    'fatherOccupation' => $user->profile->fatherOccupation,
                    'motherName' => $user->profile->motherName,
                    'motherDOB' => $user->profile->motherDOB,
                    'motherOccupation' => $user->profile->motherOccupation,
                    'maritalStatus' => $user->profile->maritalStatus,
                    'spouseName' => $user->profile->spouseName,
                    'dateOfMarriage' => $user->profile->dateOfMarriage,
                    'spouseTel' => $user->profile->spouseTel,
                    'children' => $user->profile->children,
                    'siblings' => $user->profile->siblings,
                    'status' => $user->profile->approval_status,
                ] : null, // Handle case where profile might not exist
                'profile_pic' => $user->profile_pic, // Add profile picture URL from users table
            ];
        });

        return Inertia::render('Users', [
            'users' => $users,
        ]);
    }

    public function updateProfilePic(Request $request)
    {
        $request->validate([
            'profilePic' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = $request->user();

        // Delete the old profile picture if exists
        if ($user->profile_pic) {
            Storage::delete($user->profile_pic);
        }

        // Store the new profile picture
        $path = $request->file('profilePic')->store('profile_pics', 'public');

        // Update the user's profile picture path in the database
        $user->profile_pic = $path;
        $user->save();

        // Return updated user data to the frontend
        return redirect()->back()->with('success', 'Profile picture updated successfully');
    }
}
