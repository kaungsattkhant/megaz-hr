<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\HandBook\HandBookInterface;
use Illuminate\Http\Request;

class HandBookeController extends Controller
{
    private HandBookInterface $handBookRepository;
    public function __construct(HandBookInterface $handBookRepository)
    {
        $this->handBookRepository = $handBookRepository;
    }

    public function getHandBookList(Request $request)
    {
        $data = $this->handBookRepository->getHandBookList($request);
        ResponseData($data);
    }

    public function updateOrCreateHandBook(Request $request)
    {
        $data = $this->handBookRepository->updateOrCreateHandBook($request->all());
        ResponseData($data);
    }

    public function getHandBookById($id)
    {
        $data = $this->handBookRepository->getHandBookById($id);
        ResponseData($data);
    }

    public function deleteHandBook($id)
    {
        $data = $this->handBookRepository->deleteHandBook($id);
        ResponseData($data);
    }
}
