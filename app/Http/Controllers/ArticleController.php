<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Resources\ArticleResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $artiles = Article::with('author')->when(request('search'), function (Builder $query) {
            // Only search at the beginning of the string to avail of the index on the title column
            // Most robust implementation would require full-text search like Algolia or ElasticSearch
            $query->where('title', 'like', request('search').'%');
        })->when(request('start_date'), function (Builder $query) {
            $query->where('created_at', '>=', Carbon::parse(request('start_date')));
        })->when(request('end_date'), function (Builder $query) {
            $query->where('created_at', '<=', Carbon::parse(request('end_date')));
        })->paginate(20);

        return ArticleResource::collection($artiles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Article::class);

        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);

        $article = new Article($request->only('title', 'body'));

        Auth::user()->articles()->save($article);

        return ArticleResource::make($article)
            ->response()->setStatusCode(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ArticleResource::make(Article::with('author')->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $this->authorize('update', $article);

        $article->update($request->only('title', 'body'));

        return ArticleResource::make($article)->response()->setStatusCode(200);
    }
}
