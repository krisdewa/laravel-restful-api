<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * index
     * 
     * @return void
     */
    public function index()
    {
        $posts = Post::latest()->paginate(5);
        return new PostResource(true, 'List data Posts', $posts);
    }

    /**
     * store
     * 
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors()], 422);
        }

        // upload image
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $image->hashName(),
        ]);

        return new PostResource(true, 'Data Post Berhasil Ditambahkan !', $post);
    }

    /**
     * show
     * 
     * @param  mixed $post
     * @return void
     */
    public function show(Post $post)
    {
        return new PostResource(true, 'Data Post Ditemukan !', $post);
    }

    /**
     * update
     * 
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors()], 422);
        }

        if ($request->file('image')) {
            // upload image
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            // delete old image
            Storage::delete('public/posts/' . $post->image);

            // update data with new image
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $image->hashName(),
            ]);
        } else {
            // update data without image
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);
        }

        return new PostResource(true, 'Data Post Berhasil Diupdate !', $post);
    }
}
