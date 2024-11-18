<?php

namespace App\Repositories\Objective;

use Illuminate\Http\Request;

interface ObjectiveInterface
{
//   public function getObjectives(Request $request);

  public function store($validatedData);
}
