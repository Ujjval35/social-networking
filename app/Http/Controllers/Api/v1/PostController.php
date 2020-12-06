<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post,
    App\Models\Website;
use Response, Validator;

class PostController extends Controller
{
    public function index(Request $request) {
        $posts = Post::with('website:id,name,url');

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
            'name' => 'required|min:3|max:255',
            'description' => 'required|min:10',
            'user_id' => 'required',
            'website_id' => 'required'
        ]);
        if ($validations->fails())
            return Response::json(['errorMsg' => $validations->errors()->first()], 400);

        // we can check user id is valid
        // we can check website id is valid

        $post = new Post;
        $post->user_id = $request->user_id;
        $post->website_id = $request->website_id;
        $post->name = $request['name'];
        $post->description = $request['description'];

        try {
            // Post::insert($request->all());
            $post->save();
            return Response::json(['success' => 'Post Created!'], 201);
        } catch (\Exception $e) {
            return Response::json(['errorMsg' => $e->getMessage()], 500);
        }
    }

    public function show($id) {
        $post = Post::with('website')->find($id);
        if (!$post) return Response::json(['errorMsg' => 'Post Not Found!'], 404);
        return Response::json($post, 200);
    }

    public function update(Request $request, $id) {
        $post = Post::find($id);
        if (!$post) return Response::json(['errorMsg' => 'Post Not Found!'], 404);

        $validations = Validator::make($request->all(), [
            'name' => 'min:3|max:255',
            'description' => 'min:10',
        ]);
        if ($validations->fails())
            return Response::json(['errorMsg' => $validations->errors()->first()], 400);

        if ($request['name'])
            $post->name = $request['name'];

        if ($request['description']) 
            $post->description = $request['description'];
        
        try {
            // Post::where('id', $id)->update($request->only('name', 'description'));
            $post->save();
            return Response::json(['success' => 'Post Updated!'], 201);
        } catch (\Exception $e) {
            return Response::json(['errorMsg' => $e->getMessage()], 500);
        }
    }

    public function destroy($id) {
        $post = Post::find($id);
        if (!$post) return Response::json(['errorMsg' => 'Post Not Found!'], 404);

        // we can match auth with post user and send error 403

        try {
            // Post::where('id', $id)->delete();
            $post->delete();
            return Response::json(['success' => 'Post Deleted!'], 200);
        } catch (\Exception $e) {
            return Response::json(['errorMsg' => $e->getMessage()], 500);
        }
    }
}
