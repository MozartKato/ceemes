<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function getAllBlogs(){
        $blogs = Blog::all();

        return response()->json([
            'status' => 'success',
            'message' => 'get all blogs',
            'data' => $blogs
        ]);
    }

    public function createBlog(Request $request){
        $request->validate([
            'title' => ['required','string','max:255'],
            'slug' => ['required','unique:blogs,slug','string','max:255'],
            'content' => ['required'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        }

        $newBlog = Blog::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $request->content,
            'image' => $imagePath
        ]);


        return response()->json([
            'status' => 'success',
            'message' => 'Blog successfully added'
        ]);
    }

    public function updateBlog(Request $request, $id){

    $request->validate([
        'title' => ['string','max:255'],
        'slug' => ['string','max:255'],
        'content' => ['string'],
        'image' => 'file'
    ]);

    $blog = Blog::findOrFail($id);

    $blog->fill($request->only(['title', 'slug', 'content','image']));
    $blog->user_id = $request->user()->id;

    if ($blog->isDirty()) {
        $blog->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Blog updated successfully',
        ]);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'No changes detected'
    ], 400);
    }

    public function deleteBlog(Request $request,$id){
        $blog = Blog::findOrFail($id);
        $blog->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Blog successfully deleted'
        ]);
    }
}
