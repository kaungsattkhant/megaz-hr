<?php

namespace App\Repositories\Exam;

interface ExamRepositoryInterface
{
  public function getAllExams($request);

  // public function getExamById($id);

  public function createExam(array $data);

  // public function updateExam($id, array $data);

  // public function deleteExam($id);

  // public function getExamsByUserId($userId);
}
