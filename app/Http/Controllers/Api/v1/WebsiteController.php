<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Website;
use Response, Validator;

class WebsiteController extends Controller
{
    public function index(Request $request) {
        $websites = Website::select('id', 'name', 'url');

        if ($request['search'] != null)
         $websites = $websites->where('name', 'LIKE', '%'.$request['search'].'%');

        if ($request['qurl'])
            $websites = $websites->where('url', 'LIKE', '%'.$request['qurl'].'%');

        $websites = $websites->get();

        dd($websites);

        return Response::json($websites, 200);
    }

    public function store(Request $request) {
        $validations = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255|unique:websites,name',
            'url' => 'required|url'
        ], [
            'name.required' => 'name field required',
            'unique' => 'name already available',
            'name.min' => 'Minimum 3 characters required',
            'name.max' => 'Only 255 characters',
            'url.required' => 'url field required',
            'url.url' => 'must be a valid website url'
        ]);
        if ($validations->fails())
            return Response::json(['errorMsg' => $validations->errors()->first()], 400);

        $website = new Website;
        $website->name = $request['name'];
        $website->url = $request['url'];
        
        try {
            // Website::insert($request->all());
            $website->save();
            return Response::json(['success' => 'Website Added!'], 201);
        } catch (\Exception $e) {
            return Response::json(['errorMsg' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id) {
        $website = Website::find($id);
        if (!$website) return Response::json(['errorMsg' => 'Website Not Find!'], 404);

        $validations = Validator::make($request->all(), [
            'name' => 'min:3|max:255|unique:websites,name',
            'url' => 'url'
        ], [
            'unique' => 'name already available',
            'name.min' => 'Minimum 3 characters required',
            'name.max' => 'Only 255 characters',
            'url.url' => 'must be a valid website url'
        ]);
        if ($validations->fails())
            return Response::json(['errorMsg' => $validations->errors()->first()], 400);

        if ($request['name'])
            $website->name = $request['name'];

        if ($request['url'])
            $website->url = $request['url'];

        try {
            // Website::where('id', $id)->update($request->all());
            $website->save();
            return Response::json(['success' => 'Website '. $website->name .' Updated!'], 201);
        } catch (\Exception $e) {
            return Response::json(['errorMsg' => $e->getMessage()], 500);
        }
    }

    public function destroy($id) {
        $website = Website::find($id);
        if (!$website) return Response::json(['errorMsg' => 'Website Not Found!'], 404);

        try {
            // Website::where('id', $id)->delete();
            $website->delete();
            return Response::json(['success' => 'Website Deleted!'], 200);
        } catch (\Exception $e) {
            return Response::json(['errorMsg' => $e->getMessage()], 500);
        }
    }
}
