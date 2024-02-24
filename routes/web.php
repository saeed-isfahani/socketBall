<?php

use App\Events\PlayerJoinEvent;
use App\Events\PlayerRepositionEvent;
use App\Events\ResetGroundEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/socketball', function () {
    return view('playground');
});

Route::get('/user_joined', function (Request $request) {
    PlayerJoinEvent::dispatch($request->get('direction'), $request->get('x'), $request->get('y'));
    return response()->json(['success' => true]);
});

Route::get('/repostion', function (Request $request) {
    PlayerRepositionEvent::dispatch($request->get('id'), $request->get('x'), $request->get('y'));
    return response()->json(['success' => true]);
});

Route::get('/reset-ground', function (Request $request) {
    ResetGroundEvent::dispatch($request->get('action'));
    return response()->json(['success' => true]);
});
