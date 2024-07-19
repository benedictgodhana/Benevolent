<?php

// app/Http/Middleware/HandleInertiaRequests.php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\UserProfile;
use App\Models\Contribution;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => function () use ($request) {
                    if ($user = $request->user()) {
                        return array_merge($user->only('id', 'name', 'email'), [
                            'userProfile' => UserProfile::where('user_id', $user->id)->first(),
                            'contributions' => Contribution::where('user_id', $user->id)->get(),
                            'roles' => $user->getRoleNames(),
                            'permissions' => $user->getAllPermissions()->pluck('name'),
                        ]);
                    }
                    return null;
                },
            ],
        ]);
    }
}
