<?php

namespace App\Repositories\Tag;

interface TagRepositoryInterface
{
    public function getTags();
    public function createTag(array $data);
}
