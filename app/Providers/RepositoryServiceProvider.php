<?php

namespace App\Providers;

use App\Models\DeliveryCharge;
use App\Models\UsedDefectedItem;
use App\Models\FixedAssetPurchase;
use App\Repositories\Cv\CvRepository;
use App\Repositories\Ads\AdsRepository;
use App\Repositories\Tag\TagRepository;

use App\Repositories\Uom\UomRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Area\AreaRepository;
use App\Repositories\Bank\BankRepository;
use App\Repositories\Duty\DutyRepository;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Item\ItemRepository;
use App\Repositories\Menu\MenuRepository;
use App\Repositories\Pack\PackRepository;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Task\TaskRepository;
use App\Repositories\Asset\AssetInterface;
use App\Repositories\Asset\AssetRepository;
use App\Repositories\Event\EventRepository;
use App\Repositories\Leave\LeaveRepository;
use App\Repositories\Order\OrderRepository;
// <<<<<<< HEAD
// use App\Repositories\AccountPayable\AccountPayableInterface;
// use App\Repositories\AccountPayable\AccountPayableRepository;
use App\Repositories\Skill\SkillRepository;
use App\Repositories\Staff\StaffRepository;
// use App\Repositories\Ads\AdsRepository;
// use App\Repositories\Ads\AdsRepositoryInterface;
// =======
use App\Repositories\Entity\EntityRepository;
use App\Repositories\OffDay\OffDayRepository;
// >>>>>>> origin/k/backend-api-main
use App\Repositories\Salary\SalaryRepository;
use App\Repositories\Account\AccountInterface;
use App\Repositories\Canteen\CanteenInterface;

use App\Repositories\Cv\CvRepositoryInterface;
use App\Repositories\Service\ServiceInterface;

use App\Repositories\Account\AccountRepository;

use App\Repositories\Accrued\AccruedRepository;
use App\Repositories\Booking\BookingRepository;
use App\Repositories\Canteen\CanteenRepository;
use App\Repositories\Contact\ContactRepository;
use App\Repositories\Feature\FeatureRepository;
use App\Repositories\Invoice\InvoiceRepository;
use App\Repositories\Journal\JournalRepository;
use App\Repositories\Package\PackageRepository;
use App\Repositories\PoOrder\PoOrderRepository;
use App\Repositories\Prepaid\PrepaidRepository;
use App\Repositories\Service\ServiceRepository;
use App\Repositories\Ads\AdsRepositoryInterface;
use App\Repositories\CashBook\CashBookInterface;
use App\Repositories\Creditor\CreditorInterface;
use App\Repositories\HandBook\HandBookInterface;
use App\Repositories\Supplier\SupplierInterface;

use App\Repositories\Tag\TagRepositoryInterface;
use App\Repositories\Uom\UomRepositoryInterface;


use App\Repositories\CashBook\CashBookRepository;
use App\Repositories\Creditor\CreditorRepository;

use App\Repositories\Customer\CustomerRepository;
use App\Repositories\HandBook\HandBookRepository;

use App\Repositories\Location\LocationRepository;
use App\Repositories\Supplier\SupplierRepository;
use App\Repositories\Transfer\TransferRepository;


use App\Repositories\Accessory\AccessoryInterface;
use App\Repositories\Area\AreaRepositoryInterface;

use App\Repositories\Bank\BankRepositoryInterface;
use App\Repositories\Duty\DutyRepositoryInterface;

use App\Repositories\Exam\ExamRepositoryInterface;
use App\Repositories\HomeRepository\HomeInterface;

use App\Repositories\Item\ItemRepositoryInterface;
use App\Repositories\Menu\MenuRepositoryInterface;
use App\Repositories\Objective\ObjectiveInterface;
use App\Repositories\Pack\PackRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Repositories\Accessory\AccessoryRepository;
use App\Repositories\Complaint\ComplaintRepository;
use App\Repositories\FoodOrder\FoodOrderRepository;
use App\Repositories\HomeRepository\HomeRepository;
use App\Repositories\Interview\InterviewRepository;
use App\Repositories\Inventory\InventoryRepository;
use App\Repositories\Objective\ObjectiveRepository;

use App\Repositories\TimeShift\TimeShiftRepository;
use App\Repositories\Event\EventRepositoryInterface;

