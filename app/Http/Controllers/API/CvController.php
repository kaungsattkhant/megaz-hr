<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Cv\CvRepositoryInterface;
use Illuminate\Http\Request;

class CvController extends Controller
{
    private CvRepositoryInterface $cvRepository;
    public function __construct(CvRepositoryInterface $cvRepository)
    {
        $this->cvRepository = $cvRepository;
    }

    public function skillByRoleAndDepartment($depId, $roleId)
    {
        $data = $this->cvRepository->skillByRoleAndDepartment($depId, $roleId);
        ResponseData($data);
    }

    public function getAllCvs(Request $request)
    {
        $data = $this->cvRepository->getAllCvs($request);
        ResponseData($data);
    }

    public function createCv(Request $request)
    {
        $data = $request->except(['nrc_front_image', 'nrc_back_image', 'household_registration_image', 'profile_image']);
        if ($request->hasFile('nrc_front_image')) {
            $uploadedFile = UploadFileToServer($request, 'nrc_front_image', 'staff_images');
            $data['nrc_front_url'] = $uploadedFile['file_url'];
            $data['nrc_front_path'] = $uploadedFile['file_path'];
        }
        if ($request->hasFile('nrc_back_image')) {
            $uploadedFile = UploadFileToServer($request, 'nrc_back_image', 'staff_images');
            $data['nrc_back_url'] = $uploadedFile['file_url'];
            $data['nrc_back_path'] = $uploadedFile['file_path'];
        }
        if ($request->hasFile('household_registration_image')) {
            $uploadedFile = UploadFileToServer($request, 'household_registration_image', 'staff_images');
            $data['household_registration_url'] = $uploadedFile['file_url'];
            $data['household_registration_path'] = $uploadedFile['file_path'];
        }
        if ($request->hasFile('profile_image')) {
            $uploadedFile = UploadFileToServer($request, 'profile_image', 'staff_images');
            $data['profile_image_url'] = $uploadedFile['file_url'];
            $data['profile_image_path'] = $uploadedFile['file_path'];
        }
        $data = $this->cvRepository->createCv($data);
        ResponseData($data);
    }

    public function getCvById($id)
    {
        $data = $this->cvRepository->getCvById($id);
        ResponseData($data);
    }
    public function updateCv($id, Request $request)
    {
        $data = $request->except(['nrc_front_image', 'nrc_back_image', 'household_registration_image', 'profile_image']);
        if ($request->hasFile('nrc_front_image')) {
            $uploadedFile = UploadFileToServer($request, 'nrc_front_image', 'staff_images');
            $data['nrc_front_url'] = $uploadedFile['file_url'];
            $data['nrc_front_path'] = $uploadedFile['file_path'];
        }
        if ($request->hasFile('nrc_back_image')) {
            $uploadedFile = UploadFileToServer($request, 'nrc_back_image', 'staff_images');
            $data['nrc_back_url'] = $uploadedFile['file_url'];
            $data['nrc_back_path'] = $uploadedFile['file_path'];
        }
        if ($request->hasFile('household_registration_image')) {
            $uploadedFile = UploadFileToServer($request, 'household_registration_image', 'staff_images');
            $data['household_registration_url'] = $uploadedFile['file_url'];
            $data['household_registration_path'] = $uploadedFile['file_path'];
        }
        if ($request->hasFile('profile_image')) {
            $uploadedFile = UploadFileToServer($request, 'profile_image', 'staff_images');
            $data['profile_image_url'] = $uploadedFile['file_url'];
            $data['profile_image_path'] = $uploadedFile['file_path'];
        }
        $data = $this->cvRepository->updateCv($id, $data);
        ResponseData($data);
    }

    public function deleteCv($id)
    {
        $data = $this->cvRepository->deleteCv($id);
        ResponseData($data);
    }

    public function updateCvStatus($id, Request $request)
    {
        $data = $this->cvRepository->updateCvStatus($id, $request->all());
        ResponseData($data);
    }

    public function getSalarySetupByDepartmentIdAndRoleId($departmentId, $roleId)
    {
        $data = $this->cvRepository->getSalarySetupByDepartmentIdAndRoleId($departmentId, $roleId);
        ResponseData($data);
    }

    public function createNewStaffSalary(Request $request)
    {
        $data = $this->cvRepository->createNewStaffSalary($request->all());
        ResponseData($data);
    }

    public function storeNewStaffJoinDate(Request $request)
    {
        $data = $this->cvRepository->storeNewStaffJoinDate($request->all());
        ResponseData($data);
    }
}
