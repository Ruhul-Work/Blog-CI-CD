<?php
namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\CommentReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    //
    // public function addComment(Request $request)
    // {
    //     $request->validate([
    //         'comment' => 'required',
    //     ]);

    //     $user = User::find(Auth::id());
    //     $comment = new Comment();
    //     $comment->user_id = $user->id;
    //     $comment->blog_id = $request->blog_id;
    //     $comment->comment = $request->comment;
    //     $comment->save();

    //     return response()->json(['success' => true, 'message' => 'Comment added!']);
    // }

    public function addComment(Request $request)
    {
        $request->validate([
            'comment' => 'required',
            'blog_id' => 'required|exists:blogs,id',
        ]);

        $user = Auth::user();

        if (! $user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 403);
        }

        // Save the comment
        $comment          = new Comment();
        $comment->user_id = $user->id;
        $comment->blog_id = $request->blog_id;
        $comment->comment = $request->comment;
        $comment->save();

        // Increment the comments_count field in the blogs table
        $blog = Blog::findOrFail($request->blog_id);
        $blog->increment('comments_count');

        return response()->json(['success' => true, 'message' => 'Comment added!', 'comments_count' => $blog->comments_count]);
    }

    // public function reply(Request $request)
    // {
    //     $request->validate([
    //         'comment_id' => 'required|exists:comments,id',
    //         'reply'      => 'required',
    //     ]);

    //     $user = Auth::user();
    //     if (! $user) {
    //         return response()->json(['success' => false, 'message' => 'User not authenticated'], 403);
    //     }

    //     // Save the reply
    //     $reply             = new CommentReply();
    //     $reply->comment_id = $request->comment_id;
    //     $reply->user_id    = $user->id;
    //     $reply->reply      = $request->reply;
    //     $reply->save();

    //     return response()->json(['success' => true, 'message' => 'Reply added successfully!']);
    // }

    public function reply(Request $request)
{
    $request->validate([
        'comment_id' => 'required|exists:comments,id',
        'reply' => 'required',
    ]);

    $user = Auth::user();

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not authenticated'], 403);
    }

    // Get the parent comment
    $comment = Comment::findOrFail($request->comment_id);

    // Save the reply
    $reply = new CommentReply(); // Assuming `CommentReply` is your reply model
    $reply->comment_id = $request->comment_id;
    $reply->user_id = $user->id;
    $reply->reply = $request->reply;
    $reply->save();

    // Increment the comments_count field in the blogs table
    $blog = Blog::findOrFail($comment->blog_id);
    $blog->increment('comments_count');

    return response()->json(['success' => true, 'message' => 'Reply added successfully!', 'comments_count' => $blog->comments_count]);
}


    //like count method
    public function like($id)
    {
        $blog   = Blog::findOrFail($id);
        $userId = auth()->id();

        $likedBy = $blog->liked_by ? json_decode($blog->liked_by, true) : [];

        if (in_array($userId, $likedBy)) {
            $likedBy = array_diff($likedBy, [$userId]);
            $blog->update([
                'liked_by'    => json_encode($likedBy),
                'likes_count' => $blog->likes_count - 1,
            ]);

            return response()->json([
                'success'     => true,
                'action'      => 'unliked',
                'likes_count' => $blog->likes_count,
            ]);
        } else {
            $likedBy[] = $userId;
            $blog->update([
                'liked_by'    => json_encode($likedBy),
                'likes_count' => $blog->likes_count + 1,
            ]);

            return response()->json([
                'success'     => true,
                'action'      => 'liked',
                'likes_count' => $blog->likes_count,
            ]);
        }
    }

}
