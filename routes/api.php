    <?php

    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\ReferentielController;
    use App\Http\Controllers\UserController;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;

    /*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider and all of them will
    | be assigned to the "api" middleware group. Make something great!
    |
    */

    Route::prefix('v1')->group(function () {
        Route::post('/auth/login', [AuthController::class, 'login']);
    });



    if(env('AUTH_CONFIG') == 'passport') {
        Route::group(['middleware' => ['auth:api', 'role:1,2'], 'prefix' => 'v1'],function () {

            //Managed User
            Route::post('/users', [UserController::class, 'store']);


            //Managed Referentiel
            Route::post('/referentiels', [ReferentielController::class, 'store']);
            Route::get('/referentiels', [ReferentielController::class, 'index']);
            Route::get('/referentiels/{id}', [ReferentielController::class, 'findByuid']);
            Route::patch('/referentiels/{id}', [ReferentielController::class, 'update']);
            Route::delete('/referentiels/{id}', [ReferentielController::class, 'softDelete']);
            Route::get('/archive/referentiels', [ReferentielController::class, 'archive']);
        });

        //only amdin role with passport
        Route::group(['middleware' => ['auth:api', 'role:1'], 'prefix' => 'v1'],function () {
            Route::get('/users', [UserController::class, 'index']);
            Route::get('/users/export/excel', [UserController::class, 'exportWithExcel']);
            Route::get('/users/export/pdf', [UserController::class, 'exportWithPdf']);
            Route::patch('/users/{id}', [UserController::class, 'update']);
            //Route::delete('/users/{id}', [UserController::class, 'destroy']);
        });
    }else if(env('AUTH_CONFIG') == 'firebase') {
        Route::group(['middleware' => ['firebase.auth', 'role:ADMIN,MANAGER'], 'prefix' => 'v1'],function () {
            //dd("Je suis dans firebase");
            Route::post('/users', [UserController::class, 'store']);

            //Managed Referentiel
            Route::post('/referentiels', [ReferentielController::class, 'store']);
            Route::get('/referentiels', [ReferentielController::class, 'index']);
            Route::get('/referentiels/{id}', [ReferentielController::class, 'findByuid']);
            Route::patch('/referentiels/{id}', [ReferentielController::class, 'update']);
            Route::delete('/referentiels/{id}', [ReferentielController::class, 'softDelete']);
            Route::get('/archive/referentiels', [ReferentielController::class, 'archive']);
        });

        //only amdin role with firebase

        Route::group(['middleware' => ['firebase.auth', 'role:ADMIN'], 'prefix' => 'v1'],function () {
            Route::get('/users', [UserController::class, 'index']);
            Route::get('/users/export/excel', [UserController::class, 'exportWithExcel']);
            Route::get('/users/export/pdf', [UserController::class, 'exportWithPdf']);
            Route::patch('/users/{id}', [UserController::class, 'update']);
            //Route::delete('/users/{id}', [UserController::class, 'destroy']);
        });
    }


    /*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });*/
