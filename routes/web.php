    <?php

    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\Auth\RegisteredUserController;
    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\ContributionController;
    use Illuminate\Foundation\Application;
    use Illuminate\Support\Facades\Route;
    use Inertia\Inertia;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\RoleController;


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

        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');


        // Check if the user is an admin


        // Protecting contribution routes with a permission

    });

    Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin',[AdminController::class,'index'])->name('admin.index');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/contributions', [ContributionController::class, 'contribution'])->name('contributions.index');


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



    require __DIR__.'/auth.php';
