<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post,
    App\Models\Website,
    App\Models\WebsiteUser;
use Response, Validator;

class WebsiteSubscribeController extends Controller
{
    public function index(Request $request) {
        // without Auth user working
        $auth_user_id = $request->input('auth_user_id');
        if (!$auth_user_id) return Response::json(['errorMsg' => 'User Id Required!'], 403);

        $posts = Post::whereHas('website', function($query) use ($auth_user_id) {
            $query->whereHas('subscribers', function($query) use ($auth_user_id) {
                $query->where('user_id', $auth_user_id);
            });
        })
        ->with('website:id,name,url');

        if ($request['search'])
            $posts = $posts->where('name', 'LIKE', '%'.$request['name'].'%');

        if ($request['user_id']) 
            $posts = $posts->where('user_id', $request['user_id']);

        if ($request['website_id'])
            $posts = $posts->where('website_id', $request['website_id']);
        
        $posts = $posts->get();

        return Response::json($posts, 200);
    }

    public function store(Request $request) {
        $validations = Validator::make($request->all(), [
            'user_id' => 'required',
            'website_id' => 'required'
        ]);
        if ($validations->fails())
            return Response::json(['errorMsg' => $validations->errors()->first()], 400);

        $check = WebsiteUser::where([
            [ 'user_id' => $request->user_id ], 
            [ 'website_id' => $request->website_id ]
        ])->first();
        if ($check) return Response::json(['errorMsg' => 'Website Already Subscribed!'], 208);

        // we can for valid user without auth, with Auth no need
        // we can check for valid website id

        $sub = new WebsiteUser;
        $sub->user_id = $request['user_id'];
        $sub->website_id = $request['website_id'];

        try {
            // WebsiteUser::insert($request->all());
            $sub->save();
            return Response::json(['success' => 'Website Subscribed!'], 201);
        } catch (\Exception $e) {
            return Response::json(['errorMsg' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id) {
        $validations = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validations->fails())
            return Response::json(['errorMsg' => $validations->errors()->first()], 400);

        $sub = WebsiteUser::where([
            [ 'user_id' => $request['user_id'] ],
            [ 'website_id' => $request['website_id'] ]
        ])->first();

        if (!$sub) return Response::json(['success' => 'Website Subscription Not Found!'], 200);

        try {
            $sub->delete();
            return Response::json(['success' => 'Website Unsubscribed!'], 200);
        } catch (\Exception $e) {
            return Response::json(['errorMsg' => $e->getMessage()], 500);
        }
    }
}
