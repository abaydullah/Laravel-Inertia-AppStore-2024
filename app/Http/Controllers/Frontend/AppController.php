<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppResource;
use App\Models\App;
use App\Models\Review;
use App\Models\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use KnotsPHP\PublicIP\Finders\PublicIP;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;

class AppController extends Controller
{
    public function index(Request $request)
    {
        $apps = App::latest()->cursorPaginate(24);
        if ($request->wantsJson()) {
            return AppResource::collection($apps);
        }
        return Inertia::render('Frontend/App/Index', ['apps' => AppResource::collection($apps)]);
    }

    public function view($slug)
    {
        $app = new AppResource(App::with(['screenshots','versions','reviews','developer','category'])->withCount(['reviews',
            'reviews as rating_five'=> fn($query)=> $query->ratingFive(),
            'reviews as rating_four'=> fn($query)=> $query->ratingFour(),
            'reviews as rating_three'=> fn($query)=> $query->ratingThree(),
            'reviews as rating_two'=> fn($query)=> $query->ratingTwo(),
            'reviews as rating_one'=> fn($query)=> $query->ratingOne(),

            ])->withAvg('reviews as rating_avg','rating')->firstWhere('slug', $slug));

        $related_apps = AppResource::collection(App::latest()->where('category_id', $app->category_id)->whereNot('id',$app->id)->take(6)->get());
        $developer_apps = AppResource::collection(App::latest()->where('developer_id', $app->developer_id)->whereNot('id',$app->id)->take(6)->get());

        return Inertia::render('Frontend/App/View', ['app' => $app,'related_apps' => $related_apps,'developer_apps' => $developer_apps]);
    }

    public function store(Request $request)
    {
        $request->validate([
           'app_id' => 'required',
           'review' => 'required',
           'rating' => 'required',
        ]);
        $review = Review::create($request->only('app_id', 'review', 'rating','name')+['date'=> now(),'ip'=>PublicIP::get()]);
        return redirect()->back()->with('success', 'Review added successfully');
    }

    public function update(Request $request,$id)
    {
        $review = Review::find($id);
        $review = $review->update($request->only('app_id', 'review', 'rating','name')+['date'=> now(),'ip'=>PublicIP::get()]);
        return redirect()->back()->with('success', 'Review Updated successfully');
    }
    public function destroy($id)
    {
        $review = Review::find($id)->delete();
        return redirect()->back()->with('success', 'Review Delete successfully');
    }

    public function versions($slug)
    {
        $app = new AppResource(App::with(['screenshots','versions','reviews','developer','category'])->withCount(['reviews',
            'reviews as rating_five'=> fn($query)=> $query->ratingFive(),
            'reviews as rating_four'=> fn($query)=> $query->ratingFour(),
            'reviews as rating_three'=> fn($query)=> $query->ratingThree(),
            'reviews as rating_two'=> fn($query)=> $query->ratingTwo(),
            'reviews as rating_one'=> fn($query)=> $query->ratingOne(),

        ])->withAvg('reviews as rating_avg','rating')->firstWhere('slug', $slug));
        return Inertia::render('Frontend/App/Versions', ['app' => $app]);
    }

    public function version($slug, Version $version)
    {
        $app = new AppResource(App::with(['screenshots','versions','reviews','developer','category'])->withCount(['reviews',
            'reviews as rating_five'=> fn($query)=> $query->ratingFive(),
            'reviews as rating_four'=> fn($query)=> $query->ratingFour(),
            'reviews as rating_three'=> fn($query)=> $query->ratingThree(),
            'reviews as rating_two'=> fn($query)=> $query->ratingTwo(),
            'reviews as rating_one'=> fn($query)=> $query->ratingOne(),

        ])->withAvg('reviews as rating_avg','rating')->firstWhere('slug', $slug));
        return Inertia::render('Frontend/App/Version', ['app' => $app, 'version' => $version]);
    }

    public function download(App $app, Version $version)
    {

        if (Storage::disk('public')->exists($version->file)) {

            return redirect(Storage::disk('public')->url($version->file));
        }
        abort(404);
    }
}
