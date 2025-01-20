<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppResource;
use App\Http\Resources\DeveloperResource;
use App\Models\App;
use App\Models\Developer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeveloperController extends Controller
{
    public function index(Request $request)
    {
        $apps = App::latest()->cursorPaginate(24);
        if ($request->wantsJson()){
            return AppResource::collection($apps);
        }
        return Inertia::render('Frontend/Developer/Index', ['developers' => AppResource::collection($apps)]);
    }
    public function view(Developer $developer,Request $request)
    {
       $apps = $developer->apps()->latest()->simplePaginate(24);
            if ($request->wantsJson()){
                return AppResource::collection($apps);
            }
        return Inertia::render('Frontend/Developer/View', ['developer'=> $developer,'apps' => AppResource::collection($apps)]);
    }
}
