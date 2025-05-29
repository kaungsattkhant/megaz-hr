<?php

namespace App\Repositories\Interview;

interface InterviewRepositoryInterface
{
  public function getInterviewsByRoleId($request, $roleId);
  public function getInterviewById($interviewId);
}
