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

    // Get number_of_comments and users_name order by number_of_comments
    // With selectRaw expressions
    $result = DB::table('comments')
            //->select(DB::raw('count(user_id) as number_of_comments, users.name'))
            ->selectRaw('count(user_id) as number_of_comments, users.name')
            ->join('users', 'users.id', '=' ,'comments.user_id')
            ->groupBy('user_id')
            ->orderByDesc('number_of_comments')
            ->get();
            //whereRaw / orWhereRaw
            //havingRaw / orHavingRaw
            //orderByRaw
            //groupByRaw

    //Get All comments order by updated_at (latest update)
    $result = DB::table('comments')
            ->orderByRaw('updated_at - created_at DESC')
            ->get();

    //Get All users order by name length count
    $result = DB::table('users')
        ->selectRaw('LENGTH(name) as name_length, name')
        ->orderByRaw('LENGTH(name) DESC')
        ->get();

    dd($result);
    return view('welcome');
});