use App\Repositories\Leave\LeaveRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Skill\SkillRepositoryInterface;
use App\Repositories\Staff\StaffRepositoryInterface;
use App\Repositories\Department\DepartmentRepository;
use App\Repositories\Entity\EntityRepositoryInterface;
use App\Repositories\HeadAccount\HeadAccountInterface;
use App\Repositories\OffDay\OffDayRepositoryInterface;
use App\Repositories\Salary\SalaryRepositoryInterface;
use App\Repositories\Transaction\TransactionInterface;
use App\Repositories\HeadAccount\HeadAccountRepository;
use App\Repositories\MRPForecast\MRPForecastRepository;
use App\Repositories\Resignation\ResignationRepository;
use App\Repositories\RoomSession\RoomSessionRepository;
use App\Repositories\Transaction\TransactionRepository;
use App\Repositories\Accrued\AccruedRepositoryInterface;
use App\Repositories\Booking\BookingRepositoryInterface;
use App\Repositories\Contact\ContactRepositoryInterface;
use App\Repositories\Feature\FeatureRepositoryInterface;
use App\Repositories\FinancialReport\FinancialInterface;
use App\Repositories\Invoice\InvoiceRepositoryInterface;
use App\Repositories\Journal\JournalRepositoryInterface;
use App\Repositories\Notification\NotificationInterface;
use App\Repositories\Package\PackageRepositoryInterface;
use App\Repositories\PoOrder\PoOrderRepositoryInterface;
use App\Repositories\Prepaid\PrepaidRepositoryInterface;
use App\Repositories\CookingPlace\CookingPlaceRepository;

use App\Repositories\FinancialReport\FinancialRepository;
use App\Repositories\MenuCategory\MenuCategoryRepository;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\RoomDiscount\RoomDiscountRepository;
use App\Repositories\SellingExtra\SellingExtraRepository;
use App\Repositories\StaffAdvance\StaffAdvanceRepository;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\Location\LocationRepositoryInterface;

// =======
use App\Repositories\Transfer\TransferRepositoryInterface;
use App\Repositories\PurchaseOrder\PurchaseOrderRepository;
use App\Repositories\AccountPayable\AccountPayableInterface;
use App\Repositories\Complaint\ComplaintRepositoryInterface;
use App\Repositories\FoodOrder\FoodOrderRepositoryInterface;
use App\Repositories\Interview\InterviewRepositoryInterface;
use App\Repositories\Inventory\InventoryRepositoryInterface;
use App\Repositories\JobDescription\JobDescriptionInterface;
use App\Repositories\TimeShift\TimeShiftRepositoryInterface;
use App\Repositories\AccountPayable\AccountPayableRepository;
use App\Repositories\DeliveryCharge\DeliveryChargeRepository;
use App\Repositories\JobDescription\JobDescriptionRepository;
use App\Repositories\SaleTargetMenu\SaleTargetMenuRepository;
use App\Repositories\StaffTimeShift\StaffTimeShiftRepository;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Repositories\MRPForecast\MRPForecastRepositoryInterface;
use App\Repositories\Resignation\ResignationRepositoryInterface;
use App\Repositories\RoomSession\RoomSessionRepositoryInterface;
use App\Repositories\UsedDefectedItem\UsedDefectedItemRepository;
use App\Repositories\CookingPlace\CookingPlaceRepositoryInterface;
use App\Repositories\ItemUsageForecast\ItemUsageForecastInterface;
use App\Repositories\MenuCategory\MenuCategoryRepositoryInterface;
use App\Repositories\RoomDiscount\RoomDiscountRepositoryInterface;
use App\Repositories\SellingExtra\SellingExtraRepositoryInterface;
use App\Repositories\StaffAdvance\StaffAdvanceRepositoryInterface;
use App\Repositories\AccountReceivable\AccountReceivableRepository;
use App\Repositories\BirthdayPromotion\BirthdayPromotionRepository;
use App\Repositories\ItemUsageForecast\ItemUsageForecastRepository;
use App\Repositories\PurchaseOrderItem\PurchaseOrderItemRepository;
use App\Repositories\PurchaseOrder\PurchaseOrderRepositoryInterface;
use App\Repositories\FixedAssetPurchase\FixedAssetPurchaseRepository;
use App\Repositories\SaleTargetPosition\SaleTargetPositionRepository;
use App\Repositories\DeliveryCharge\DeliveryChargeRepositoryInterface;
use App\Repositories\JobDescription\JobDescriptionRepositoryInterface;
use App\Repositories\SaleTargetMenu\SaleTargetMenuRepositoryInterface;
use App\Repositories\StaffTimeShift\StaffTimeShiftRepositoryInterface;
use App\Repositories\MenuServiceDiscount\MenuServiceDiscountRepository;
use App\Repositories\AssetInventoryLedger\AssetInventoryLedgerInterface;
use App\Repositories\AssetInventoryLedger\AssetInventoryLedgerRepository;
use App\Repositories\PurchaseOrderItemLeft\PurchaseOrderItemLeftInterface;
use App\Repositories\UsedDefectedItem\UsedDefectedITemRepositoryInterface;
use App\Repositories\CustomerLevelDiscount\CustomerLevelDiscountRepository;
use App\Repositories\PurchaseOrderItemLeft\PurchaseOrderItemLeftRepository;
use App\Repositories\AccountReceivable\AccountReceivableRepositoryInterface;
use App\Repositories\BirthdayPromotion\BirthdayPromotionRepositoryInterface;
use App\Repositories\PurchaseOrderItem\PurchaseOrderItemRepositoryInterface;
use App\Repositories\FixedAssetPurchase\FixedAssetPurchaseRepositoryInterface;

