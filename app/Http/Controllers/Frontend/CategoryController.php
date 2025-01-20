<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppResource;
use App\Models\App;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;


class CategoryController extends Controller
{
    public function index()
    {

        return Inertia::render("Category/Index");
    }

    public function view(Request $request,Category $category)
    {
        $apps = $category->apps()->latest()->simplePaginate(40);
        if ($request->wantsJson()){
            return AppResource::collection($apps);
        }
        return Inertia::render("Frontend/Category/View",['category' => $category, 'apps' => AppResource::collection($apps)]);
    }
}
