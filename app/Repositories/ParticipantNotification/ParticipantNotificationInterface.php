<?php

namespace App\Repositories\ParticipantNotification;

use Illuminate\Support\Facades\Request;

interface ParticipantNotificationInterface
{
  public function getStaffByDepartmentRole($depId, $roleId);

  public function getMeetings();
  public function showMeeting($meetingId);
  public function storeMeetings($data);
  public function updateMeeting($meetingId);
  public function deleteMeeting($meetingId);

  public function getTrainings();
  public function getTrainingById($trainingId);
  public function storeTraining($data);
  public function updateTraining($trainingId);
  public function deleteTraining($trainingId);

  public function storeOrgNews($data);
  public function  updateOrgNews($orgNewsId);
  public function getOrgNews();
  public function getOrgNewsById($orgNewsId);
  public function deleteOrgNews($orgNewsId);
}
