<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeveloperResource;
use App\Models\Developer;
use Illuminate\Http\Request;

class DeveloperController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search')??null;
        $type = $request->input('type')??'';
        $status = $request->input('status')??'';
        $query = Developer::query();
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
        $developers = DeveloperResource::collection($query);
        return inertia()->render('Backend/Developer/Index', compact('developers','search','type','status'));
    }

    public function store(StoreRequest $request)
    {
        Developer::create($request->only('name','google_name','google_url','slug','type')+['status' => (bool)$request->status]);

        return back()->with('success', 'Developer Created Successfully');
    }

    public function update($id,UpdateRequest $request)
    {
        $developer = Developer::find($id);
        $developer->update($request->only('name','google_name','google_url','slug','type','status'));

        return back()->with('success', 'Developer Update Successfully');
    }
    public function destroy($id,Request $request)
    {
        $developer = Developer::find($id);
        if ($developer->apps){
            return back()->with('warning', "This Developer Can't Delete");
        }
        $developer->delete();

        return back()->with('success', 'Developer Delete Successfully');
    }
}
