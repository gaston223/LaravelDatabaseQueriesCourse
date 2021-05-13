<?php

use App\User;
use Illuminate\Support\Facades\DB;
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

    //Get all reservations where rooms id and users id respect the given condition
    $result = DB::table('reservations')
        ->join('rooms', 'reservations.room_id', '=', 'rooms.id')
        ->join('users', 'reservations.user_id', '=', 'users.id')
        ->where('rooms.id', '>', 3)
        ->where('users.id', '>', 1)
        ->get();

    //@todo revision
    $result = DB::table('rooms')
        ->leftJoin('reservations', 'room_id', '=', 'reservations.room_id')
        ->leftJoin('cities', 'reservations.city_id', '=', 'cities.id')
        ->selectRaw('room_size, cities.name, count(reservations.id) as reservations_count')
        ->groupBy('room_size', 'cities.name')
        ->orderByRaw('count(reservations.id) DESC')
        ->get();

    // Get number of reservations for cities and each room size
    //Useful for generating reports
    $result = DB::table('rooms')
        ->crossJoin('cities')
        ->leftJoin('reservations', function($join){
            $join->on('rooms.id', '=', 'reservations.room_id')
            ->on('cities.id', '=', 'reservations.city_id');
        })
        ->selectRaw('count(reservations.id) as reservations_count, room_size, cities.name')
        ->groupBy('room_size', 'cities.name')
        ->orderByRaw('rooms.room_size DESC')
        ->get();

    dump($result);

    return view('welcome');
});
