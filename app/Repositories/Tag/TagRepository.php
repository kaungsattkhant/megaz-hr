<?php

namespace App\Repositories\Tag;

use App\Models\Tag;

class TagRepository implements TagRepositoryInterface
{
    public function getTags()
    {
        return Tag::orderBy('id', 'desc')->get();
    }

    public function createTag($data)
    {
        return Tag::create($data);
    }
}
