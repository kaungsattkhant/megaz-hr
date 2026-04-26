<?php

namespace App\Providers;

use App\Repositories\Cv\CvRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Area\AreaRepository;
use App\Repositories\Bank\BankRepository;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Loan\LoanRepository;
use App\Repositories\Role\RoleRepository;

use App\Repositories\Leave\LeaveRepository;
use App\Repositories\Skill\SkillRepository;
// use App\Repositories\Ads\AdsRepository;
// use App\Repositories\Ads\AdsRepositoryInterface;
// =======
use App\Repositories\Staff\StaffRepository;
use App\Repositories\OffDay\OffDayRepository;

use App\Repositories\Salary\SalaryRepository;

use App\Repositories\Advance\AdvanceInterface;

use App\Repositories\Benefit\BenefitInterface;
use App\Repositories\Cv\CvRepositoryInterface;

use App\Repositories\Advance\AdvanceRepository;
use App\Repositories\Benefit\BenefitRepository;
use App\Repositories\Contact\ContactRepository;
use App\Repositories\Feature\FeatureRepository;

use App\Repositories\HandBook\HandBookInterface;

use App\Repositories\Contract\ContractRepository;
use App\Repositories\Contract\ContractRepositoryInterface;
use App\Repositories\HandBook\HandBookRepository;

use App\Repositories\Location\LocationRepository;

use App\Repositories\Area\AreaRepositoryInterface;
use App\Repositories\Bank\BankRepositoryInterface;
use App\Repositories\Exam\ExamRepositoryInterface;
use App\Repositories\HomeRepository\HomeInterface;
use App\Repositories\Loan\LoanRepositoryInterface;
use App\Repositories\Objective\ObjectiveInterface;
use App\Repositories\Role\RoleRepositoryInterface;

use App\Repositories\Complaint\ComplaintRepository;
use App\Repositories\HomeRepository\HomeRepository;
use App\Repositories\Interview\InterviewRepository;
use App\Repositories\Inventory\InventoryRepository;
use App\Repositories\Objective\ObjectiveRepository;
use App\Repositories\TimeShift\TimeShiftRepository;
use App\Repositories\Leave\LeaveRepositoryInterface;
use App\Repositories\Skill\SkillRepositoryInterface;
use App\Repositories\Staff\StaffRepositoryInterface;
use App\Repositories\Department\DepartmentRepository;
use App\Repositories\HeadAccount\HeadAccountInterface;
use App\Repositories\OffDay\OffDayRepositoryInterface;
use App\Repositories\Salary\SalaryRepositoryInterface;
use App\Repositories\Transaction\TransactionInterface;
use App\Repositories\HeadAccount\HeadAccountRepository;
use App\Repositories\Resignation\ResignationRepository;
use App\Repositories\Transaction\TransactionRepository;
use App\Repositories\Contact\ContactRepositoryInterface;
use App\Repositories\Feature\FeatureRepositoryInterface;


use App\Repositories\Notification\NotificationInterface;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\StaffAdvance\StaffAdvanceRepository;
use App\Repositories\Location\LocationRepositoryInterface;
use App\Repositories\BudgetAccount\BudgetAccountRepository;
use App\Repositories\Complaint\ComplaintRepositoryInterface;
use App\Repositories\Interview\InterviewRepositoryInterface;
use App\Repositories\Inventory\InventoryRepositoryInterface;
use App\Repositories\TimeShift\TimeShiftRepositoryInterface;
use App\Repositories\JobDescription\JobDescriptionRepository;
use App\Repositories\StaffTimeShift\StaffTimeShiftRepository;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Repositories\Resignation\ResignationRepositoryInterface;

use App\Repositories\StaffAdvance\StaffAdvanceRepositoryInterface;
use App\Repositories\BudgetAccount\BudgetAccountRepositoryInterface;
use App\Repositories\JobDescription\JobDescriptionRepositoryInterface;

use App\Repositories\StaffTimeShift\StaffTimeShiftRepositoryInterface;


use App\Repositories\StaffEquipmentHandover\StaffEquipmentHandoverRepository;
use App\Repositories\ParticipantNotification\ParticipantNotificationInterface;

use App\Repositories\ParticipantNotification\ParticipantNotificationRepository;


use App\Repositories\StaffEquipmentHandover\StaffEquipmentHandoverRepositoryInterface;

use App\Repositories\MeetingMinute\MeetingMinuteRepository;
use App\Repositories\MeetingMinute\MeetingMinuteRepositoryInterface;
use App\Repositories\MenuCategory\MenuCategoryRepository;
use App\Repositories\MenuCategory\MenuCategoryRepositoryInterface;

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
        $this->app->bind(MenuCategoryRepositoryInterface::class, MenuCategoryRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(StaffRepositoryInterface::class, StaffRepository::class);
        $this->app->bind(ComplaintRepositoryInterface::class, ComplaintRepository::class);
        $this->app->bind(InventoryRepositoryInterface::class, InventoryRepository::class);
        $this->app->bind(HeadAccountInterface::class, HeadAccountRepository::class);
        $this->app->bind(TransactionInterface::class, TransactionRepository::class);
        $this->app->bind(ContractRepositoryInterface::class, ContractRepository::class);
        $this->app->bind(NotificationInterface::class, NotificationRepository::class);
        $this->app->bind(FeatureRepositoryInterface::class, FeatureRepository::class);
        $this->app->bind(HomeInterface::class, HomeRepository::class);
        $this->app->bind(StaffAdvanceRepositoryInterface::class, StaffAdvanceRepository::class);
        $this->app->bind(SkillRepositoryInterface::class, SkillRepository::class);
        $this->app->bind(ObjectiveInterface::class, ObjectiveRepository::class);
        $this->app->bind(TimeShiftRepositoryInterface::class, TimeShiftRepository::class);
        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);
        $this->app->bind(ParticipantNotificationInterface::class, ParticipantNotificationRepository::class);
        $this->app->bind(OffDayRepositoryInterface::class, OffDayRepository::class);
        $this->app->bind(LeaveRepositoryInterface::class, LeaveRepository::class);
        $this->app->bind(SalaryRepositoryInterface::class, SalaryRepository::class);
        $this->app->bind(ResignationRepositoryInterface::class, ResignationRepository::class);
        $this->app->bind(CvRepositoryInterface::class, CvRepository::class);
        $this->app->bind(ExamRepositoryInterface::class, ExamRepository::class);
        $this->app->bind(InterviewRepositoryInterface::class, InterviewRepository::class);
        $this->app->bind(LocationRepositoryInterface::class, LocationRepository::class);
        $this->app->bind(BankRepositoryInterface::class, BankRepository::class);

        $this->app->bind(JobDescriptionRepositoryInterface::class, JobDescriptionRepository::class);

        $this->app->bind(StaffTimeShiftRepositoryInterface::class, StaffTimeShiftRepository::class);
        $this->app->bind(HandBookInterface::class, HandBookRepository::class);
        $this->app->bind(LoanRepositoryInterface::class, LoanRepository::class);
        $this->app->bind(StaffEquipmentHandoverRepositoryInterface::class, StaffEquipmentHandoverRepository::class);

        $this->app->bind(BudgetAccountRepositoryInterface::class, BudgetAccountRepository::class);
        $this->app->bind(AdvanceInterface::class, AdvanceRepository::class);
        $this->app->bind(BenefitInterface::class, BenefitRepository::class);
        $this->app->bind(MeetingMinuteRepositoryInterface::class, MeetingMinuteRepository::class);
    }
}
