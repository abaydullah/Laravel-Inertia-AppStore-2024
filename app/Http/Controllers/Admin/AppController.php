<?php

namespace App\Http\Controllers\Admin;

use Abaydullah\GooglePlayScraper\Scraper;
use App\Http\Controllers\Controller;
use App\Http\Resources\AppResource;
use App\Models\App;
use App\Models\Category;
use App\Models\Developer;
use App\Models\Screenshot;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AppController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search') ?? null;
        $type = $request->input('type') ?? '';
        $status = $request->input('status') ?? '';
        $app = $request->input('app_id') ?? null;
        $sort = $request->input('sort') ?? 'date_created_desc';
        $query = App::query();

        if ($app !== null){
            $scraper = new Scraper();
            $app = $scraper->getApp($app);
            if (App::where('app_id', $app['app_id'])->exists()){
                $app['status'] = 'This '.$app['type'].' Already Exists !';
            }
        }

        if ($sort === 'date_created_desc' ||$sort === 'date_desc' || $sort === 'rating_desc' || $sort === 'desc' || $sort === 'download_desc') {
            switch ($sort) {
                case 'date_created_desc':
                    $sortBy = 'created_at';
                    break;
                case 'date_desc':
                    $sortBy = 'updated_at';
                    break;
                case 'rating_desc':
                    $sortBy = 'rating';
                    break;
                case 'desc':
                    $sortBy = 'title';
                    break;
                case 'download_desc':
                    $sortBy = 'downloads';
                    break;
                default;
                $sortBy = 'created_at';
            }

            $query = $query->orderByDesc($sortBy);
        }

        if ($sort === 'date_created_asc' || $sort === 'date_asc' || $sort === 'rating_asc' || $sort === 'asc' || $sort === 'download_asc') {
            switch ($sort) {
                case 'date_created_asc':
                    $sortBy = 'created_at';
                    break;
                case 'date_asc':
                    $sortBy = 'updated_at';
                    break;
                case 'rating_asc':
                    $sortBy = 'rating';
                    break;
                case 'asc':
                    $sortBy = 'title';
                    break;
                case 'download_asc':
                    $sortBy = 'downloads';
                    break;
                default;
                    $sortBy = 'created_at';
            }
            $query = $query->orderBy($sortBy);
        }
        if (!is_null($search) && $search !== '') {
            $query = $query->where('title', 'like', '%' . $search . '%');
        }
        if (!is_null($type) && $type !== '') {
            $query = $query->where('type', $type);
        }
        if (!is_null($status) && $status !== '') {
            $query = $query->where('status', $status);
        }
        $query = $query->with(['developer','category'])->paginate(10);


        $categories = Category::all();
        $developers = Developer::all();
        $apps = AppResource::collection($query->withQueryString());
        return inertia()->render('Backend/App/Index', ['app' => $app,'apps' => $apps, 'categories' => $categories, 'developers' => $developers, 'search' => $search, 'status' => $status, 'type' => $type, 'sort' => $sort]);
    }
    public function scrape(Request $request)
    {

        $scrape = $request->app;
        $check = App::where('app_id', $request->app['app_id'])->first();

        if (!$check){
            $checkSlug = App::where('slug', Str::slug($scrape['title']))->first();
            if ($checkSlug ) {
                $slug = Str::slug($scrape['title']).$checkSlug->id;
            }else{
                $slug = Str::slug($scrape['title']);
            }
            //icon
            $icon = file_get_contents($scrape['icon']);
            $icon_name = uniqid() . '.png';
            if (!Storage::disk('public')->exists('icons')) {
                Storage::disk('public')->makeDirectory('icons');

            }
            $store = Storage::disk('public')->put('icons/' . $icon_name, $icon);
            //
            //cover image


            if (isset($scrape['cover_image'])){
                $cover = file_get_contents($scrape['cover_image']);
                $cover_name = uniqid() . '.png';
                if (!Storage::disk('public')->exists('cover')) {
                    Storage::disk('public')->makeDirectory('cover');

                }
                $store = Storage::disk('public')->put('cover/' . $cover_name, $cover);
            }


            //
            $app = new App();
            $app->title = $scrape['title'];
            $app->app_id = $scrape['app_id'];
            $app->slug = $slug;
            $app->rating = $scrape['rating'];
            $app->url = $scrape['url'];
            $app->downloads = $scrape['downloads'];
            $app->review = $scrape['review'];
            $app->histograms = json_encode($scrape['histograms']);
            $app->paid = $scrape['paid']??false;
            $app->icon = $icon_name;
            $app->icon_url = $scrape['icon'];
            $app->icon_original = $scrape['icon_original'];
            $app->cover_image_url = $scrape['cover_image'];
            $app->cover_image = $cover_name??null;
            $app->updated = date('Y-m-d', strtotime($scrape['updated']));
            $app->trailer = $scrape['trailer'];
            $app->whats_new = $scrape['whats_new'];
            $app->meta_description = $scrape['meta_description'];
            $app->description = $scrape['description'];
            $app->type = $scrape['type'];
                $developer = Developer::where('google_url', $scrape['developer_url'])->first();
                if ($developer) {
                    $app->developer_id = $developer->id;
                } else {
                    $new_dev = Developer::create([
                        'name' => $scrape['developer_name'],
                        'slug' => Str::slug($scrape['developer_name']),
                        'google_url' => $scrape['developer_url'],
                    ]);
                    $app->developer_id = $new_dev->id;
                }
                $category = Category::where('google_name', $scrape['category_slug'])->first();
                if ($category) {
                    $app->category_id = $category->id;
                } else {
                    $new_cat = Category::create([
                        'name' => $scrape['category_name'],
                        'google_name' => Str::slug($scrape['category_slug']),
                        'slug' => Str::slug($scrape['category_name']),
                        'google_url' => $scrape['category_url'],
                        'type' => $scrape['type'],
                    ]);
                    $app->category_id = $new_cat->id;
                }

            $app->save();

            if (count($scrape['screenshots']) > 0) {
                foreach ($scrape['screenshots'] as $screenshot) {
                    $photoCheck = Screenshot::where('photo_url', $screenshot)->first();

                    if (!$photoCheck) {
                        $image = file_get_contents($screenshot);
                        $image_name = $app->slug . uniqid() . '.png';
                        if (!Storage::disk('public')->exists('screenshots')) {
                            Storage::disk('public')->makeDirectory('screenshots');

                        }
                        $store = Storage::disk('public')->put('screenshots/' . $image_name, $image);
                        Screenshot::create(
                            [
                                'app_id' => $app->id,
                                'photo_url' => $screenshot,
                                'photo' => $image_name
                            ]
                        );
                    }
                }

            }

            return back()->with('success', 'App Uploaded successfully');
        }else{
            return back()->with('error', 'App Already Exists');
        }




    }
    public function sync(Request $request)
    {


        $app = App::find($request->app['id']);
        $scraper = new Scraper();
        $scrape = $scraper->getApp($request->app['app_id']);
        if (!isset($app->developer)) {
            $developer = Developer::where('google_url', $scrape['developer_url'])->first();
            if ($developer) {
                $app->developer_id = $developer->id;
            } else {
                $new_dev = Developer::create([
                    'name' => $scrape['developer_name'],
                    'slug' => Str::slug($scrape['developer_name']),
                    'google_url' => $scrape['developer_url'],
                ]);
                $app->developer_id = $new_dev->id;
            }
        }
        if (!isset($app->category)) {
            $category = Category::where('google_name', $scrape['category_slug'])->first();
            if ($category) {
                $app->category_id = $category->id;
            } else {
                $new_cat = Category::create([
                    'name' => $scrape['category_name'],
                    'google_name' => Str::slug($scrape['category_slug']),
                    'slug' => Str::slug($scrape['category_name']),
                    'google_url' => $scrape['category_url'],
                    'type' => $scrape['type'],
                ]);
                $app->category_id = $new_cat->id;
            }
        }


        if (!Storage::disk('public')->exists('icons')) {
            Storage::disk('public')->makeDirectory('icons');

        }

        if (!isset($app->icon) || !Storage::disk('public')->exists('icons/'.$app->icon)) {

            $icon = file_get_contents($scrape['icon']);
            $icon_name = uniqid() . '.png';
            $store = Storage::disk('public')->put('icons/' . $icon_name, $icon);
            $app->icon = $icon_name;
        }
        //cover image

        if (!Storage::disk('public')->exists('cover/'.$app->cover_image)) {
            if (isset($scrape['cover_image'])&& $scrape['cover_image'] !== '') {
                $cover = file_get_contents($scrape['cover_image']);
                $cover_name = uniqid() . '.png';
                if (!Storage::disk('public')->exists('cover')) {
                    Storage::disk('public')->makeDirectory('cover');

                }
                $store = Storage::disk('public')->put('cover/' . $cover_name, $cover);
                $app->cover_image = $cover_name;
            }
        }


        if ($app->screenshots->count() !== count($scrape['screenshots'])) {
            foreach ($scrape['screenshots'] as $screenshot) {
                $photoCheck = Screenshot::where('photo_url', $screenshot)->first();

                if (!$photoCheck) {
                    $image = file_get_contents($screenshot);
                    $image_name = $app->slug . uniqid() . '.png';
                    if (!Storage::disk('public')->exists('screenshots')) {
                        Storage::disk('public')->makeDirectory('screenshots');

                    }
                    $store = Storage::disk('public')->put('screenshots/' . $image_name, $image);
                    Screenshot::create(
                        [
                            'app_id' => $app->id,
                            'photo_url' => $screenshot,
                            'photo' => $image_name
                        ]
                    );
                }
            }

        }
        $app->title = $scrape['title'];
        $app->rating = $scrape['rating'];
        $app->url = $scrape['url'];
        $app->downloads = $scrape['downloads'];
        $app->review = $scrape['review'];
        $app->histograms = $scrape['histograms'];
        $app->paid = $scrape['paid'];

        $app->icon_url = $scrape['icon'];
        $app->icon_original = $scrape['icon_original'];
        $app->cover_image_url = $scrape['cover_image'];
        $app->updated = date('Y-m-d', strtotime($scrape['updated']));
        $app->trailer = $scrape['trailer'];
        $app->whats_new = $scrape['whats_new'];
        $app->meta_description = $scrape['meta_description'];
        $app->description = $scrape['description'];
        $app->type = $scrape['type'];
        $app->update();


        return back()->with('success', 'App Updated Successfully');
    }

    public function show($id)
    {
        $app = AppResource::make(App::with('category','developer','screenshots')->findOrFail($id));
        return Inertia::render('Backend/App/View', ['app' => $app]);
    }

    public function destroy($id)
    {
        $app = App::find($id);

        if (Storage::disk('public')->exists('icons/' . $app->icon)) {

            Storage::disk('public')->delete('icons/' . $app->icon);
        }
        if ($app->screenshots->count() > 0) {
            foreach ($app->screenshots as $screenshot) {
                if (Storage::disk('public')->exists('screenshots/' . $screenshot->photo)) {
                    Storage::disk('public')->delete('screenshots/' . $screenshot->photo);
                }
                $screenshot->delete();
            }

        }
        if ($app->versions->count() > 0) {
            foreach ($app->versions as $version) {
                if (Storage::disk('public')->exists($version->file)) {
                    Storage::disk('public')->delete($version->file);
                }
                $version->delete();
            }

        }
        if ($app->versions->count() > 0){
            if (!Storage::disk('public')->exists($app->app_id)) {
                Storage::disk('public')->deleteDirectory($app->app_id);

            }
              }

       $app->delete();
        return back()->with('success', 'App Deleted Successfully');
    }
}
