<?php

namespace App\Repositories\Interview;

interface InterviewRepositoryInterface
{
  public function getInterviewsByRoleId($request, $roleId);
  public function getInterviewById($interviewId);
  public function storeInterview(array $data);
  public function getInterviewResults($request);
}
