<?php

namespace App\Http\Controllers;

use DB;
use Throwable;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PostAndUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(int $userId): JsonResponse
    {
        try {
            //Paginated data is ordered by newest date                        
            $posts = DB::table('posts')->where('user_id', $userId)->orderBy('created_at', 'desc')->paginate(10);

            //Return data
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
