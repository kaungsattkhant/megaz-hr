<?php

namespace App\Repositories\ParticipantNotification;

use Illuminate\Support\Facades\Request;

interface ParticipantNotificationInterface
{
  public function getStaffByDepartmentRole($depId, $roleId);

  public function getMeetings();
  public function showMeeting($meetingId);
  public function storeMeetings($data);
  public function updateMeeting($meetingId, $request);
  public function deleteMeeting($meetingId);

  public function getTrainings();
  public function getTrainingById($trainingId);
  public function storeTraining($data);
  public function updateTraining($trainingId, $request);
  public function deleteTraining($trainingId);

  public function storeOrgNews($data);
  public function updateOrgNews($orgNewsId, $data);
  public function getOrgNews();
  public function getOrgNewsById($orgNewsId);
  public function deleteOrgNews($orgNewsId);

  public function getWarnings($request);
  public function getWarningById($warningId);
  public function storeWarning($data);
  public function updateWarning($warningId, $data);
  public function deleteWarning($warningId);

  public function storeTypes($data);
  public function getTypes($request);

  //mobile
  public function getallNoties($request, $staffId);
  public function getMeetingsByStaffId($staffId,$request);
  public function getTrainingsByStaffId($staffId,$request);
  public function getShiftsByStaffId($staffId,$request);
  public function getConfirmedShiftsByStaffIdTimeShiftId($staffId,$staffTimeshiftId);
}
