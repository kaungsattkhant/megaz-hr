<template>
    <nav id="sidebar_admin" class="side-bar w-fit pt-0 h-[100vh]">
        <div class="relative flex">
            <button type="button" id="toggleBtn"
                class="py-3 px-2 absolute -right-4 overflow-hidden top-8 bg-[#fafafa] text-black rounded-tr-md rounded-br-md border-gray-400 z-50 shadow-md">
                <i class="fas fa-chevron-double-left ease-linear" style="transition:transform 0.5s ease;"></i>
            </button>

            <div>
                <div class="w-fit h-screen overflow-y-auto pb-20 no-scrollbar border-r border-gray-200" id="icon_sidebar">
                    <div class="w-20 sidebar-bg flex flex-col items-center py-6 space-y-4 text-gray-500">
                        <div class="w-12 h-12 flex items-center justify-center bg-white rounded-lg shadow-md mb-4">
                            <img src="../../../../public/img/logo.png" alt="Logo" class="w-10">
                        </div>
                        <div class="flex flex-col space-y-4 w-full items-center">
                            <button data-tab="tab1" class="tab-btn w-12 h-12 rounded-lg active-nav-item flex items-center justify-center cursor-pointer" title="Report">
                                <i class="fal fa-chart-line"></i>
                            </button>
                            <button v-show="hasAnyPermission([
                                'department', 'role', 'area.list', 'room', 'table', 'inventory',
                                'brand', 'service', 'cooking-place'
                              ])" data-tab="tab-setup" class="tab-btn w-12 h-12 rounded-lg active-nav-item flex items-center justify-center cursor-pointer" title="Set Up">
                                <i class="fal fa-network-wired"></i>
                            </button>
                            <button v-show="hasAnyPermission([
                                'item', 'uom-conversion', 'accessory', 'supplier',
                              ])" data-tab="tab-item" class="tab-btn w-12 h-12 rounded-lg active-nav-item flex items-center justify-center cursor-pointer" title="Item">
                                <i class="fal fa-hand-receiving"></i>
                            </button>
                            <button v-show="hasAnyPermission([
                                'staff', 'okr-duty', 'meeting', 'training', 'org-new', 'warning',
                                'check-in', 'leave', 'exit-pass', 'resignation',
                                'shift-assignment', 'equipment-assignment'
                              ])" data-tab="tab-hr" class="tab-btn w-12 h-12 rounded-lg active-nav-item flex items-center justify-center cursor-pointer" title="Hr">
                                <i class="far fa-user"></i>
                            </button>
                            <button v-show="hasAnyPermission([
                                'salary-batch', 'salary-calculate', 'pay-slip'
                                ])" data-tab="tab-salary" class="tab-btn w-12 h-12 rounded-lg active-nav-item flex items-center justify-center cursor-pointer" title="Salary">
                                <i class="fal fa-file-invoice-dollar"></i>
                            </button>
                            <button  v-show="hasAnyPermission([
                                'jd','js','sop','cv','okr','time-shift','gps','contact','off-day','leave-allowance',
                                'overtime-fee','salary-setup','salary','resignation-categories','skill','handbook',
                                'overtime-confirmation','allowance'
                                ])" data-tab="tab-hr-setup" class="tab-btn w-12 h-12 rounded-lg active-nav-item flex items-center justify-center cursor-pointer" title="Hr Setup">
                                <i class="fal fa-layer-group"></i>
                            </button>
                            <button v-show="hasAnyPermission([
                                'menu-category','menu-sale-report','menu-costing','menu','menu-area'
                                ])" data-tab="tab-menu" class="tab-btn w-12 h-12 rounded-lg active-nav-item flex items-center justify-center cursor-pointer" title="Menu">
                                <i class="fal fa-salad"></i>
                            </button>
                            <button v-show="hasAnyPermission([
                                'room-discount','menu-service-discount','package','customer',
                                'customer-birthday','customer-level-discount','customer-birthday-promotion'
                                ])" data-tab="tab-promotion" class="tab-btn w-12 h-12 rounded-lg active-nav-item flex items-center justify-center cursor-pointer" title="Promotion">
                                <i class="fal fa-badge-percent"></i>
                            </button>
                            <button v-show="hasAnyPermission([
                                'cashbook','journal','staff-balance','prepaid','account',
                                'financial-transaction','asset-item','asset','fix-asset',
                                'ap-balance','ar','loan','accrual'
                                ])" data-tab="tab-financial" class="tab-btn w-12 h-12 rounded-lg active-nav-item flex items-center justify-center cursor-pointer" title="Financial">
                                <i class="fal fa-sack-dollar"></i>
                            </button>
                            <button v-show="hasAnyPermission([
                                'menu','ktv-product-tree','menu-forecasting','ktv-forecasting'
                                ])" data-tab="tab-mrp" class="tab-btn w-12 h-12 rounded-lg active-nav-item flex items-center justify-center cursor-pointer" title="MRP">
                                <i class="fal fa-project-diagram "></i>
                            </button>
                            <button v-show="hasAnyPermission([
                                'purchase-order','confirm-purchase-order-item.list','purchase-order-item-left',
                                'po-order','arrival-item','po-order-invoice'
                                ])" data-tab="tab-order" class="tab-btn w-12 h-12 rounded-lg active-nav-item flex items-center justify-center cursor-pointer" title="Order">
                                <i class="fal fa-shopping-bag"></i>
                            </button>
                            <button v-show="hasAnyPermission([
                                'inventory-stock','inventory-transfer-history','inventory-transfer-receive','inventory-transfer','used-defected-item'
                                ])" data-tab="tab-inventory" class="tab-btn w-12 h-12 rounded-lg active-nav-item flex items-center justify-center cursor-pointer" title="Inventory">
                                <i class="fal fa-warehouse"></i>
                            </button>
                            <button  v-show="hasAnyPermission([
                                'okr-dashboard','event','asset-assignment'
                                ])" data-tab="tab-okr" class="tab-btn w-12 h-12 rounded-lg active-nav-item flex items-center justify-center cursor-pointer" title="Okr">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </button>
                            <button data-tab="tab-sample" class="tab-btn w-12 h-12 rounded-lg active-nav-item flex items-center justify-center cursor-pointer" title="Sample">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="relative pb-20 small-scrollbar h-[100vh]" onmouseover="this.style.overflowY='auto'"
                onmouseout="this.style.overflowY='hidden'" id="sidebar_scroll_list">
                <div class="relative w-[256px] pt-12">
                    <div class="tab-content" id="tab1">
                        <ul>
                            <li>
                                <button class="flex items-center pl-9 my-2 text-sm w-full" type="button" data-te-collapse-init
                                    data-te-ripple-init data-te-ripple-color="light" data-te-target="#collapseFinanceReport"
                                    aria-expanded="false" aria-controls="collapseExample">
                                    <i class="fal fa-chart-line  pr-3"></i>
                                    Finance Report <i class="fas fa-angle-down absolute right-2"></i>
                                </button>
                                <div class="!visible hidden text-center" id="collapseFinanceReport"
                                    data-te-collapse-item>
                                    <ul>
                                        <li v-show="checkFeaturePermission('depcash-flowartment')">
                                            <a href="/cash_flow_statement"
                                                class="flex items-center text-left ">
                                                <i class="fal fa-tasks  pr-3"></i>
                                                Cash Flow
                                            </a>
                                        </li>
                                        <li v-show="checkFeaturePermission('indirect-cash-flow')">
                                            <a href="/indirect_cashflow_statement"
                                                class="flex items-center text-left !px-0 ">
                                                <i class="fal fa-tasks  pr-3"></i>
                                                Indirect Cash Flow
                                            </a>
                                        </li>
                                        <li v-show="checkFeaturePermission('working-capital')">
                                            <a href="/working_capital"
                                                class="flex items-center text-left !px-0">
                                                <i class="fal fa-braille pr-3"></i>
                                                Working Capital
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/profit_and_loss"
                                                class="flex items-center text-left !px-0">
                                                <i class="fal fa-braille pr-3"></i>
                                                Profit And Loss
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/trial_balance"
                                                class="flex items-center text-left !px-0">
                                                <i class="fal fa-braille pr-3"></i>
                                                Trial Balance
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/sale_ledger" class="flex items-center text-left !px-0">
                                                <i class="fal fa-braille pr-3"></i>
                                                Sale Ledger
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/sale_ledger_ktv"
                                                class="flex items-center text-left !px-0">
                                                <i class="fal fa-braille pr-3"></i>
                                                Sale Ledger Ktv
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/sale_ledger_restaurant"
                                                class="flex items-center text-left !px-0">
                                                <i class="fal fa-braille pr-3"></i>
                                                Sale Ledger Restaurant
                                            </a>
                                        </li>
                                        <li v-show="checkFeaturePermission('ap-balance')">
                                            <a href="/ap_balances" class="flex items-center text-left !px-0">
                                                <i class="fal fa-braille pr-3"></i>
                                                AP Balance
                                            </a>
                                        </li>
                                        <li v-show="checkFeaturePermission('creditor-balance')">
                                            <a href="/creditor_balances"
                                                class="flex items-center text-left !px-0">
                                                <i class="fal fa-braille pr-3"></i>
                                                Creditor Balance
                                            </a>
                                        </li>
                                        <li v-show="checkFeaturePermission('asset-depreciation-balance')">
                                            <a href="/asset_depreciation_balance_list"
                                                class="flex items-center text-left !px-0">
                                                <i class="fal fa-braille pr-3"></i>
                                                Current Asset Depreciation
                                            </a>
                                        </li>
                                        <li v-show="checkFeaturePermission('asset-depreciation-balance')">
                                            <a href="/fix_asset_depreciation"
                                            class="flex items-center text-left !px-0">
                                                <i class="fal fa-braille pr-3"></i>
                                                Fix Asset Depreciation
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li  v-show="hasAnyPermission([
                                'sale-target-menu', 'sale-target-position',
                                ])">
                                <button class="flex items-center pl-9 my-2 text-sm w-full" type="button" data-te-collapse-init
                                    data-te-ripple-init data-te-ripple-color="light" data-te-target="#collapseSaleTargetReport"
                                    aria-expanded="false" aria-controls="collapseExample">
                                    <i class="fal fa-chart-line  pr-3"></i>
                                    Sale Target <i class="fas fa-angle-down absolute right-2"></i>
                                </button>
                                <div class="!visible hidden text-center" id="collapseSaleTargetReport"
                                    data-te-collapse-item>
                                    <ul>
                                        <li v-show="checkFeaturePermission('sale-target-menu')">
                                            <a href="/sale_target_menu"
                                                class="flex items-center text-left ">
                                                <i class="fal fa-tasks  pr-3"></i>
                                                Menu
                                            </a>
                                        </li>
                                        <li v-show="checkFeaturePermission('sale-target-position')">
                                            <a href="/sale_target_position"
                                                class="flex items-center text-left ">
                                                <i class="fal fa-tasks  pr-3"></i>
                                                Position
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="tab-setup">
                        <ul v-if="hasAnyPermission([
                            'department', 'role', 'area.list', 'room', 'table', 'inventory',
                            'brand', 'service', 'cooking-place'
                          ])">
                            <li>
                                <p class="sidebar-tab-title">
                                    SET UP
                                </p>
                            </li>
                            <li v-show="checkFeaturePermission('department')">
                                <a href="/departments"
                                    class="flex items-center  sidebar-gap-x">
                                    <i class="fal fa-network-wired"></i>
                                    Department
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('role')">
                                <a href="/roles" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-tasks"></i>
                                    Roles
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('area.list')">
                                <a href="/areas" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-network-wired"></i>
                                    Areas
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('room')">
                                <a href="/rooms" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-microphone-alt"></i>
                                    Room
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('table')">
                                <a href="/tables" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-utensils"></i>
                                    Table
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('inventory')">
                                <a href="/inventories"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-inventory  pr-3"></i>
                                    Inventories
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('brand')">
                                <a href="/brands" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-copyright"></i>
                                    Brands
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('service')">
                                <a href="/services" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-concierge-bell"></i>
                                    Services
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('cooking-place')">
                                <a href="/cooking_places"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="far fa-hat-chef"></i>
                                    Cooking Place
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="tab-item">
                        <ul v-show="hasAnyPermission([
                            'item', 'uom-conversion', 'accessory', 'supplier',
                          ])">
                            <li>
                                <p class="sidebar-tab-title">
                                    ITEM
                                </p>
                            </li>
                            <li v-show="checkFeaturePermission('item')">
                                <a href="/items" class="flex items-center  sidebar-gap-x">
                                    <i class="fal fa-hand-receiving"></i>
                                    Items
                                </a>
                            </li>
                            <li>
                                <a href="/selling_extras"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-hand-receiving"></i>
                                    Selling Extras
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('uom-conversion')">
                                <a href="/uoms" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-weight"></i>
                                    Units of Measurement (UOM)
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('uom-conversion')">
                                <a href="/uom_conversions" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-balance-scale-right"></i>
                                    UOM Conversions
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('accessory')">
                                <a href="/accessories" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-microphone-stand"></i>
                                    Accessories
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('supplier')">
                                <a href="/suppliers"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-gifts"></i>
                                    Suppliers
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="tab-hr">
                        <ul v-show="hasAnyPermission([
                            'staff', 'okr-duty', 'meeting', 'training', 'org-new', 'warning',
                            'check-in', 'leave', 'exit-pass', 'resignation',
                            'shift-assignment', 'equipment-assignment', 'complaint'
                          ])">
                            <li>
                                <p class="sidebar-tab-title">
                                    HR
                                </p>
                            </li>
                            <li v-show="checkFeaturePermission('staff')">
                                <a href="/staff" class="flex items-center sidebar-gap-x">
                                    <i class="far fa-user"></i>
                                    Staff
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('okr-duty')">
                                <a href="/okr_assign" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-poll-people"></i>
                                    OKR Assign
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('shift-assignment')">
                                <a href="/shift_assignment" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-door-open"></i>
                                    shift Assignment
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('meeting')">
                                <a href="/meeting" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-users"></i>
                                    Meeting
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('training')">
                                <a href="/training" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-chalkboard-teacher"></i>
                                    Training
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('org-new')">
                                <a href="/org_news" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-newspaper"></i>
                                    OrgNews
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('warning')">
                                <a href="/warning" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-engine-warning"></i>
                                    Warning
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('check-in')">
                                <a href="/check_in" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-user-check"></i>
                                    Check In
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('leave')">
                                <a href="/leave" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-bed"></i>
                                    Leave
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('exit-pass')">
                                <a href="/exit_pass" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-door-open"></i>
                                    Exit Pass
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('resignation')">
                                <a href="/resignations" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-user-alt-slash"></i>
                                    Resignation
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('equipment-assignment')">
                                <a href="/equipment_assignment"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-door-open"></i>
                                    Equipment Assignment
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('complaint')">
                                <a href="/complaints" class="flex items-center @yield('complains') sidebar-gap-x">
                                    <i class="fal fa-envelope-open-text"></i>
                                    Complaints
                                </a>
                            </li>
                            <li>
                                <a href="/advances" class="flex items-center @yield('advances') sidebar-gap-x">
                                    <i class="fal fa-envelope-open-text"></i>
                                    Advances
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="tab-salary">
                        <ul v-show="hasAnyPermission([
                            'salary-batch', 'salary-calculate', 'pay-slip'
                          ])">
                            <li>
                                <p class="sidebar-tab-title">
                                    SALARY
                                </p>
                            </li>
                            <li v-show="checkFeaturePermission('salary-batch')">
                                <a href="/salary_batch" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-door-open"></i>
                                    Salary Batch
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('salary-calculate')">
                                <a href="/salary_calculate" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-calculator-alt"></i>
                                    Salary Calculate
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('pay-slip')">
                                <a href="/pay_slip" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-file-invoice-dollar"></i>
                                    Pay Slip
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="tab-hr-setup">
                        <ul v-show="hasAnyPermission([
                            'jd','js','sop','cv','okr','time-shift','gps','contact','off-day','leave-allowance',
                            'overtime-fee','salary-setup','salary','resignation-categories','skill','handbook',
                            'overtime-confirmation','allowance'
                          ])">
                            <li>
                                <p class="sidebar-tab-title">
                                    HR SETUP
                                </p>
                            </li>
                            <li v-show="checkFeaturePermission('jd')">
                                <a href="/JD" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-layer-group"></i>
                                    JD
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('js')">
                                <a href="/JS" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-briefcase"></i>
                                    JS
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('sop')">
                                <a href="/SOP" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-door-open"></i>
                                    SOP
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('cv')">
                                <a href="/cv" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-door-open"></i>
                                    CV
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('okr')">
                                <a href="/OKR" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-poll-h"></i>
                                    OKR
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('time-shift')">
                                <a href="/time_shift" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-user-clock "></i>
                                    Time Shift
                                </a>
                            </li>
                            <li>
                                <a href="/shift" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-user-clock "></i>
                                    Shift
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('gps')">
                                <a href="/gps" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-map-marker-alt "></i>
                                    GPS
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('contact')">
                                <a href="/contact" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-address-card"></i>
                                    Contact
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('off-day')">
                                <a href="/off_day" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-calendar-minus"></i>
                                    Off Day
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('leave-allowance')">
                                <a href="/leave_allowance" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-wallet"></i>
                                    Leave Allowance
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('allowance')">
                                <a href="/allowance" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-wallet"></i>
                                    Allowance
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('overtime-fee')">
                                <a href="/overtime_fees" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-money-check-edit"></i>
                                    Overtime Fees
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('overtime-confirmation')">
                                <a href="/overtime_confirmation"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-clock"></i>
                                    Overtime
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('salary-setup')">
                                <a href="/salary_setup" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-money-bill-wave"></i>
                                    Salary Setup
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('salary')">
                                <a href="/salary" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-hand-holding-usd"></i>
                                    Salary
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('resignation-categories')">
                                <a href="/resignation_categories"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-door-open"></i>
                                    Resignation Categories
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('skill')">
                                <a href="/skill" class="flex items-center sidebar-gap-x">
                                    <i class="far fa-award"></i>
                                    Skill
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('handbook')">
                                <a href="/handbooks" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-project-diagram "></i>
                                    Handbooks
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="tab-menu">
                        <ul v-show="hasAnyPermission([
                            'menu-category','menu-sale-report','menu-costing','menu','menu-area'
                          ])">
                            <li>
                                <p class="sidebar-tab-title">
                                    MENU
                                </p>
                            </li>
                            <li v-show="checkFeaturePermission('menu-category')">
                                <a href="/menu_categories"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-clipboard-list"></i>
                                    Menu Categories
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('menu-sale-report')">
                                <a href="/menu_sale_report"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-file-chart-line"></i>
                                    Menu Sale Report
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('menu-costing')">
                                <a href="/menu_costing"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-file-signature"></i>
                                    Menu Costing
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('menu')">
                                <a href="/mrp" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-salad"></i>
                                    MRP
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('menu-area')">
                                <a href="/menu_area" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-utensils-alt"></i>
                                    Menu Area
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="tab-promotion">
                        <ul v-show="hasAnyPermission([
                            'room-discount','menu-service-discount','package','customer',
                            'customer-birthday','customer-level-discount','customer-birthday-promotion'
                          ])">
                            <li>
                                <p class="sidebar-tab-title">
                                    PROMOTION & CRM
                                </p>
                            </li>
                            <li v-show="checkFeaturePermission('room-discount')">
                                <a href="/room_discounts"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-badge-percent"></i>
                                    Room Discount
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('menu-service-discount')">
                                <a href="/menu_service_discounts"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-user-tag"></i>
                                    Menu Service Discount
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('package')">
                                <a href="/packages"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-box"></i>
                                    Packages
                                </a>
                            </li>
                            <li  v-show="hasAnyPermission([
                                'customer','customer-birthday','customer-level-discount','customer-birthday-promotion'
                              ])">
                                <button class="flex items-center my-2 text-sm sidebar-gap-x" type="button" data-te-collapse-init
                                    data-te-ripple-init data-te-ripple-color="light" data-te-target="#collapseCRM"
                                    aria-expanded="false" aria-controls="collapseExample">
                                    <i class="fal fa-sack-dollar w-6 text-left"></i>
                                    CRM
                                </button>
                                <div class="!visible hidden text-center" id="collapseCRM" data-te-collapse-item>
                                    <ul>
                                        <li v-show="checkFeaturePermission('customer')">
                                            <a href="/crm/customers"
                                                class="flex items-center sidebar-gap-x !px-0">
                                                <i class="fal fa-user-friends"></i>
                                                Customers
                                            </a>
                                        </li>
                                        <li v-show="checkFeaturePermission('customer-birthday')">
                                            <a href="/crm/customers/birthdays"
                                                class="flex items-center sidebar-gap-x !px-0">
                                                <i class="fal fa-tbirthday-cake"></i>
                                                Customer Birthdays
                                            </a>
                                        </li>
                                        <li v-show="checkFeaturePermission('customer-level-discount')">
                                            <a href="/crm/level_discounts"
                                                class="flex items-center sidebar-gap-x !px-0">
                                                <i class="fal fa-user-tag"></i>
                                                Customer Level Discounts
                                            </a>
                                        </li>
                                        <li v-show="checkFeaturePermission('customer-birthday-promotion')">
                                            <a href="/crm/birthday_promotions"
                                                class="flex items-center sidebar-gap-x !px-0">
                                                <i class="fal fa-stopwatch-20"></i>
                                                Birthday Promotions
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="tab-financial">
                        <ul v-show="hasAnyPermission([
                            'cashbook','journal','staff-balance','prepaid','account',
                            'financial-transaction','asset-item','asset','fix-asset',
                            'ap-balance','ar','loan','accrual'
                            ])">
                            <li>
                                <p class="sidebar-tab-title">
                                    FINANCIAL
                                </p>
                            </li>
                            <li v-show="checkFeaturePermission('cashbook')">
                                <button class="flex items-center pl-9 my-2 text-sm sidebar-gap-x" type="button"
                                    data-te-collapse-init data-te-ripple-init data-te-ripple-color="light"
                                    data-te-target="#collapseCashbooks" aria-expanded="false" aria-controls="collapseExample">
                                    <!-- <img class=" sidebar-img" src="{{ asset('img/cash_book.png') }}" alt=""> -->
                                    <i class="fal fa-sack-dollar"></i>
                                    Cash Book
                                </button>
                                <div class="!visible hidden text-center" id="collapseCashbooks" data-te-collapse-item>
                                    <ul>
                                        <li>
                                            <a href="/cashbook/office"
                                                class="flex items-center ">
                                                <i class="fal fa-tasks  pr-3"></i>
                                                Office Cash Book
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/cashbook/owner" class="flex items-center ">
                                                <i class="fal fa-tasks  pr-3"></i>
                                                Owner Cash Book
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/cashbook/service"
                                                class="flex items-center ">
                                                <i class="fal fa-tasks  pr-3"></i>
                                                Service Cash Book
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/cashbook/advance"
                                                class="flex items-center ">
                                                <i class="fal fa-tasks  pr-3"></i>
                                                Advance Cash Book
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/cashbook/agm" class="flex items-center ">
                                                <i class="fal fa-tasks  pr-3"></i>
                                                AGM Cash Book
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/cashbook/gm" class="flex items-center ">
                                                <i class="fal fa-tasks  pr-3"></i>
                                                GM Cash Book
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/cashbook/ktv_project"
                                                class="flex items-center ">
                                                <i class="fal fa-tasks  pr-3"></i>
                                                KTV Project Cash Book
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="/cashbook_history"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-tasks"></i>
                                    Cash Book History
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('journal')">
                                <a href="/journals" class="flex items-center  sidebar-gap-x">
                                    <i class="fal fa-books"></i>
                                    Journal
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('staff-balance')">
                                <a href="/advanced" class="flex items-center  sidebar-gap-x">
                                    <i class="fal fa-balance-scale-right"></i>
                                    Staff Balance
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('prepaid')">
                                <a href="/prepaid" class="flex items-center sidebar-gap-x">
                                    <i class="fas fa-dollar-sign"></i>
                                    Prepaid
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('account')">
                                <a href="/accounting"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-file-user"></i>
                                    Chart of Accounts (COA)
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('financial-transaction')">
                                <a href="/financial_transaction"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-file-invoice-dollar"></i>
                                    Financial Transactions
                                </a>
                            </li>

                            <li>
                                <button class="flex items-center pl-9 my-2 text-sm sidebar-gap-x" type="button"
                                    data-te-collapse-init data-te-ripple-init data-te-ripple-color="light"
                                    data-te-target="#collapseBankbooks" aria-expanded="false" aria-controls="collapseExample">
                                    <!-- <img class=" sidebar-img" src="{{ asset('img/bank_book.png') }}" alt=""> -->
                                    <i class="fal fa-money-check-alt  pr-3"></i>
                                    Bank Book
                                </button>

                                <div class="!visible hidden text-center" id="collapseBankbooks" data-te-collapse-item>
                                    <ul>
                                        <li>
                                            <a href="/bankbook/kbz_special_md_gm"
                                                class="flex items-center ">
                                                <i class="fal fa-tasks  pr-3"></i>
                                                KBZ Special Account
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/bankbook/kbz_old_gm"
                                                class="flex items-center ">
                                                <i class="fal fa-tasks  pr-3"></i>
                                                KBZ Old Account (GM)
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/bankbook/kpay" class="flex items-center ">
                                                <i class="fal fa-tasks  pr-3"></i>
                                                KPay
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li v-show="checkFeaturePermission('asset-item')">
                                <a href="/asset_items"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-boxes"></i>
                                    Asset Items
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('asset')">
                                <a href="/assets" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-briefcase"></i>
                                    Assets
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('fix-asset')">
                                <a href="/fixed_assets"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-building"></i>
                                    Fixed Assets
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('ap-balance')">
                                <a href="/account_payables"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-envelope-open-dollar"></i>
                                    AP
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('ar')">
                                <a href="/account_receivable"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fas fa-coins"></i>
                                    AR
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('loan')">
                                <a href="/loans" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-door-open"></i>
                                    Loans
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('accrual')">
                                <a href="/accruals" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-door-open"></i>
                                    Accruals
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="tab-mrp">
                        <ul v-show="hasAnyPermission([
                            'menu','ktv-product-tree','menu-forecasting','ktv-forecasting'
                            ])">
                            <li>
                                <p class="sidebar-tab-title">
                                    MRP
                                </p>
                            </li>
                            <li v-show="checkFeaturePermission('menu')">
                                <a href="/mrp" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-salad"></i>
                                    MRP
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('ktv-product-tree')">
                                <a href="/ktv_product_tree" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-project-diagram "></i>
                                    Product Tree
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('menu-forecasting')">
                                <a href="/menu_forecasting" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-chart-line "></i>
                                    Menu Forecasting
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('ktv-forecasting')">
                                <a href="/ktv_forecasting" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-chart-area "></i>
                                    KTV Forecasting
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="tab-order">
                        <ul v-show="hasAnyPermission([
                            'purchase-order','confirm-purchase-order-item.list','purchase-order-item-left',
                            'po-order','arrival-item','po-order-invoice'
                            ])">
                            <li>
                                <p class="sidebar-tab-title">
                                    ORDER
                                </p>
                            </li>
                            <li v-show="checkFeaturePermission('purchase-order')">
                                <a href="/purchase_orders"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-shopping-bag"></i>
                                    Purchase Orders
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('confirm-purchase-order-item.list')">
                                <a href="/confirm_purchase_order_items"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-cash-register"></i>
                                    Confirm Purchase Order Items
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('purchase-order')">
                                <a href="/purchase_order_left_items"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-cash-register"></i>
                                    Purchase Orders with Left Items
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('po-order')">
                                <a href="/procurement_order_items"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-box-open"></i>
                                    Procurement Order Items
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('arrival-item')">
                                <a href="/arrival_items"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-truck-loading"></i>
                                    Arrival Items
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('po-order-invoice')">
                                <a href="/purchase_order_invoices"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-file-invoice  pr-3"></i>
                                    Purchase Order Invoices
                                </a>
                            </li>
                            <li>
                                <a href="/lead_time" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-braille"></i>
                                    Lead Time
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="tab-inventory">
                        <ul v-show="hasAnyPermission([
                            'inventory-stock','inventory-transfer-history','inventory-transfer-receive','inventory-transfer','used-defected-item'
                            ])">
                            <li>
                                <p class="sidebar-tab-title">
                                    INVENTORY
                                </p>
                            </li>
                            <li v-show="checkFeaturePermission('inventory-stock')">
                                <a href="/inventory_stocks"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-warehouse  pr-3"></i>
                                    Inventory Stocks
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('inventory-transfer-history')">
                                <a href="/inventory_transfers"
                                    class="flex items-center  sidebar-gap-x">
                                    <i class="fal fa-history  pr-3"></i>
                                    Inventory Transfer Histories
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('inventory-receive')">
                                <a href="/inventory_receives_list"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-clipboard-list-check  pr-3"></i>
                                    Inventory Receives List
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('inventory-transfer')">
                                <a href="/inventory_transfers_list"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-person-carry  pr-3"></i>
                                    Inventory Transfers List
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('used-defected-item')">
                                <a href="/used_defected_items"
                                    class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-sensor-alert"></i>
                                    Used Defected Items
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="tab-okr">
                        <ul v-show="hasAnyPermission([
                            'okr-dashboard','event','asset-assignment'
                            ])">
                            <li>
                                <p class="sidebar-tab-title">
                                    OKR
                                </p>
                            </li>
                            <li v-show="checkFeaturePermission('okr-dashboard')">
                                <a href="/okr_dashboard" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-poll"></i>
                                    OKR Dashboard
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('event')">
                                <a href="/events" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-door-open"></i>
                                    Event
                                </a>
                            </li>
                            <li v-show="checkFeaturePermission('asset-assignment')">
                                <a href="/asset_assignment" class="flex items-center sidebar-gap-x">
                                    <i class="fal fa-door-open"></i>
                                    Asset Assignment
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="logout"
                class="absolute bottom-0 left-0 w-[336px] h-14 bg-[#fdfdfd] border-t border-[#0002] flex items-center justify-center overflow-hidden">
                <logout-component />
            </div>
        </div>
    </nav>

</template>

<script>
    import { Modal, Ripple, Select, Datepicker, initTE, Input, Tab, Collapse } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";


    export default{
        data() {
            return {

                feature: this.getFeature(),
            };
        },

        methods: {
            ...mapGetters(['getUser', 'getDepartment', 'getToken', 'getRoles', 'getFeature']),

            checkFeaturePermission(feature) {
                return this.feature.includes(feature);
            },
            hasAnyPermission(selectedFeatures) {
                return selectedFeatures.some(p => this.feature.includes(p));
            }

        },

        created(){

        },

        mounted(){
            initTE({ Tab, Modal, Select, Ripple, Collapse });


        },
    }
</script>
