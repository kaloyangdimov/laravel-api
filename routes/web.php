<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiServices\BlizzRequestService;

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
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::group(['middleware' => ['auth']], function () {
    //admin routes
    Route::get('/users', 'UserController@list')->name('users.list')->middleware('admin');

    //user accessible routes
    Route::get('/language/switch/{lang}', 'LocaleController@switchLanguage')->name('switch.language');
    Route::get('/user/view/{user}', 'UserController@show')->name('user.show');
    Route::match(['get', 'post'], '/user/update/{user}', 'UserController@update')->name('user.update');
    Route::match(['get', 'post'], '/user/delete/{user}', 'UserController@destroy')->name('user.delete');
    Route::match(['get', 'post'], '/authAccess', 'WarcraftController@authorizeAccess');
    Route::match(['get', 'post'], '/createtoken', 'WarcraftController@createToken');
    Route::match(['get', 'post'], '/getProfileData', 'WarcraftController@getProfile');
    Route::match(['get', 'post'], '/view/char/{realmID}/{charID}', 'WarcraftController@getCharacterData')->name('view.char');
    Route::match(['get', 'post'], '/view/char/achievments/{realmSlug}/{characterName}', 'WarcraftController@getCharacterAchievments')->name('view.char.achievments');
});
