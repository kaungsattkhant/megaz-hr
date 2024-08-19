<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Skill\SkillRepositoryInterface;
use Illuminate\Http\Request;

class SkillAPIController extends Controller
{
    //
    protected $skillRepo;

    public function __construct(SkillRepositoryInterface $skillRepo)
    {
        $this->skillRepo = $skillRepo;
    }

    public function listAllSkills()
    {
        $this->skillRepo->listAllSkill();
    }

    public function createSkill(Request $request)
    {
        $this->skillRepo->createSkill($request);
    }

    public function skillDetail(int $id)
    {
        $this->skillRepo->skillDetail($id);
    }

    public function updateSkill(int $id,Request $request)
    {
        $this->skillRepo->updateSkill($request, $id);
    }

    public function deleteSkill(int $id)
    {
        $this->skillRepo->deleteSkill($id);
    }
}
