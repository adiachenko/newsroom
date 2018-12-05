<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Resources\CommentResource;
use App\Notifications\CommentCreated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $comments = Comment::with('author')->when(request('article'), function (Builder $query) {
            $query->where('article_id', request('article'));
        })->paginate(20);

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Comment::class);

        $this->validate($request, [
            'body' => 'required',
        ]);

        $comment = new Comment([
            'article_id' => $request->input('article_id'),
            'body' => $request->input('body'),
        ]);

        Auth::user()->comments()->save($comment);

        $comment->article->author->notify(
            new CommentCreated($comment->article, $comment)
        );

        return CommentResource::make($comment)->response()->setStatusCode(200);
    }
}
