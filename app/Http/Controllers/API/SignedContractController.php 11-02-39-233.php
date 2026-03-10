<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SignedContract;
use App\Repositories\SignedContract\SignedContractRepositoryInterface;
use Illuminate\Http\Request;

class SignedContractController extends Controller
{
    private SignedContractRepositoryInterface $signedContractRepo;

    public function __construct(SignedContractRepositoryInterface $signedContractRepo)
    {
        $this->signedContractRepo = $signedContractRepo;
    }

    public function index(Request $request)
    {
        $signedContracts = $this->signedContractRepo->list($request);
        ResponseData($signedContracts);
    }

    public function store(Request $request)
    {
        $signedContract = $this->signedContractRepo->updateOrCreate($request);
        ResponseData($signedContract);
    }

    public function show(SignedContract $signedContract)
    {
        $signedContract = $this->signedContractRepo->detail($signedContract);
        ResponseData($signedContract);
    }

    public function destroy($id)
    {
        $signedContract = SignedContract::findOrFail($id);
        $signedContract->delete();
        ResponseMessage('Signed contract deleted successfully.', 200);
    }
}
