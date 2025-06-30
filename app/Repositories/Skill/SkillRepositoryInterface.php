<?php

namespace App\Repositories\Skill;

use Illuminate\Http\Request;

interface SkillRepositoryInterface
{
    public function listAllSkill(Request $request);

    public function createSkill(Request $request);

    public function updateSkill(Request $request, int $id);

    public function deleteSkill(int $id);

    public function skillDetail(int $id);

    public function skillByRole(int $role_id);
}
