<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ParticipantNotification\ParticipantNotificationInterface;

class ParticipantNotificationController extends Controller
{
    private ParticipantNotificationInterface $ParticipantNotificationRepository;
    public function __construct(ParticipantNotificationInterface $ParticipantNotificationRepository)
    {
        $this->ParticipantNotificationRepository = $ParticipantNotificationRepository;
    }

    public function getStaffByDepartmentRole($depId, $roleId)
    {
        $data = $this->ParticipantNotificationRepository->getStaffByDepartmentRole($depId, $roleId);
        ResponseData($data);
    }

    public function storeMeetings(Request $request)
    {
        $data = $this->ParticipantNotificationRepository->storeMeetings($request->all());
        ResponseData($data);
    }

    public function getMeetings()
    {
        $data = $this->ParticipantNotificationRepository->getMeetings();
        ResponseData($data);
    }
    public function showMeeting($meetingId)
    {
        $data = $this->ParticipantNotificationRepository->showMeeting($meetingId);
        ResponseData($data);
    }


    public function updateMeeting($meetingId)
    {
        $data = $this->ParticipantNotificationRepository->updateMeeting($meetingId);
        ResponseData($data);
    }
}
