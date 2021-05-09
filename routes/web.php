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

    $result = DB::table('users')
            //->orderBy('name', 'desc')
            //->latest() // created_at default
            ->inRandomOrder() //->orderByRAw('RAND()')
            ->first();

    $result = DB::table('comments')
                ->selectRaw('count(id) as number_of_5stars_comments, rating')
                ->groupBy('rating')
                ->where('rating', '=', 5)
                ->get();

    $result = DB::table('comments')
                ->skip(5)
                ->take(5)
                ->get();

    // When clause will execute only if $roomId is not null
    $roomId = 1;
    $result = DB::table('reservations')
                ->when($roomId, function ($query, $roomId){
                    return $query->where('room_id', $roomId);
                })
                ->get();

    $sortBy = null;
    $result = DB::table('rooms')
                ->when($sortBy, function ($query, $sortBy){
                    return $query->orderByDesc($sortBy);
                }, function ($query) {
                    return $query->orderBy('price'); //asc
                })
            ->get();

    $result = DB::table('comments')->orderBy('id')->chunk(2, function ($comments){
        foreach ($comments as $comment)
        {
            if($comment->id === 23) return false;
        }
    });

    //Useful for administration tasks for save memory
    $result = DB::table('comments')->orderBy('id')->chunkById(5, function ($comments){
        foreach ($comments as $comment)
        {
            DB::table('comments')
                ->where('id' , $comment->id)
                ->update(['rating' => null]);
        }
    });

    dump($result);
    return view('welcome');
});
