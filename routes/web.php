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

    // Get rooms where price < 200
    $result = DB::table('rooms')->where('price', '<', 200)->get();

    //Get where with 2 conditions (AND)
    $result = DB::table('rooms')->where([['room_size', '3'], ['price', '<', '400']])->get();

    // Get where (OR)
    $result = DB::table('rooms')
        ->where('room_size', '3')
        ->orWhere('price', '<', '100')
        ->get();

    //Nested Get where query with where orWhere
    $result = DB::table('rooms')
        ->where('price', '<', '400')
        ->orWhere(function($query){
            $query->where('room_size', '>', '1')
                ->where('room_size', '<', '4');
        })->get();

    //Get data where between
    $result = DB::table('rooms')
                ->whereBetween('room_size', [1, 3])//whereNotBetween
                ->get();

    //Get data where id is not in given paramaters
    $result = DB::table('rooms')
            ->whereNotIn('id', [1,2,3])//whereIn
            ->get();
    //whereNull('column') whereNotNull
    //whereDate('created_at', '2021-05-13')
    //whereMonth('created_at', '5')
    //whereDay('created_at', '27')
    //whereYear('created_at', '2021')
    //whereTime('created_at', '=', '12:25:10')
    //whereColumn('column1', '>', 'column2')
    //whereColumn([
    //['first_name', '=', 'last_name'],
    //['updated_at', '>', 'created_at']
    //])

    $result = DB::table('users')
            ->whereExists(function ($query){
                $query->select('id')
                    ->from('reservations')
                    ->whereRaw('reservations.user_id = users.id')
                    ->where('check_in', '=', '2021-04-25')
                    ->limit(1);
            })->get();

    $result = DB::table('users')
            ->whereJsonContains('meta->skills', 'PHP 7')
            //->where('meta->settings->site_language', 'fr')
            ->get();

    dump($result);

    return view('welcome');
});
