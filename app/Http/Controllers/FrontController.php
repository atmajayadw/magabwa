<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ArticleNews;
use App\Models\Author;
use App\Models\BannerAdvertisement;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    //
    public function index(){
        $categories = Category::all();

        $articles = ArticleNews::with(['category'])
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(3)
        ->get();

        $featured_articles = ArticleNews::with(['category'])
        ->where('is_featured', 'featured')
        ->inRandomOrder()
        ->latest()
        ->take(3)
        ->get();

        $authors = Author::all();

        $bannerads = BannerAdvertisement::where('is_active', 'active')
        ->where('type', 'banner')
        ->inRandomOrder()
        ->first();

        $entertainment_articles = ArticleNews::whereHas('category', function($query){
            $query->where('name', 'Entertainment');
        })
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(6)
        ->get();

        $entertainment__featured_articles = ArticleNews::whereHas('category', function($query){
            $query->where('name', 'Entertainment');
        })
        ->where('is_featured', 'featured')
        ->inRandomOrder()
        ->first();

        return view('front.index', compact('categories', 'articles', 'authors', 'featured_articles', 'bannerads', 'entertainment_articles', 'entertainment__featured_articles'));
    }

    public function category(Category $category){
        $categories = Category::all();

        $articles = ArticleNews::with(['category'])
        ->latest()
        ->get();

        return view('front.category', compact('category', 'categories', 'articles'));
    }

    public function author(Author $author){
        $authors = Author::all();

        $articles = ArticleNews::with(['author'])
        ->latest()
        ->get();

        $categories = Category::all();

        return view('front.author', compact('authors', 'author','articles', 'categories'));
    }

    public function search(Request $request){
                //
        $request->validate([
            'keyword' => ['required', 'string', 'max:255']
        ]);

        $categories = Category::all();

        $keyword = $request->keyword;

        $articles = ArticleNews::with(['author', 'category'])
        ->where('name', 'like', '%' . $keyword . '%' )->paginate(6);

        return view('front.search', compact('articles', 'keyword', 'categories'));
        
    }

}
