<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Typehint Eloquent model for improved code intelligence
     *
     * @var \App\Comment
     */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'article_id' => $this->resource->article_id,
            'body' => $this->resource->body,
            'created_at' => $this->resource->created_at->toAtomString(),
            'updated_at' => $this->resource->updated_at->toAtomString(),
            'author' => AuthorResource::make($this->whenLoaded('author')),
        ];
    }
}
