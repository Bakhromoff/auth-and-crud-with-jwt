<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $model = Post::all();
        return response()->json([
            'data' => $model
        ]);
    }

    public function show($id)
    {
        $model = Post::find($id);
        if($model) {
            return response()->json(['data' => $model]);
        }
        return response()->json(['errors' => ['not found']]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:30'],
            'body' => ['required', 'string', 'max:50'],
        ]);
        if($validator->fails()) {
            return response()->json($validator->messages());
        }
        $model = new Post;
        $model->title = $request->title;
        $model->body = $request->body;
        $model->user_id = auth()->user()->id;
        $model->save();
        return response()->json([
            'message' => "saved",
            'data' => $model
        ]);
    }

    public function update(Request $request, $id)
    {
        $model = Post::find($id);
        if($model) {
            $validator = Validator::make($request->all(), [
                'title' => ['required', 'string', 'max:30'],
                'body' => ['required', 'string', 'max:50'],
            ]);
            if($validator->fails()) {
                return response()->json($validator->messages());
            }
            $model->title = $request->title;
            $model->body = $request->body;
            $model->user_id = auth()->user()->id;
            $model->update();
            return response()->json([
                'message' => 'Successfully updated',
                'data' => $model
            ]);
        }
        return response()->json(['errors' => ['not found']]);
    }

    public function delete($id)
    {
        $model = Post::find($id);
        if($model) {
            $model->delete();
            return response()->json(['message' => 'Syccessfully deleted']);
        }
        return response()->json(['errors' =>['not found']]);
    }
}
