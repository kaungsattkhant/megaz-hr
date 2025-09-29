<?php
namespace App\Repositories\StaffEquipmentHandover;

interface StaffEquipmentHandoverRepositoryInterface
{
    public function getStaffEquipmentHandoverById($id);
    public function createStaffEquipmentHandover(array $data);
    public function confirmHandover($id, array $data);
    public function cancelHandover($id, array $data);
    // public function updateStaffEquipmentHandover($id, array $data);
    // public function deleteStaffEquipmentHandover($id);
    public function getStaffTimeshift($staffId);
    public function getHandoverStaffs();
    public function getLostItems(); //for admin panel
}
