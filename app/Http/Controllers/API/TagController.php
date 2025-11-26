<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Tag\TagRepositoryInterface;

class TagController extends Controller
{
    private TagRepositoryInterface $TagRepository;

    public function __construct(TagRepositoryInterface $TagRepository)
    {
        $this->TagRepository = $TagRepository;
    }

    public function getTags()
    {
        $data = $this->TagRepository->getTags();
        ResponseData($data);
    }

    public function createTag(Request $request)
    {
        $data = $this->TagRepository->createTag($request->all());
        ResponseData($data);
    }

}
