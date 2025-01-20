<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search')??null;
        $type = $request->input('type')??'';
        $status = $request->input('status')??'';
        $query = Category::query();
        if (!is_null($search)&&$search !== '') {
            $query = $query->where('name','like','%'.$search.'%');
        }
        if (!is_null($type)&&$type !== '') {
            $query = $query->where('type',$type);
        }
        if (!is_null($status) && $status!== '') {
            $query = $query->where('status',$status);
        }
        $query =  $query->latest()->paginate(10)->withQueryString();
        $categories = CategoryResource::collection($query);
        return inertia()->render('Backend/Category/Index', compact('categories','search','type','status'));
    }

    public function store(StoreRequest $request)
    {
        Category::create($request->only('name','google_name','google_url','slug','type')+['status' => (bool)$request->status]);

        return back()->with('success', 'Category Created Successfully');
    }

    public function update($id,UpdateRequest $request)
    {
        $category = Category::find($id);
        $category->update($request->only('name','google_name','google_url','slug','type','status'));

        return back()->with('success', 'Category Update Successfully');
    }
    public function destroy($id,Request $request)
    {
        $category = Category::find($id);
        $category->delete();

        return back()->with('success', 'Category Delete Successfully');
    }
}
