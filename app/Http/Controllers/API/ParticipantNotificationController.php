<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\NotiType\NotiTypeRequest;
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

    public function deleteMeeting($meetingId)
    {
        $data = $this->ParticipantNotificationRepository->deleteMeeting($meetingId);
        ResponseData($data);
    }

    public function storeTraining(Request $request)
    {
        $data = $this->ParticipantNotificationRepository->storeTraining($request->all());
        ResponseData($data);
    }

    public function getTrainings()
    {
        $data = $this->ParticipantNotificationRepository->getTrainings();
        ResponseData($data);
    }
    public function getTrainingById($trainingId)
    {
        $data = $this->ParticipantNotificationRepository->getTrainingById($trainingId);
        ResponseData($data);
    }

    public function updateTraining($trainingId)
    {
        $data = $this->ParticipantNotificationRepository->updateTraining($trainingId);
        ResponseData($data);
    }

    public function deleteTraining($trainingId)
    {
        $data = $this->ParticipantNotificationRepository->deleteTraining($trainingId);
        ResponseData($data);
    }

    public function getOrgNews()
    {
        $data = $this->ParticipantNotificationRepository->getOrgNews();
        ResponseData($data);
    }

    public function storeOrgNews(Request $request)
    {
        $data = $this->ParticipantNotificationRepository->storeOrgNews($request->all());
        ResponseData($data);
    }

    public function updateOrgNews($orgNewsId)
    {
        $data = $this->ParticipantNotificationRepository->updateOrgNews($orgNewsId);
        ResponseData($data);
    }

    public function getOrgNewsById($orgNewsId)
    {
        $data = $this->ParticipantNotificationRepository->getOrgNewsById($orgNewsId);
        ResponseData($data);
    }

    public function deleteOrgNews($orgNewsId)
    {
        $data = $this->ParticipantNotificationRepository->deleteOrgNews($orgNewsId);
        ResponseData($data);
    }

    public function getWarnings(Request $request)
    {
        $data = $this->ParticipantNotificationRepository->getWarnings($request);
        ResponseData($data);
    }

    public function getWarningById($warningId)
    {
        $data = $this->ParticipantNotificationRepository->getWarningById($warningId);
        ResponseData($data);
    }

    public function storeWarning(Request $request)
    {
        $data = $this->ParticipantNotificationRepository->storeWarning($request->all());
        ResponseData($data);
    }

    public function updateWarning($warningId)
    {
        $data = $this->ParticipantNotificationRepository->updateWarning($warningId);
        ResponseData($data);
    }
    public function deleteWarning($warningId)
    {
        $data = $this->ParticipantNotificationRepository->deleteWarning($warningId);
        ResponseData($data);
    }

    public function storeTypes(NotiTypeRequest $request)
    {
        $data = $this->ParticipantNotificationRepository->storeTypes($request->validated());
        ResponseData($data);
    }

    public function getTypes(Request $request)
    {
        $data = $this->ParticipantNotificationRepository->getTypes($request);
        ResponseData($data);
    }
}
