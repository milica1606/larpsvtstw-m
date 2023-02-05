<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $address = null;
    if (isset($_GET['cl'])) {
        $address = $_GET['cl'];
    }
    return view('home', ['url' => $address]);
});

Route::get('email/verify/{id}/{hash}', [App\Http\Controllers\AuthEmailVerificationController::class, 'verify'])
    ->middleware([/*'auth',*/ 'signed'])->name('verification.verify');

//redirect to home page on client routes directly from browser
Route::get('/role/{role?}', function ($role) {
    return redirect("/?cl=role%2F$role");
});

Route::get('/{other?}', function ($other) {
    return redirect("/?cl=$other");
});
