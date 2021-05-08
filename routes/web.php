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

    //Add Fulltext index for search query
    //$result = DB::statement('ALTER TABLE comments ADD FULLTEXT fulltext_index(content)'); // innoDB - MySQL >= 5.6

    // Search query with Raw sql expressions
    $result = DB::table('comments')
        ->whereRaw("MATCH(content) AGAINST(? IN BOOLEAN MODE)", ['+repellendus -pariatur'])
        ->get();


    //Search query with Query Builder
    $query = 'voluptatum';
    $result = DB::table('comments')
                ->where("content", 'like', "%{$query}%")
                ->get();

    dd($result);
    return view('welcome');
});
