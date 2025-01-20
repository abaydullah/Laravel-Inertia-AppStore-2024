<?php

namespace App\Http\Controllers\Admin;

use Abaydullah\GooglePlayScraper\Scraper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Scrape\StoreRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DeveloperResource;
use App\Models\App;
use App\Models\Category;
use App\Models\Developer;
use App\Models\Screenshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ScrapeController extends Controller
{
    public function index(Request $request)
    {
        $scraper = new Scraper();
        if ($request->has('searchQuery')){
        $apps = $scraper->getSearchApps($request->get('searchQuery'));
        }elseif($request->has('categoryQuery')){
            $apps = $scraper->getCategoryApps($request->get('categoryQuery')['google_name']);
        }elseif($request->has('developerQuery')){

            $apps = $scraper->getSearchApps($request->get('developerQuery')['name']);
        }else{
            $apps = [];
        }
        $collection = collect($apps);
        $filterApps = $collection->filter(function ($item) {
            return !App::where('app_id',$item['app_id'])->first();
        });
        $categories = CategoryResource::collection(Category::get());
        $developers = DeveloperResource::collection(Developer::get());

        return Inertia::render('Backend/Scrape/Index',['apps'=>$filterApps->values(),'categories'=>$categories,'developers'=>$developers]);
    }
    public function search(Request $request)
    {
 //

    }

    public function store(StoreRequest $request)
    {
        foreach ($request->data as $scrape){

            $check = App::where('app_id',$scrape['app_id'])->first();

            if (!$check){
                $checkSlug = App::where('slug', Str::slug($scrape['title']))->first();
                if ($checkSlug ) {
                    $slug = Str::slug($scrape['title']).$checkSlug->id;
                }else{
                    $slug = Str::slug($scrape['title']);
                }
                //icon
                if (isset($scrape['icon'])){

                $icon = file_get_contents($scrape['icon']);
                $icon_name = uniqid() . '.png';
                if (!Storage::disk('public')->exists('icons')) {
                    Storage::disk('public')->makeDirectory('icons');
                }
                $store = Storage::disk('public')->put('icons/' . $icon_name, $icon);
                }else{
                    $icon_name = null;
                }
                //
                $app = new App();
                $app->title = $scrape['title'];
                $app->app_id = $scrape['app_id'];
                $app->slug = $slug;
                $app->rating = $scrape['rating']??0;
                $app->url = $scrape['app_url'];
                $app->icon = $icon_name;
                $app->icon_url = $scrape['icon'];
                $app->save();


            }
        }

        return back()->with('success', 'App Scrape Successfully');
    }
}
