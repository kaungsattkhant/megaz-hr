<?php
namespace App\Repositories\StaffEquipmentHandover;

interface StaffEquipmentHandoverRepositoryInterface
{
    // public function getStaffEquipmentHandovers();
    public function createStaffEquipmentHandover(array $data);
    public function confirmHandover($id, array $data);
    // public function updateStaffEquipmentHandover($id, array $data);
    // public function deleteStaffEquipmentHandover($id);
}
