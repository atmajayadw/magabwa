<?php
use App\Models\ArticleNews;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//sample API
Route::get('/api/articles', function () {
    return response()->json(ArticleNews::all());
});

Route::get('/api/articles/featured', function () {
    return response()->json(
        ArticleNews::where('is_featured', 'featured')->get()
    );
});