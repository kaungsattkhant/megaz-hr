<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ContactRequest;
use App\Repositories\Contact\ContactRepositoryInterface;

class ContactController extends Controller
{
    private ContactRepositoryInterface $contactRepository;
    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function contactList(Request $request)
    {
        $data = $this->contactRepository->contactList($request);
        ResponseData($data);
    }
    public function getContactById($id)
    {
        $data = $this->contactRepository->getContactById($id);
        ResponseData($data);
    }

    public function updateOrCreate(ContactRequest $request)
    {
        $data = $this->contactRepository->updateOrCreate($request);
        ResponseData($data);
    }

    public function delete($id)
    {
        $data = $this->contactRepository->delete($id);
        ResponseData($data);
    }
}
