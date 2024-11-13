<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{


    public function showpostform(){
        return view('createpost');

    }

    public function viewpost(Post $post){
        $post['body'] = Str::markdown($post->body);
        return view('single-page', ['post' => $post]);
    }

    public function creatnewpost(Request $request)
{
    // Validate the request inputs
    $newfields = $request->validate([
        'title' => 'required|max:255',
        'body' => 'required',
        'media' => 'image|mimes:jpeg,png,jpg,gif,jfif|max:2048', // Validate single image
    ]);

    // Sanitize input data
    $newfields['title'] = strip_tags($newfields['title']);
    $newfields['body'] = strip_tags($newfields['body']);
    $newfields['user_id'] = auth()->id();

    // Handle media upload
    if ($request->hasFile('media')) {
        $file = $request->file('media');
        $filename = uniqid() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/PostsImage', $filename);
        $newfields['media'] = $filename;
    }

    $newpost = Post::create($newfields);
    return redirect("/post/{$newpost->id}")->with('success', 'New Post Created!');
}





    public function actuallyUpdated(Post $post, Request $request){
        $newData = $request->validate([
            'title' => 'required',
            'body' => 'required',

        ]);

        $newData['title'] = strip_tags($newData['title']);
        $newData['body'] = strip_tags($newData['body']);


        $post->update($newData);
        return redirect('/post/{$post->id}')->with('success', 'Successfully Updated!');

    }



    public function showEditForm(Post $post){
    return view('edit-post', ['post' => $post]);
}


    public function delete(Post $post){

    $post->delete();

    return redirect('/my-profile/' . auth()->user()->username)->with('success', 'Post successfully deleted.');
}


public function likepost(Request $request, $postId)
{
    $post = Post::findOrFail($postId);

    // Check if the user has already liked the post
    $like = Like::where('user_id', auth()->id())->where('post_id', $postId)->first();

    if ($like) {
        // User already liked the post; remove the like
        $like->delete();
        return response()->json(['success' => true, 'message' => 'Post unliked', 'likes_count' => $post->likes()->count()]);
    } else {
        // User has not liked the post; add the like
        $post->likes()->create(['user_id' => auth()->id()]);
        return response()->json(['success' => true, 'message' => 'Post liked', 'likes_count' => $post->likes()->count()]);
    }
}


public function addcomment(Request $request, $postId)
{
    $request->validate(['comments' => 'required|max:255']);

    $post = Post::findOrFail($postId);
    $comment = $post->comments()->create([
        'user_id' => auth()->id(),
        'comments' => $request->comments,
    ]);

    $allComments = $post->comments()->orderBy('created_at', 'desc')->get();
    $totalComments = $allComments->count();

    return response()->json([
        'success' => true,
        'user' => $comment->user->username,
        'comment' => $comment->comments,
        'avatar' => $comment->user->avatar,
        'created_at' => $comment->created_at->diffForHumans(),
        'comment_id' => $comment->id,
        'comments' => $allComments,
        'moreCommentsCount' => max(0, $totalComments - 1), // Adjust for the number of comments shown
    ]);
}



    public function showcomment(Request $request, $postId){
        $post = Post::findOrFail($postId);
        $comments = $post->comments()->orderBy('created_at', 'desc')->take(3)->get(); // Get the first 3 comments

        return view('your-view-name', compact('post', 'comments')); // Pass $post and $comments to the view


    }



}
