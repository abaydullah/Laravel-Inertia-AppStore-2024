<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppResource;
use App\Models\App;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $latest_apps = AppResource::collection(App::type('app')->latest()->take(10)->get());
        $latest_games = AppResource::collection(App::type('game')->latest()->take(10)->get());
        $popular_apps = AppResource::collection(App::type('app')->latest()->with('screenshots')->orderBy('rating','desc')->take(5)->get());
        $popular_games = AppResource::collection(App::type('game')->latest()->with('screenshots')->orderBy('rating','desc')->take(5)->get());
        $hot_apps = AppResource::collection(App::type('app')->orderBy('rating','desc')->orderBy('downloads','desc')->take(5)->get());
        $hot_games = AppResource::collection(App::type('game')->orderBy('rating','desc')->orderBy('downloads','desc')->take(5)->get());
        $top_rated_apps = AppResource::collection(App::type('app')->orderBy('rating','desc')->take(5)->get());
        $top_rated_games = AppResource::collection(App::type('game')->orderBy('rating','desc')->take(5)->get());
        return Inertia::render('Frontend/Home/Index', ['latest_apps' => $latest_apps,'latest_games' => $latest_games,
            'popular_apps' => $popular_apps,'popular_games' => $popular_games,
            'hot_apps' => $hot_apps,'hot_games' => $hot_games,'top_rated_apps' => $top_rated_apps,'top_rated_games' => $top_rated_games]);
    }

}