use App\Repositories\ParticipantNotification\ParticipantNotificationInterface;
use App\Repositories\SaleTargetPosition\SaleTargetPositionRepositoryInterface;
use App\Repositories\ParticipantNotification\ParticipantNotificationRepository;
use App\Repositories\MenuServiceDiscount\MenuServiceDiscountRepositoryInterface;
use App\Repositories\AssetItemEquipmentAssign\AssetItemEquipmentAssignRepository;
use App\Repositories\CustomerLevelDiscount\CustomerLevelDiscountRepositoryInterface;
use App\Repositories\MaterialRequirementsPlanning\MaterialRequirementsPlanningInterface;
use App\Repositories\MaterialRequirementsPlanning\MaterialRequirementsPlanningRepository;
use App\Repositories\AssetItemEquipmentAssign\AssetItemEquipmentAssignRepositoryInterface;

use App\Repositories\SaleLedgerReport\SaleLedgerReportRepositoryInterface;
use App\Repositories\SaleLedgerReport\SaleLedgerReportRepository;
use App\Repositories\CustomerDepositReport\CustomerDepositReportRepositoryInterface;
use App\Repositories\CustomerDepositReport\CustomerDepositReportRepository;
use App\Repositories\DepositAndReceivableReport\DepositAndReceivableReportRepositoryInterface;
use App\Repositories\DepositAndReceivableReport\DepositAndReceivableReportRepository;
use App\Repositories\CreditPurchaseJournal\CreditPurchaseJournalRepositoryInterface;
use App\Repositories\CreditPurchaseJournal\CreditPurchaseJournalRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        $this->app->bind(AreaRepositoryInterface::class, AreaRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(StaffRepositoryInterface::class, StaffRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(ComplaintRepositoryInterface::class, ComplaintRepository::class);
        $this->app->bind(EntityRepositoryInterface::class, EntityRepository::class);
        $this->app->bind(InventoryRepositoryInterface::class, InventoryRepository::class);
        $this->app->bind(UomRepositoryInterface::class, UomRepository::class);
        $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
        $this->app->bind(PurchaseOrderRepositoryInterface::class, PurchaseOrderRepository::class);
        $this->app->bind(PurchaseOrderItemRepositoryInterface::class, PurchaseOrderItemRepository::class);
        $this->app->bind(TransferRepositoryInterface::class, TransferRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(MenuRepositoryInterface::class, MenuRepository::class);
        $this->app->bind(InvoiceRepositoryInterface::class, InvoiceRepository::class);
        $this->app->bind(RoomSessionRepositoryInterface::class, RoomSessionRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(ItemUsageForecastInterface::class, ItemUsageForecastRepository::class);
        $this->app->bind(HeadAccountInterface::class, HeadAccountRepository::class);
        $this->app->bind(AccountInterface::class, AccountRepository::class);
        $this->app->bind(TransactionInterface::class, TransactionRepository::class);
        $this->app->bind(CashBookInterface::class, CashBookRepository::class);
        $this->app->bind(SupplierInterface::class, SupplierRepository::class);
        $this->app->bind(NotificationInterface::class, NotificationRepository::class);
        $this->app->bind(PurchaseOrderItemLeftInterface::class, PurchaseOrderItemLeftRepository::class);
        $this->app->bind(FixedAssetPurchaseRepositoryInterface::class, FixedAssetPurchaseRepository::class);
        $this->app->bind(PackRepositoryInterface::class, PackRepository::class);
        $this->app->bind(UsedDefectedITemRepositoryInterface::class, UsedDefectedItemRepository::class);
        $this->app->bind(FeatureRepositoryInterface::class, FeatureRepository::class);
        $this->app->bind(AccountPayableInterface::class, AccountPayableRepository::class);
        $this->app->bind(MenuServiceDiscountRepositoryInterface::class, MenuServiceDiscountRepository::class);
        $this->app->bind(RoomDiscountRepositoryInterface::class, RoomDiscountRepository::class);
        $this->app->bind(PackageRepositoryInterface::class, PackageRepository::class);
        $this->app->bind(BirthdayPromotionRepositoryInterface::class, BirthdayPromotionRepository::class);
        $this->app->bind(CustomerLevelDiscountRepositoryInterface::class, CustomerLevelDiscountRepository::class);
        $this->app->bind(HomeInterface::class, HomeRepository::class);
        $this->app->bind(MenuCategoryRepositoryInterface::class, MenuCategoryRepository::class);
        $this->app->bind(AdsRepositoryInterface::class, AdsRepository::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(FoodOrderRepositoryInterface::class, FoodOrderRepository::class);
        $this->app->bind(DeliveryChargeRepositoryInterface::class, DeliveryChargeRepository::class);
        $this->app->bind(AssetInterface::class, AssetRepository::class);
        $this->app->bind(AssetInventoryLedgerInterface::class, AssetInventoryLedgerRepository::class);
        $this->app->bind(JournalRepositoryInterface::class, JournalRepository::class);
        $this->app->bind(StaffAdvanceRepositoryInterface::class, StaffAdvanceRepository::class);
        $this->app->bind(PrepaidRepositoryInterface::class, PrepaidRepository::class);
        $this->app->bind(AccountReceivableRepositoryInterface::class, AccountReceivableRepository::class);
        $this->app->bind(FinancialInterface::class, FinancialRepository::class);
        $this->app->bind(SkillRepositoryInterface::class, SkillRepository::class);
        $this->app->bind(CookingPlaceRepositoryInterface::class, CookingPlaceRepository::class);
        $this->app->bind(DutyRepositoryInterface::class, DutyRepository::class);
        $this->app->bind(SaleTargetPositionRepositoryInterface::class, SaleTargetPositionRepository::class);
        $this->app->bind(SaleTargetMenuRepositoryInterface::class, SaleTargetMenuRepository::class);
        $this->app->bind(CanteenInterface::class, CanteenRepository::class);
        $this->app->bind(ServiceInterface::class, ServiceRepository::class);
        $this->app->bind(AccessoryInterface::class, AccessoryRepository::class);
        $this->app->bind(MaterialRequirementsPlanningInterface::class, MaterialRequirementsPlanningRepository::class);
        $this->app->bind(ObjectiveInterface::class, ObjectiveRepository::class);
        $this->app->bind(CreditorInterface::class, CreditorRepository::class);
        $this->app->bind(MRPForecastRepositoryInterface::class, MRPForecastRepository::class);
        $this->app->bind(TimeShiftRepositoryInterface::class, TimeShiftRepository::class);
        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);
        $this->app->bind(PoOrderRepositoryInterface::class, PoOrderRepository::class);
        $this->app->bind(ParticipantNotificationInterface::class, ParticipantNotificationRepository::class);
        $this->app->bind(OffDayRepositoryInterface::class, OffDayRepository::class);
        $this->app->bind(LeaveRepositoryInterface::class, LeaveRepository::class);
        $this->app->bind(SalaryRepositoryInterface::class, SalaryRepository::class);
        $this->app->bind(ResignationRepositoryInterface::class, ResignationRepository::class);
        $this->app->bind(CvRepositoryInterface::class, CvRepository::class);
        $this->app->bind(ExamRepositoryInterface::class, ExamRepository::class);
        $this->app->bind(InterviewRepositoryInterface::class, InterviewRepository::class);
        $this->app->bind(LocationRepositoryInterface::class, LocationRepository::class);
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(BankRepositoryInterface::class, BankRepository::class);

        $this->app->bind(JobDescriptionRepositoryInterface::class, JobDescriptionRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);

        $this->app->bind(SellingExtraRepositoryInterface::class, SellingExtraRepository::class);
        $this->app->bind(StaffTimeShiftRepositoryInterface::class, StaffTimeShiftRepository::class);
        $this->app->bind(AssetItemEquipmentAssignRepositoryInterface::class, AssetItemEquipmentAssignRepository::class);
        $this->app->bind(HandBookInterface::class, HandBookRepository::class);
        $this->app->bind(AccruedRepositoryInterface::class, AccruedRepository::class);
        $this->app->bind(SaleLedgerReportRepositoryInterface::class, SaleLedgerReportRepository::class);
        $this->app->bind(CustomerDepositReportRepositoryInterface::class, CustomerDepositReportRepository::class);
        $this->app->bind(DepositAndReceivableReportRepositoryInterface::class, DepositAndReceivableReportRepository::class);
        $this->app->bind(CreditPurchaseJournalRepositoryInterface::class, CreditPurchaseJournalRepository::class);
    }
}
