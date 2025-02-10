<?php

namespace App\Repositories\ParticipantNotification;

use Illuminate\Support\Facades\Request;

interface ParticipantNotificationInterface
{
  public function getStaffByDepartmentRole($depId, $roleId);
  public function storeMeetings($data);

  public function getMeetings();
  public function showMeeting($meetingId);
  public function updateMeeting($meetingId);
}
