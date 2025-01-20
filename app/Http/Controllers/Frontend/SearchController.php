<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppResource;
use App\Models\App;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function search(Request $request)
    {

        $query = App::query();
        if ($request->q){
            $query->where('title','LIKE','%'.$request->q.'%');
        }
        $apps = AppResource::collection($query->simplePaginate(1000)->withQueryString());
        return Inertia::render('Frontend/Search/Index',['apps'=>$apps]);
    }


}
