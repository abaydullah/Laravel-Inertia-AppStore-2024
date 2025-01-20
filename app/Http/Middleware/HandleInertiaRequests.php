<?php

namespace App\Http\Middleware;

use App\Http\Resources\AppResource;
use App\Models\App;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Middleware;
use KnotsPHP\PublicIP\Finders\PublicIP;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'ip' => PublicIP::get(),
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'notification' => collect(Arr::only($request->session()->all(), ['success', 'warning', 'error']))->mapWithKeys(function ($notification, $key) {
                return ['type' => $key, 'body' => $notification];
            }),
            'csrf_token' => fn () => csrf_token(),
            'apps_categories' => Category::where('type','app')->get(),
            'games_categories' => Category::where('type','game')->get(),
            'top_rated_apps' => AppResource::collection(App::type('app')->with('developer')->orderBy('rating','desc')->take(5)->get()),
            'top_rated_games' => AppResource::collection(App::type('game')->orderBy('rating','desc')->take(5)->get()),
        ];
    }
}
