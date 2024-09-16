<?php

namespace App\Repositories\Skill;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkillRepository implements SkillRepositoryInterface
{
    public function listAllSkill()
    {
        $skills = Skill::with('role')->orderBy('created_at','desc')->paginate(config('common.list_count'));
        ResponseData($skills);
    }

    public function createSkill(Request $request)
    {
        DB::beginTransaction();
        try{
            $data= $request->all();
            $duplicateSkill = Skill::where('skill', $data['skill'])->first();
            if ($duplicateSkill) {
                ResponseData( 'This skill already exists.', 422);
            }
            $data['created_by'] = UserData()->id;
            $skill = Skill::create($data);
            DB::commit();
            ResponseData($skill);
        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function updateSkill(Request $request, int $id)
    {
        DB::beginTransaction();
        try{
            $data = $request->all();
            $skill = Skill::find($id);
            $skill->update($data);
            DB::commit();
            ResponseData($skill);

        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function skillDetail(int $id)
    {
        $skill = Skill::with('role')->find($id);
        ResponseData($skill);
    }

    public function skillByRole(int $role_id)
    {
        $skills = Skill::where('role_id',$role_id)->get();
        ResponseData($skills);
    }

    public function deleteSkill(int $id)
    {
        DB::beginTransaction();
        try{
            $skill = Skill::find($id);
            if(!$skill)
            {
                ResponseMessage('Skill not found',422);
            }
            $skill->delete();
            DB::commit();
            ResponseMessage('Skill deleted successfully');
        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }
}
