<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Validator;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(int $postId): JsonResponse
    {
        try {
            //Paginated data is ordered by newest date            
            $comments = DB::table('comments')->where('post_id', $postId)->orderBy('created_at', 'desc')->paginate(15);

            //Return data
            return response()->json([
                'message' => 'Success',
                'comments' => $comments
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
     * @param  int  $request['postId']
     * @param  String  $request['content']
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        //Validate new resource
        $validator = Validator::make($request->all(), [
            'postId' => 'required|integer|exists:posts,id',
            'content' => 'required|string|min:1|max:200',
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
            //Store a new comment
            Comment::create([
                'user_id' => $user->id,
                'post_id' => $request['postId'],
                'content' => $request['content']
            ]);

            //Successfull response
            return response()->json([
                'message' => 'New comment created!',
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
            $comment = Comment::where('id', $id)->firstOrFail();

            //Return found resource
            return response()->json([
                'message' => 'Resource found',
                'comment' => $comment,                
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
     * @param  String  $request['content']
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        //Validate edited resource
        $validator = Validator::make($request->all(), [
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
            $comment = Comment::where('id', $id)->firstOrFail();
            //If user is owner of this resource
            if ($user->id == $comment->user_id) {
                $comment->update([
                    'content' => $request['content']
                ]);

                return response()->json([
                    'message' => 'Comment was updated.'
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
            $comment = Comment::where('id', $id)->firstOrFail();
            //If user is owner of this resource
            if ($user->id == $comment->user_id) {
                $comment->delete();

                return response()->json([
                    'message' => 'Comment was deleted.'
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
