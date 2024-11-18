<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Objective\ObjectiveRequest;
use App\Repositories\Objective\ObjectiveInterface;
use Illuminate\Http\Request;

class ObjectiveController extends Controller
{
    private ObjectiveInterface $objectiveRepository;
    public function __construct(ObjectiveInterface $objectiveRepository)
    {
        $this->objectiveRepository =$objectiveRepository;
    }

    public function store(ObjectiveRequest $request)
    {
        
        $validatedData = $request->validated();
        $data = $this->objectiveRepository->store($validatedData);

        return $data;
     
    }
}
