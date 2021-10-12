<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $posts = Post::paginate(10);

            return response()->json([
                'message' => 'Success',
                'posts' => $posts
            ], 200);
        } catch (Throwable $throwable) {
            //Server side error
            return response()->json([
                'message' => $throwable
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  String  $request['title']
     * @param  String  $request['content']
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        //Validate new resource
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:1|max:50',
            'content' => 'required|string|min:1|max:500',
        ]);

        //When validation fails send response
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }

        //Get user from session
        $user = Auth::user();

        //Try to store validated data
        try {
            //Store a new post
            $post = Post::create([
                'user_id' => $user->id,
                'title' => $request['title'],
                'content' => $request['content']
            ]);

            //Successfull response
            return response()->json([
                'message' => 'New post created!',
                'postId' => $post->id
            ], 200);
        } catch (Throwable $throwable) {
            //Server side error
            return response()->json([
                'message' => $throwable
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            //Try to get specified resource
            $post = Post::where('id', $id)->firstOrFail();

            //Return found resource
            return response()->json([
                'message' => 'Resource found',
                'post' => $post,
                'user' => $post->user
            ], 200);
        } catch (Throwable $throwable) {
            //Server side error
            return response()->json([
                'message' => $throwable
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  String  $request['title']
     * @param  String  $request['content']
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        //Validate edited resource
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:1|max:50',
            'content' => 'required|string|min:1|max:500',
        ]);

        //When validation fails send response
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }

        //Get user from session
        $user = Auth::user();

        //Try to update resource
        try {
            //Get resource from database
            $post = Post::where('id', $id)->firstOrFail();
            //If user is owner of this resource
            if ($user->id == $post->user_id) {
                $post->update([
                    'title' => $request['title'],
                    'content' => $request['content']
                ]);

                return response()->json([
                    'message' => 'Post was updated.'
                ], 200);
            }
            //If user is not owner of this resource
            else {
                return response()->json([
                    'message' => 'This resource doesnt belong to this user.'
                ], 401);
            }
        } catch (Throwable $throwable) {
            //Server side error
            return response()->json([
                'message' => $throwable
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        //Get user from session
        $user = Auth::user();

        //Try to delete resource
        try {
            //Get resource from database
            $post = Post::where('id', $id)->firstOrFail();
            //If user is owner of this resource
            if ($user->id == $post->user_id) {
                $post->delete();

                return response()->json([
                    'message' => 'Post was deleted.'
                ], 200);
            }
            //If user is not owner of this resource
            else {
                return response()->json([
                    'message' => 'This resource doesnt belong to this user.'
                ], 401);
            }
        } catch (Throwable $throwable) {
            //Server side error
            return response()->json([
                'message' => $throwable
            ], 500);
        }
    }
}
