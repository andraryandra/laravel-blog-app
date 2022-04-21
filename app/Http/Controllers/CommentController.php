<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    // get all comments of a post
    public function index($id)
    {
        $post = Post::find($id);
        
        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 404);
        }

        return response([
            'post' => $post->comments()->with('user:id,name,image')->get()
        ], 200);
    }

    //create a comment
    public function store(Request $request, $id)
    {
        $post = Post::find($id);
        
        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 404);
        }

        //validate fields
        $attrs = $request->validate([
            'comment' => 'required|string',
        ]);

        Comment::create([
            'comment' => $attrs['comment'],
            'post_id' => $id,
            'user_id' => auth()->user()->id
        ]);

        return response([
            'message' => 'Comment Created.'
        ], 200);
    }

    //update a comment
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        
        if(!$comment)
        {
            return response([
                'message' => 'Comment not found.'
            ], 404);
        }

        if($comment->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 404);
        }

        //validate fields
        $attrs = $request->validate([
            'comment' => 'required|string',
        ]);

        $comment->update([
            'comment' => $attrs['comment']
        ]);

        return response([
            'message' => 'Comment Updated.'
        ], 200);
    }

    //delete a comment
    public function destroy($id)
    {
        $comment = Comment::find($id);
        
        if(!$comment)
        {
            return response([
                'message' => 'Comment not found.'
            ], 404);
        }

        if($comment->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 404);
        }
        
        $comment->delete();
        return response([
            'message' => 'Comment Deleted.'
        ]);
    }
}
