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

    // Get All users
    $users = DB::table('users')->get();

    // Get All users and only Email column
    $usersEmail = DB::table('users')->pluck('email');

    //Get Email Value from by name
    $user = DB::table('users')->where('name', 'Chelsey Grady')->value('email');

    // Get a single user by Id
    $user = DB::table('users')->find(1);

    //Get comments and  only content column
    $commentsContent = DB::table('comments')->select('content as comment_content')->get();

    //Get distinct user_id who comments
    $userIds = DB::table('comments')->select('user_id')->distinct()->get();

    // Get the max user_id who comments
    $result = DB::table('comments')->max('user_id');

    //Get the sum of userId who comments
    $result = DB::table('comments')->sum('user_id');

    //Check if comment content exists in DB return boolean
    $result = DB::table('comments')->where('content', 'content')->exists();
    $result = DB::table('comments')->where('content', 'content')->doesntExist();

    dump($usersEmail);

    return view('welcome');
});
