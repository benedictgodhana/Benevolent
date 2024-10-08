    <?php

    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\ContributionController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Foundation\Application;
    use Illuminate\Support\Facades\Route;
    use Inertia\Inertia;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\RoleController;
    use App\Http\Controllers\memberController;



    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application.
    |
    */

    Route::get('/', function () {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    });

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');


        // Check if the user is an admin


        // Protecting contribution routes with a permission

    });

    Route::middleware(['auth', 'role:member'])->group(function () {



        Route::get('/contribution', [memberController::class, 'contribution'])->name('contribution');
        Route::get('/notifications', [memberController::class, 'notifications'])->name('notifications');
        Route::get('/settings', [memberController::class, 'settings'])->name('settings');
        Route::post('/user/profile-pic', [UserController::class, 'updateProfilePic'])->name('user.profile-pic');

        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');


    });

    Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin',[AdminController::class,'index'])->name('admin.dashboard');

    Route::get('/profile',[AdminController::class,'adminProfile'])->name('profile');

    Route::get('/my_contribution',[AdminController::class,'adminContribution'])->name('admincontribution');

    Route::get('/my_notifications',[AdminController::class,'adminNotification'])->name('adminnotification');

    Route::get('/my_settings',[AdminController::class,'adminSetting'])->name('adminsettings');

    Route::post('/contributions/import', [ContributionController::class, 'import']);


    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/contributions', [ContributionController::class, 'contribution'])->name('contributions.index');

    Route::get('/expenses', [ExpenseController::class, 'expense'])->name('expense.index');
    Route::post('/expenses', [ExpenseController::class, 'store']);
    Route::post('/expenses/import', [ExpenseController::class, 'import'])->name('expenses.import');



    Route::get('/manage-roles', [RoleController::class, 'index'])->name('roles.index');


    Route::get('/graph', [AdminController::class, 'graph']);

        // Route::get('/users', function () {
        //     return Inertia::render('Users');
        // })->name('users');

        // Route::get('/manage-roles', function () {
        //     return Inertia::render('ManageRoles');
        // })->name('manage-roles');

    // Route::get('/contributions', function () {
    //     return Inertia::render('Contributions');
    // });

    // Default dashboard for authenticated users

    });

    // Routes for additional pages
    Route::get('/about', function () {
        return Inertia::render('About');
    })->name('about');

    Route::get('/contact', function () {
        return Inertia::render('Contact');
    })->name('contact');

    Route::get('/gallery', function () {
        return Inertia::render('Gallery');
    })->name('gallery');

    Route::get('/updates', function () {
        return Inertia::render('Updates');
    })->name('updates');

    // Registration routes
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
// web.php or api.php
    Route::post('/check-email', [AuthController::class, 'checkEmail'])->name('check-email');



    require __DIR__.'/auth.php';
