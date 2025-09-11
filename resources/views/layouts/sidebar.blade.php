<nav id="sidebar_admin" class="side-bar w-fit pt-0 h-[100vh]">
    <div class="relative">
        <button type="button" id="toggleBtn"
            class="py-3 px-2 absolute left-full top-8 bg-[#fafafa] text-black rounded-tr-md rounded-br-md border-gray-400 ">
            <i class="fas fa-chevron-double-left ease-linear" style="transition:transform 0.5s ease;"></i>
        </button>



        <!-- <input type="text" id="sidebar-search" placeholder="Search links..." class="mx-2 mt-4 rounded-md"> -->

        <div class="relative pb-20 small-scrollbar h-[100vh]" onmouseover="this.style.overflowY='scroll'"
            onmouseout="this.style.overflowY='hidden'">


            <div class="relative w-[256px] pt-12">
                <ul class=" mb-4">
                    @if (checkMultipleFeaturePermission(['cash-flow', 'indirect-cash-flow', 'working-capital', 'ap-balance']))
                        <li>
                            <button class="flex items-center pl-9 my-2 text-sm w-full" type="button" data-te-collapse-init
                                data-te-ripple-init data-te-ripple-color="light" data-te-target="#collapseFinanceReport"
                                aria-expanded="false" aria-controls="collapseExample">
                                <i class="fal fa-chart-line  pr-3"></i>
                                Finance Report <i class="fas fa-angle-down absolute right-2"></i>
                            </button>
                            <div class="!visible @yield('cash_flow_collapse')hidden text-center" id="collapseFinanceReport"
                                data-te-collapse-item>
                                <ul>
                                    @if (checkFeaturePermission('cash-flow'))

                                        <li>
                                            <a href="/cash_flow_statement"
                                                class="flex items-center text-left @yield('cash_flow_statement')">
                                                <i class="fal fa-tasks  pr-3"></i>
                                                Cash Flow
                                            </a>
                                        </li>
                                    @endif
                                    @if (checkFeaturePermission('indirect-cash-flow'))

                                        <li>
                                            <a href="/indirect_cashflow_statement"
                                                class="flex items-center text-left @yield('indirect_cashflow_statement')">
                                                <i class="fal fa-tasks  pr-3"></i>
                                                Indirect Cash Flow
                                            </a>
                                        </li>
                                    @endif
                                    @if (checkFeaturePermission('working-capital'))

                                        <li>
                                            <a href="/working_capital"
                                                class="flex items-center text-left @yield('working_capital')">
                                                <i class="fal fa-braille pr-3"></i>
                                                Working Capital
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                            <a href="/profit_and_loss"
                                                class="flex items-center text-left @yield('profit_and_loss')">
                                                <i class="fal fa-braille pr-3"></i>
                                                Profit And Loss
                                            </a>
                                    </li>
                                    <li>
                                            <a href="/trial_balance"
                                                class="flex items-center text-left @yield('trial_balance')">
                                                <i class="fal fa-braille pr-3"></i>
                                                Trial Balance
                                            </a>
                                    </li>
                                    <li>
                                            <a href="/sale_ledger"
                                                class="flex items-center text-left @yield('sale_ledger')">
                                                <i class="fal fa-braille pr-3"></i>
                                                Sale Ledger
                                            </a>
                                    </li>
                                    <li>
                                            <a href="/sale_ledger_ktv"
                                                class="flex items-center text-left @yield('sale_ledger_ktv')">
                                                <i class="fal fa-braille pr-3"></i>
                                                Sale Ledger Ktv
                                            </a>
                                    </li>
                                    <li>
                                            <a href="/sale_ledger_restaurant"
                                                class="flex items-center text-left @yield('sale_ledger_restaurant')">
                                                <i class="fal fa-braille pr-3"></i>
                                                Sale Ledger Restaurant
                                            </a>
                                    </li>
                                    @if (checkFeaturePermission('ap-balance'))

                                        <li>
                                            <a href="/ap_balances" class="flex items-center text-left @yield('ap_balances')">
                                                <i class="fal fa-braille pr-3"></i>
                                                AP Balance
                                            </a>
                                        </li>
                                    @endif
                                    @if (checkFeaturePermission('creditor-balance'))

                                        <li>
                                            <a href="/creditor_balances"
                                                class="flex items-center text-left @yield('creditor_balances')">
                                                <i class="fal fa-braille pr-3"></i>
                                                Creditor Balance
                                            </a>
                                        </li>
                                    @endif
                                    @if (checkFeaturePermission('asset-depreciation-balance'))
                                        <li>
                                            <a href="/asset_depreciation_balance_list"
                                                class="flex items-center text-left @yield('asset_depreciation_balance_list')" >
                                                                                               <i class="fal fa-braille pr-3"></i>

                                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-balance-list-50.png') }}" alt=""> -->
                                                Current Asset Depreciation
                                            </a>
                                        </li>
                                    @endif
                                    @if (checkFeaturePermission('asset-depreciation-balance'))
                                        <li>
                                            <a href="/fix_asset_depreciation"
                                                class="flex items-center text-left @yield('fix_asset_depreciation')" >
                                                                                               <i class="fal fa-braille pr-3"></i>

                                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-balance-list-50.png') }}" alt=""> -->
                                                Fix Asset Depreciation
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if (checkFeaturePermission('sale-target-menu') || checkFeaturePermission('sale-target-position'))
                        <li>
                            <button class="flex items-center pl-9 my-2 text-sm w-full" type="button" data-te-collapse-init
                                data-te-ripple-init data-te-ripple-color="light" data-te-target="#collapseSaleTargetReport"
                                aria-expanded="false" aria-controls="collapseExample">
                                <i class="fal fa-chart-line  pr-3"></i>
                                Sale Target <i class="fas fa-angle-down absolute right-2"></i>
                            </button>
                            <div class="!visible @yield('sale_target')hidden text-center" id="collapseSaleTargetReport"
                                data-te-collapse-item>
                                <ul>
                                    @if (checkFeaturePermission('sale-target-menu'))
                                        <li>
                                            <a href="/sale_target_menu"
                                                class="flex items-center text-left @yield('sale_target_menu')">
                                                <i class="fal fa-tasks  pr-3"></i>
                                                Menu
                                            </a>
                                        </li>
                                    @endif
                                    @if (checkFeaturePermission('sale-target-position'))
                                        <li>
                                            <a href="/sale_target_position"
                                                class="flex items-center text-left @yield('sale_target_position')">
                                                <i class="fal fa-tasks  pr-3"></i>
                                                Position
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif

                    <!-- @if (checkFeaturePermission('cash-flow-statement'))
-->

                    <!--
@endif -->




                    @if (checkFeaturePermission('duty'))
                        <li>
                            <a href="{{ route('duty') }}" class="flex items-center @yield('duty') sidebar-gap-x">
                                <i class="far fa-clipboard-list"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-list-50.png') }}" alt=""> -->
                                Duty
                            </a>
                        </li>
                    @endif

                    @if (checkFeaturePermission('staff'))
                        <li>
                            <a href="{{ route('staff') }}" class="flex items-center @yield('staffs') sidebar-gap-x">
                                <i class="far fa-user"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-staff-16.png') }}" alt=""> -->
                                Staff
                            </a>
                        </li>
                    @endif
                    <!-- @if (checkFeaturePermission('mrp'))
<li>
                            <a href="{{ route('MRP') }}" class="flex items-center @yield('mrp')">
                                <i class="fal fa-clipboard-list  pr-3"></i>
                                MRP
                            </a>
                        </li>
@endif -->
                    @if (checkFeaturePermission('menu-category') || checkFeaturePermission('menu-sale-report') || checkFeaturePermission('menu-costing') || checkFeaturePermission('menu') || checkFeaturePermission('menu-area'))

                        <li>
                            <p class="text-[#2a3547] text-xs font-semibold ml-1">
                                MENU
                            </p>
                        </li>
                        @if (checkFeaturePermission('menu-category'))
                            <li>
                                <a href="{{ route('menu_categories') }}"
                                    class="flex items-center @yield('menu_categories') sidebar-gap-x">
                                    <i class="fal fa-clipboard-list"></i>
                                    <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-menu-50 (2).png') }}"
                                                                                alt=""> -->
                                    Menu Categories
                                </a>
                            </li>
                        @endif

                        <!-- <li>
                                                                <a href="{{ route('menus') }}" class="flex items-center @yield('menus')">
                                                                    <i class="fal fa-clipboard-list  pr-3"></i>
                                                                    Selling Menus
                                                                </a>
                                                            </li> -->
                        @if (checkFeaturePermission('menu-sale-report'))
                            <li>
                                <a href="{{ route('menu_sale_report.index') }}"
                                    class="flex items-center @yield('menu_sale_report') sidebar-gap-x">
                                    <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-report-50.png') }}" alt=""> -->
                                    <i class="fal fa-file-chart-line"></i>
                                    Menu Sale Report
                                </a>
                            </li>
                        @endif
                        @if (checkFeaturePermission('menu-costing'))
                            <li>
                                <a href="{{ route('menu_costing.index') }}"
                                    class="flex items-center @yield('menu_costing') sidebar-gap-x">
                                    <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-estimate-50.png') }}"
                                                                                alt=""> -->
                                    <i class="fal fa-file-signature"></i>
                                    Menu Costing
                                </a>
                            </li>
                        @endif
                        @if (checkFeaturePermission('menu'))
                            <li>
                                <a href="{{ route('MRP') }}" class="flex items-center @yield('mrp') sidebar-gap-x">
                                    <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-mrp-50.png') }}" alt=""> -->
                                    <i class="fal fa-salad"></i>
                                    MRP
                                </a>
                            </li>
                        @endif
                        @if (checkFeaturePermission('menu-area'))
                            <li>
                                <a href="/menu_area" class="flex items-center @yield('menu_area') sidebar-gap-x">
                                    <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}"
                                                                                alt=""> -->
                                    <i class="fal fa-utensils-alt"></i>
                                    Menu Area
                                </a>
                            </li>
                        @endif
                    @endif

                    <!-- @if (checkFeaturePermission('room'))
<li>
                            <a href="{{ route('room') }}" class="flex items-center @yield('room') sidebar-gap-x">
                                <img class=" sidebar-img" src="{{ asset('img/icons8-microphone-20.png') }}" alt="">
                                Room
                            </a>
                        </li>
@endif -->
                    @if (checkFeaturePermission('journal') || checkFeaturePermission('staff-balance') || checkFeaturePermission('prepaid'))
                        <li>
                            <p class="sidebar-title">
                                JOURNAL
                            </p>
                        </li>
                    @endif

                    @if (checkFeaturePermission('journal'))
                        <li>
                            <a href="{{ route('journal') }}" class="flex items-center @yield('journals') sidebar-gap-x">
                                <i class="fal fa-books"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-journal-48.png') }}"
                                                                                                    alt=""> -->
                                Journal
                            </a>
                        </li>
                    @endif

                    @if (checkFeaturePermission('staff-balance'))
                        <li>
                            <a href="{{ route('advance') }}" class="flex items-center @yield('advanced') sidebar-gap-x">
                                <i class="fal fa-balance-scale-right"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-balance-50.png') }}"
                                                                        alt=""> -->
                                Staff Balance
                            </a>
                        </li>
                    @endif

                    @if (checkFeaturePermission('prepaid'))
                        <li>
                            <a href="{{ route('prepaid') }}" class="flex items-center @yield('prepaid') sidebar-gap-x">
                                <i class="fas fa-dollar-sign"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-prepaid-50.png') }}"
                                                                        alt=""> -->
                                Prepaid
                            </a>
                        </li>
                    @endif

                    @if (checkFeaturePermission('ar') || checkFeaturePermission('skill') || checkFeaturePermission('cooking-place') || checkFeaturePermission('department') || checkFeaturePermission('role'))
                        <li>
                            <p class="sidebar-title">
                                SAMPLE
                            </p>
                        </li>
                    @endif


                    @if (checkFeaturePermission('skill'))
                        <li>
                            <a href="{{ route('skill') }}" class="flex items-center @yield('skill') sidebar-gap-x">
                                <!-- <img class=" sidebar-img" src="{{ asset('img/skill.png') }}" alt=""> -->
                                <i class="far fa-award"></i>
                                Skill
                            </a>
                        </li>
                    @endif

                    @if (checkFeaturePermission('cooking-place'))
                        <li>
                            <a href="{{ route('cookingPlace') }}"
                                class="flex items-center @yield('cooking_place') sidebar-gap-x">
                                <i class="far fa-hat-chef"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-cooking-50.png') }}"
                                                                        alt=""> -->
                                Cooking Place
                            </a>
                        </li>
                    @endif

                    @if (checkFeaturePermission('department'))
                        <li>
                            <a href="{{ route('departments') }}"
                                class="flex items-center @yield('departments') sidebar-gap-x">
                                <i class="fal fa-network-wired"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-department-50.png') }}"
                                                                        alt=""> -->
                                Department
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('role'))
                        <li>
                            <a href="{{ route('roles') }}" class="flex items-center @yield('roles') sidebar-gap-x">
                                <i class="fal fa-tasks"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-role-50.png') }}" alt=""> -->
                                Roles
                            </a>
                        </li>
                    @endif

                    @if (checkFeaturePermission('task') || checkFeaturePermission('custom-task') || checkFeaturePermission('report-task'))
                        <li>
                            <p class="sidebar-title">
                                TASKS
                            </p>
                        </li>
                    @endif

                    @if (checkFeaturePermission('task'))
                        <li>
                            <a href="{{ route('tasks') }}" class="flex items-center @yield('tasks') sidebar-gap-x">
                                <i class="fal fa-tasks"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-task-50.png') }}" alt=""> -->
                                Tasks
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('custom-task'))
                        <li>
                            <a href="{{ route('custom_tasks') }}"
                                class="flex items-center @yield('custom_tasks') sidebar-gap-x">
                                <i class="fal fa-tasks"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-task-50.png') }}" alt=""> -->
                                Custom Tasks
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('report-task'))
                        <li>
                            <a href="{{ route('task_report') }}"
                                class="flex items-center @yield('tasks_reports') sidebar-gap-x">
                                <i class="fal fa-tasks"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-task-50.png') }}" alt=""> -->
                                Report Tasks
                            </a>
                        </li>
                    @endif


                    @if (checkFeaturePermission('area.list'))
                        <li>
                            <a href="{{ route('areas') }}" class="flex items-center @yield('areas') sidebar-gap-x">
                                <i class="fal fa-network-wired"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-area-50.png') }}" alt=""> -->
                                Areas
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('brand'))
                        <li>
                            <a href="{{ route('brands') }}" class="flex items-center @yield('brands') sidebar-gap-x">
                                <i class="fal fa-copyright"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-brand-48.png') }}" alt=""> -->
                                Brands
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('item') || checkFeaturePermission('uom-conversion'))

                        <li>
                            <p class="sidebar-title">
                                ITEM UOM
                            </p>
                        </li>
                        @if (checkFeaturePermission('item'))
                            <li>
                                <a href="{{ route('items') }}" class="flex items-center @yield('items') sidebar-gap-x">
                                    <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-item-48.png') }}" alt=""> -->
                                    <i class="fal fa-hand-receiving"></i>
                                    Items
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('selling_extras') }}"
                                    class="flex items-center @yield('selling_extras') sidebar-gap-x">
                                    <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-item-48.png') }}" alt=""> -->
                                    <i class="fal fa-hand-receiving"></i>
                                    Selling Extras
                                </a>
                            </li>
                        @endif
                        @if (checkFeaturePermission('uom-conversion'))
                            <li>
                                <a href="{{ route('uoms') }}" class="flex items-center @yield('uom_conversions') sidebar-gap-x">
                                    <i class="fal fa-balance-scale"></i>
                                    <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-unit-48.png') }}" alt=""> -->
                                    UOMs
                                </a>
                            </li>
                        @endif
                    @endif

                    @if (checkFeaturePermission('item-usage-forecast'))
                        {{-- <li>
                            <a href="{{ route('item_usage_forecasts') }}"
                                class="flex items-center @yield('item_usage_forecasts')">
                                <i class="fal fa-truck-loading  pr-3"></i>
                                Item Usage Forecasts
                            </a>
                        </li> --}}

                        <li>
                            <a href="{{ route('item_usage_forecasts_by_month') }}"
                                class="flex items-center @yield('item_usage_forecasts_month') sidebar-gap-x">
                                <i class="fal fa-truck-loading"></i>
                                Item Usage Forecasts By Month
                            </a>
                        </li>
                    @endif

                    <!-- @if (checkFeaturePermission('menu'))
<li>
                            <a href="{{ route('menu_categories') }}" class="flex items-center @yield('menu_categories')">
                                <i class="fal fa-clipboard-list  pr-3"></i>
                                Menu Categories
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('menus') }}" class="flex items-center @yield('menus')">
                                <i class="fal fa-clipboard-list  pr-3"></i>
                                Selling Menus
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('menu_position') }}" class="flex items-center @yield('menu_position')">
                                <i class="fal fa-clipboard-list  pr-3"></i>
                                Menu Position
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('menu_sale_report.index') }}"
                                class="flex items-center @yield('menu_sale_report') sidebar-gap-x">
                                <img class=" sidebar-img" src="{{ asset('img/icons8-report-50.png') }}" alt="">
                                Menu Sale Report
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('menu_costing.index') }}"
                                class="flex items-center @yield('menu_costing') sidebar-gap-x">
                                <img class=" sidebar-img" src="{{ asset('img/icons8-estimate-50.png') }}" alt="">
                                Menu Costing
                            </a>
                        </li>
@endif -->
                    @if (checkFeaturePermission('room') || checkFeaturePermission('table') || checkFeaturePermission('service'))

                        <li>
                            <p class="sidebar-title">
                                ENTERTAINMENT
                            </p>
                        </li>
                        @if (checkFeaturePermission('room'))
                            <li>
                                <a href="{{ route('room') }}" class="flex items-center @yield('room') sidebar-gap-x">
                                    <i class="fal fa-microphone-alt"></i>
                                    <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-microphone-20.png') }}" alt=""> -->
                                    Room
                                </a>
                            </li>
                        @endif

                        @if (checkFeaturePermission('table'))
                            <li>
                                <a href="{{ route('table') }}" class="flex items-center @yield('table') sidebar-gap-x">
                                    <i class="fal fa-utensils"></i>
                                    <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-table-50.png') }}" alt=""> -->
                                    Table
                                </a>
                            </li>
                        @endif
                        @if (checkFeaturePermission('service'))
                            <li>
                                <a href="{{ route('services') }}" class="flex items-center @yield('services') sidebar-gap-x">
                                    <i class="fal fa-concierge-bell"></i>
                                    <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-service-50.png') }}" alt=""> -->
                                    Services
                                </a>
                            </li>
                        @endif
                    @endif

                    @if (checkFeaturePermission('complaint') || checkFeaturePermission('inventory') || checkFeaturePermission('supplier'))

                        <li>
                            <p class="sidebar-title">
                                SAMPLE
                            </p>
                        </li>
                    @endif
                    @if (checkFeaturePermission('complaint'))
                        <li>
                            <a href="{{ route('complains') }}" class="flex items-center @yield('complains') sidebar-gap-x">
                                <i class="fal fa-envelope-open-text"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-complaint-50.png') }}" alt=""> -->
                                Complaints
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('inventory'))
                        <li>
                            <a href="{{ route('inventories') }}"
                                class="flex items-center @yield('inventories') sidebar-gap-x">
                                <i class="fal fa-inventory  pr-3"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-inventory-50.png') }}" alt=""> -->
                                Inventories
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('supplier'))
                        <li>
                            <a href="{{ route('suppliers.index') }}"
                                class="flex items-center @yield('supplier') sidebar-gap-x">
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-supplier-48.png') }}" alt=""> -->
                                <i class="fal fa-gifts"></i>
                                Suppliers
                            </a>
                        </li>
                    @endif

                    @if (checkFeaturePermission('account') || checkFeaturePermission('financial-transaction') || checkFeaturePermission('cashbook'))
                        <li>
                            <p class="sidebar-title">
                                FINANCIAL
                            </p>
                        </li>
                    @endif
                    @if (checkFeaturePermission('account'))
                        <li>
                            <a href="{{ route('accounting') }}"
                                class="flex items-center @yield('accounting') sidebar-gap-x">
                                <!-- <img class=" sidebar-img" src="{{ asset('img/coa.png') }}" alt=""> -->
                                <i class="fal fa-file-user"></i>
                                Chart of Accounts (COA)
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('financial-transaction'))
                        <li>
                            <a href="{{ route('financial_transactions') }}"
                                class="flex items-center @yield('financial_transactions') sidebar-gap-x">
                                <!-- <img class=" sidebar-img" src="{{ asset('img/financial_transaction.png') }}" alt=""> -->
                                <i class="fal fa-file-invoice-dollar"></i>
                                Financial Transactions
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('cashbook'))
                        <li>
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
                                        <a href="{{ route('office_cash') }}"
                                            class="flex items-center @yield('office_cash')">
                                            <i class="fal fa-tasks  pr-3"></i>
                                            Office Cash Book
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('owner_cash') }}" class="flex items-center @yield('owner_cash')">
                                            <i class="fal fa-tasks  pr-3"></i>
                                            Owner Cash Book
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('service_cash') }}"
                                            class="flex items-center @yield('service_cash')">
                                            <i class="fal fa-tasks  pr-3"></i>
                                            Service Cash Book
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('advance_cash') }}"
                                            class="flex items-center @yield('advance_cash')">
                                            <i class="fal fa-tasks  pr-3"></i>
                                            Advance Cash Book
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('agm_cash') }}" class="flex items-center @yield('agm_cash')">
                                            <i class="fal fa-tasks  pr-3"></i>
                                            AGM Cash Book
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('gm_cash') }}" class="flex items-center @yield('gm_cash')">
                                            <i class="fal fa-tasks  pr-3"></i>
                                            GM Cash Book
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('ktv_project_cash') }}"
                                            class="flex items-center @yield('ktv_project_cash')">
                                            <i class="fal fa-tasks  pr-3"></i>
                                            KTV Project Cash Book
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('cashbook.history') }}"
                                            class="flex items-center @yield('cashbook_history')">
                                            <i class="fal fa-tasks  pr-3"></i>
                                            Cash Book History
                                        </a>
                                    </li>
                                </ul>
                            </div>
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
                                        <a href="{{ route('kbz_special_bank') }}"
                                            class="flex items-center @yield('kbz_special_bank')">
                                            <i class="fal fa-tasks  pr-3"></i>
                                            KBZ Special Account
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('kbz_old_gm_bank') }}"
                                            class="flex items-center @yield('kbz_old_gm_bank')">
                                            <i class="fal fa-tasks  pr-3"></i>
                                            KBZ Old Account (GM)
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('kpay_bank') }}" class="flex items-center @yield('kpay_bank')">
                                            <i class="fal fa-tasks  pr-3"></i>
                                            KPay
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif


                    @if (
                            checkFeaturePermission('purchase-order') || checkFeaturePermission('confirm-purchase-order-item.list')
                            || checkFeaturePermission('purchase-order-item-left') || checkFeaturePermission('po-order')
                            || checkFeaturePermission('arrival-item') || checkFeaturePermission('po-order-invoice')
                        )
                        <li>
                            <p class="sidebar-title">
                                ORDER
                            </p>
                        </li>
                    @endif

                    @if (checkFeaturePermission('purchase-order'))
                        <li>
                            <a href="{{ route('purchase_orders') }}"
                                class="flex items-center @yield('purchase_orders') sidebar-gap-x">
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-order-48.png') }}" alt=""> -->
                                <i class="fal fa-shopping-bag"></i>
                                Purchase Orders
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('confirm-purchase-order-item.list'))
                        <li>
                            <a href="{{ route('purchase_orders.confirm_poitems') }}"
                                class="flex items-center @yield('confirm_purchase_order_items') sidebar-gap-x">
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-shopping-cart-48.png') }}" alt=""> -->
                                <i class="fal fa-cash-register"></i>
                                Confirm Purchase Order Items
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('purchase-order'))
                        <li>
                            <a href="{{ route('purchase_orders.left_items_index') }}"
                                class="flex items-center @yield('purchase_order_left_items') sidebar-gap-x">
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-shopping-cart-48.png') }}" alt=""> -->
                                <i class="fal fa-cash-register"></i>
                                Purchase Orders with Left Items
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('po-order'))
                        <li>
                            <a href="{{ route('procurement_order_items') }}"
                                class="flex items-center @yield('procurement_order_items') sidebar-gap-x">
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-shopping-cart-48.png') }}" alt=""> -->
                                <i class="fal fa-box-open"></i>
                                Procurement Order Items
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('arrival-item'))
                        <li>
                            <a href="{{ route('arrival_items') }}"
                                class="flex items-center @yield('arrival_items') sidebar-gap-x">
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-add-to-shopping-basket-48.png') }}"
                                    alt=""> -->
                                <i class="fal fa-truck-loading"></i>
                                Arrival Items
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('po-order-invoice'))
                        <li>
                            <a href="{{ route('purchase_order_invoices') }}"
                                class="flex items-center @yield('purchase_order_invoices') sidebar-gap-x">
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-shopping-cart-48.png') }}" alt=""> -->
                                <i class="fal fa-file-invoice  pr-3"></i>
                                Purchase Order Invoices
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="/lead_time" class="flex items-center @yield('lead_time') sidebar-gap-x">
                            <i class="fal fa-braille"></i>
                            <!-- <img class="sidebar-img " src="{{ asset('img/icons8-time-50.png') }}" alt=""> -->
                            Lead Time
                        </a>
                    </li>


                    @if (
                            checkFeaturePermission('asset-item') || checkFeaturePermission('asset')
                            || checkFeaturePermission('fixed-asset') || checkFeaturePermission('ap-balance') || checkFeaturePermission('ar')
                        )
                        <li>
                            <p class="sidebar-title">
                                ASSET
                            </p>
                        </li>
                    @endif
                    @if (checkFeaturePermission('asset-item'))
                        <li>
                            <a href="{{ route('assetItemList') }}"
                                class="flex items-center @yield('asset_items') sidebar-gap-x">
                                <i class="fal fa-boxes"></i>
                                Asset Items
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('asset'))
                        <li>
                            <a href="{{ route('assetList') }}" class="flex items-center @yield('assets') sidebar-gap-x">
                                <i class="fal fa-briefcase"></i>
                                Assets
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('fix-asset'))
                        <li>
                            <a href="{{ route('fixed_assets.index') }}"
                                class="flex items-center @yield('fixed_asset') sidebar-gap-x">
                                <i class="fal fa-building"></i>
                                Fixed Assets
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('ap-balance'))
                        <li>
                            <a href="{{ route('AP.index') }}"
                                class="flex items-center @yield('account_payables') sidebar-gap-x">
                                <i class="fal fa-envelope-open-dollar"></i>
                                AP
                            </a>
                        </li>
                    @endif
                    {{-- <li>
                        <a href="{{ route('AP.history') }}" class="flex items-center @yield('ap_history')">
                            <i class="fal fa-truck-loading  pr-3"></i>
                            AP Transactions
                        </a>
                    </li> --}}
                    @if (checkFeaturePermission('ar'))
                        <li>
                            <a href="{{ route('account_receivable') }}"
                                class="flex items-center @yield('ar') sidebar-gap-x">
                                <i class="fas fa-coins"></i>
                                AR
                            </a>
                        </li>
                    @endif


                    @if (
                            checkFeaturePermission('inventory-stock') || checkFeaturePermission('inventory-transfer-history')
                            || checkFeaturePermission('inventory-transfer-receive') || checkFeaturePermission('inventory-transfer')
                        )
                        <li>
                            <p class="sidebar-title">
                                INVENTORY
                            </p>
                        </li>
                    @endif
                    @if (checkFeaturePermission('inventory-stock'))
                        <li>
                            <a href="{{ route('inventory_stocks.index') }}"
                                class="flex items-center @yield('inventory_stocks') sidebar-gap-x">
                                <i class="fal fa-warehouse  pr-3"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-inventory-50.png') }}" alt=""> -->
                                Inventory Stocks
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('inventory-transfer-history'))
                        <li>
                            <a href="{{ route('transfers.index') }}"
                                class="flex items-center @yield('inventory_histories') sidebar-gap-x">
                                <i class="fal fa-history  pr-3"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-inventory-50.png') }}" alt=""> -->
                                Inventory Transfer Histories
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('inventory-receive'))
                        <li>
                            <a href="{{ route('transfers.receives') }}"
                                class="flex items-center @yield('inventory_receives') sidebar-gap-x">
                                <i class="fal fa-clipboard-list-check  pr-3"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-inventory-50.png') }}" alt=""> -->
                                Inventory Receives List
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('inventory-transfer'))
                        <li>
                            <a href="{{ route('transfers.transfers') }}"
                                class="flex items-center @yield('inventory_transfers') sidebar-gap-x">
                                <i class="fal fa-person-carry  pr-3"></i>
                                <!-- <img class=" sidebar-img" src="{{ asset('img/icons8-inventory-50.png') }}" alt=""> -->
                                Inventory Transfers List
                            </a>
                        </li>
                    @endif

                    @if (checkFeaturePermission('used-defected-item'))
                        <li>
                            <a href="{{ route('used_defected_items.index') }}"
                                class="flex items-center @yield('used_defected_items') sidebar-gap-x">
                                <i class="fal fa-sensor-alert"></i>
                                Used Defected Items
                            </a>
                        </li>
                    @endif

                    @if (
                            checkFeaturePermission('room-discount') || checkFeaturePermission('menu-service-discount')
                            || checkFeaturePermission('package') || checkFeaturePermission('inventory-transfer')
                        )
                        <li>
                            <p class="sidebar-title">
                                PROMOTION
                            </p>
                        </li>
                    @endif
                    @if (checkFeaturePermission('room-discount'))
                        <li>
                            <a href="{{ route('room_discount.index') }}"
                                class="flex items-center @yield('room_discount') sidebar-gap-x">
                                <i class="fal fa-badge-percent"></i>
                                Room Discount
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('menu-service-discount'))
                        <li>
                            <a href="{{ route('menu_service_discount.index') }}"
                                class="flex items-center @yield('menu&service_discount') sidebar-gap-x">
                                <i class="fal fa-user-tag"></i>
                                Menu Service Discount
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('package'))
                        <li>
                            <a href="{{ route('packages.index') }}"
                                class="flex items-center @yield('packages') sidebar-gap-x">
                                <i class="fal fa-box"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-packages-50.png') }}" alt=""> -->
                                Packages
                            </a>
                        </li>
                    @endif
                    <li>
                        <button class="flex items-center my-2 text-sm sidebar-gap-x" type="button" data-te-collapse-init
                            data-te-ripple-init data-te-ripple-color="light" data-te-target="#collapseCRM"
                            aria-expanded="false" aria-controls="collapseExample">
                            <i class="fal fa-sack-dollar w-6 text-left"></i>
                            CRM
                        </button>
                        <div class="!visible hidden text-center" id="collapseCRM" data-te-collapse-item>
                            <ul>
                                @if (checkFeaturePermission('customer'))
                                    <li>
                                        <a href="{{ route('crm.customers.index') }}"
                                            class="flex items-center @yield('crm_customer_list') sidebar-gap-x">
                                            <i class="fal fa-user-friends"></i>
                                            Customers
                                        </a>
                                    </li>
                                @endif
                                @if (checkFeaturePermission('customer-birthday'))
                                    <li>
                                        <a href="{{ route('crm.customers.birthdays') }}"
                                            class="flex items-center @yield('customer_birthdays') sidebar-gap-x">
                                            <i class="fal fa-tbirthday-cake"></i>
                                            Customer Birthdays
                                        </a>
                                    </li>
                                @endif
                                @if (checkFeaturePermission('customer-level-discount'))
                                    <li>
                                        <a href="{{ route('crm.level_discounts.index') }}"
                                            class="flex items-center @yield('customer_level_discounts') sidebar-gap-x">
                                            <i class="fal fa-user-tag"></i>
                                            Customer Level Discounts
                                        </a>
                                    </li>
                                @endif
                                @if (checkFeaturePermission('customer-birthday-promotion'))
                                    <li>
                                        <a href="{{ route('crm.birthday_discounts.index') }}"
                                            class="flex items-center @yield('birthday_promotions') sidebar-gap-x">
                                            <i class="fal fa-stopwatch-20"></i>
                                            Birthday Promotions
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>


                    @if (checkFeaturePermission('accessory'))
                        <li>
                            <a href="/accessories" class="flex items-center @yield('accessories') sidebar-gap-x">
                                <i class="fal fa-microphone-stand"></i>
                                Accessories
                            </a>
                        </li>
                    @endif

                    @if (checkFeaturePermission('okr') || checkFeaturePermission('okr-duty') || checkFeaturePermission('okr-dashboard'))
                        <li>
                            <p class="sidebar-title">
                                OKR
                            </p>
                        </li>
                    @endif
                    @if (checkFeaturePermission('okr'))
                        <li>
                            <a href="/OKR" class="flex items-center @yield('OKR') sidebar-gap-x">
                                <i class="fal fa-poll-h"></i>
                                OKR
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('okr-duty'))
                        <li>
                            <a href="/okr_assign" class="flex items-center @yield('okr_assign') sidebar-gap-x">
                                <i class="fal fa-poll-people"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-career-64.png') }}" alt=""> -->
                                OKR Assign
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('okr-dashboard'))
                        <li>
                            <a href="/okr_dashboard" class="flex items-center @yield('okr_dashboard') sidebar-gap-x">
                                <i class="fal fa-poll"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-dashboard-48.png') }}" alt=""> -->
                                OKR Dashboard
                            </a>
                        </li>
                    @endif

                    @if (checkFeaturePermission('ktv-product-tree') || checkFeaturePermission('menu-forecasting') || checkFeaturePermission('ktv-forecasting'))
                        <li>
                            <p class="sidebar-title">
                                FACT
                            </p>
                        </li>
                    @endif
                    @if (checkFeaturePermission('ktv-product-tree'))
                        <li>
                            <a href="/ktv_product_tree" class="flex items-center @yield('ktv_product_tree') sidebar-gap-x">
                                <i class="fal fa-project-diagram "></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-product-tree-64.png') }}" alt=""> -->
                                Product Tree
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('menu-forecasting'))
                        <li>
                            <a href="/menu_forecasting" class="flex items-center @yield('menu_forecasting') sidebar-gap-x">
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-analytics-48.png') }}" alt=""> -->
                                <i class="fal fa-chart-line "></i>
                                Menu Forecasting
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('ktv-forecasting'))
                        <li>
                            <a href="/ktv_forecasting" class="flex items-center @yield('ktv_forecasting') sidebar-gap-x">
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-analytics-48.png') }}" alt=""> -->
                                <i class="fal fa-chart-area "></i>
                                KTV Forecasting
                            </a>
                        </li>
                    @endif

                    @if (checkFeaturePermission('staff') || checkFeaturePermission('staff.create'))
                    <li>
                        <p class="sidebar-title">
                            HR
                        </p>
                    </li>
                    @endif
                    <li>
                        <a href="/handbooks" class="flex items-center @yield('handbook') sidebar-gap-x">
                            <i class="fal fa-project-diagram "></i>
                            <!-- <img class="sidebar-img " src="{{ asset('img/icons8-product-tree-64.png') }}" alt=""> -->
                            Handbooks
                        </a>
                    </li>

                    @if (checkFeaturePermission('time-shift') || checkFeaturePermission('gps') || checkFeaturePermission('check-in'))
                        <li>
                            <p class="sidebar-title">
                                GENERAL
                            </p>
                        </li>
                    @endif
                    @if (checkFeaturePermission('time-shift'))
                        <li>
                            <a href="/time_shift" class="flex items-center @yield('time_shift') sidebar-gap-x">
                                <i class="fal fa-user-clock "></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-time-shift-50.png') }}" alt=""> -->
                                Time Shift
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="/shift" class="flex items-center @yield('shift') sidebar-gap-x">
                            <i class="fal fa-user-clock "></i>
                            <!-- <img class="sidebar-img " src="{{ asset('img/icons8-time-shift-50.png') }}" alt=""> -->
                            Shift
                        </a>
                    </li>
                    @if (checkFeaturePermission('gps'))
                        <li>
                            <a href="/gps" class="flex items-center @yield('gps') sidebar-gap-x">
                                <i class="fal fa-map-marker-alt "></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-gps-48.png') }}" alt=""> -->
                                GPS
                            </a>
                        </li>
                    @endif

                    @if (checkFeaturePermission('check-in'))
                        <li>
                            <a href="/check_in" class="flex items-center @yield('check_in') sidebar-gap-x">
                                <i class="fal fa-user-check"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-check-in-64.png') }}" alt=""> -->
                                Check In
                            </a>
                        </li>
                    @endif

                    @if (checkFeaturePermission('contact'))
                        <li>
                            <a href="/contact" class="flex items-center @yield('contact') sidebar-gap-x">
                                <i class="fal fa-address-card"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-contact-50.png') }}" alt=""> -->
                                Contact
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('meeting'))
                        <li>
                            <a href="/meeting" class="flex items-center @yield('meeting') sidebar-gap-x">
                                <i class="fal fa-users"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-meeting-50.png') }}" alt=""> -->
                                Meeting
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('training'))
                        <li>
                            <a href="/training" class="flex items-center @yield('training') sidebar-gap-x">
                                <i class="fal fa-chalkboard-teacher"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-training-50.png') }}" alt=""> -->
                                Training
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('org-new'))
                        <li>
                            <a href="/org_news" class="flex items-center @yield('org_news') sidebar-gap-x">
                                <i class="fal fa-newspaper"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-news-50.png') }}" alt=""> -->
                                OrgNews
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('warning'))
                        <li>
                            <a href="/warning" class="flex items-center @yield('warning') sidebar-gap-x">
                                <i class="fal fa-engine-warning"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                                Warning
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('off-day'))
                        <li>
                            <a href="/off_day" class="flex items-center @yield('off_day') sidebar-gap-x">
                                <i class="fal fa-calendar-minus"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                                Off Day
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('leave-allowance'))
                        <li>
                            <a href="/leave_allowance" class="flex items-center @yield('leave_allowance') sidebar-gap-x">
                                <i class="fal fa-wallet"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                                Leave Allowance
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('leave'))
                        <li>
                            <a href="/leave" class="flex items-center @yield('leave') sidebar-gap-x">
                                <i class="fal fa-bed"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                                Leave
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('exit-pass'))
                        <li>
                            <a href="/exit_pass" class="flex items-center @yield('exit_pass') sidebar-gap-x">
                                <i class="fal fa-door-open"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                                Exit Pass
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('overtime-fee'))
                        <li>
                            <a href="/overtime_fees" class="flex items-center @yield('overtime_fees') sidebar-gap-x">
                                <i class="fal fa-money-check-edit"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                                Overtime Fees
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('overtime-confirmation'))
                        <li>
                            <a href="/overtime_confirmation"
                                class="flex items-center @yield('overtime_confirmation') sidebar-gap-x">
                                <i class="fal fa-clock"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                                Overtime
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('salary-setup'))
                        <li>
                            <a href="/salary_setup" class="flex items-center @yield('salary_setup') sidebar-gap-x">
                                <i class="fal fa-money-bill-wave"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                                Salary Setup
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('salary'))
                        <li>
                            <a href="/salary" class="flex items-center @yield('salary') sidebar-gap-x">
                                <i class="fal fa-hand-holding-usd"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                                Salary
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('allowance'))
                        <li>
                            <a href="/allowance" class="flex items-center @yield('allowance') sidebar-gap-x">
                                <i class="fal fa-wallet"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                                Allowance
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('salary-batch'))
                        <li>
                            <a href="/salary_batch" class="flex items-center @yield('salary_batch') sidebar-gap-x">
                                <i class="fal fa-door-open"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                                Salary Batch
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('salary-calculate'))
                        <li>
                            <a href="/salary_calculate" class="flex items-center @yield('salary_calculate') sidebar-gap-x">
                                <i class="fal fa-calculator-alt"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                                Salary Calculate
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('pay-slip'))
                        <li>
                            <a href="/pay_slip" class="flex items-center @yield('pay_slip') sidebar-gap-x">
                                <i class="fal fa-file-invoice-dollar"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                                Pay Slip
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('resignation-categories'))
                        <li>
                            <a href="/resignation_categories"
                                class="flex items-center @yield('resignation_categories') sidebar-gap-x">
                                <i class="fal fa-door-open"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                                Resignation Categories
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('resignation'))
                        <li>
                            <a href="/resignations" class="flex items-center @yield('resignations') sidebar-gap-x">
                                <i class="fal fa-user-alt-slash"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                                Resignation
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('event'))
                        <li>
                            <a href="/events" class="flex items-center @yield('event') sidebar-gap-x">
                                <i class="fal fa-door-open"></i>
                                <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                                Event
                            </a>
                        </li>
                    @endif
                    @if (checkFeaturePermission('jd'))
                    <li>
                        <a href="/JD" class="flex items-center @yield('JD') sidebar-gap-x">
                            <i class="fal fa-layer-group"></i>
                            <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                            JD
                        </a>
                    </li>
                    @endif
                    @if (checkFeaturePermission('js'))
                    <li>
                        <a href="/JS" class="flex items-center @yield('js') sidebar-gap-x">
                            <i class="fal fa-briefcase"></i>
                            <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                            JS
                        </a>
                    </li>
                    @endif
                    @if (checkFeaturePermission('sop'))
                    <li>
                        <a href="/SOP" class="flex items-center @yield('SOP') sidebar-gap-x">
                            <i class="fal fa-door-open"></i>
                            <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                            SOP
                        </a>
                    </li>
                    @endif
                    @if (checkFeaturePermission('cv'))
                    <li>
                        <a href="/cv" class="flex items-center @yield('cv') sidebar-gap-x">
                            <i class="fal fa-door-open"></i>
                            <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                            CV
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="/shift_assignment" class="flex items-center @yield('shift_assignment') sidebar-gap-x">
                            <i class="fal fa-door-open"></i>
                            <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                            shift Assignment
                        </a>
                    </li>
                    <li>
                        <a href="/equipment_assignment" class="flex items-center @yield('equipment_assignment') sidebar-gap-x">
                            <i class="fal fa-door-open"></i>
                            <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                            Equipment Assignment
                        </a>
                    </li>
                    <li>
                        <a href="/asset_assignment" class="flex items-center @yield('asset_assignment') sidebar-gap-x">
                            <i class="fal fa-door-open"></i>
                            <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                            Asset Assignment
                        </a>
                    </li>
                    <li>
                        <a href="/accruals" class="flex items-center @yield('accruals') sidebar-gap-x">
                            <i class="fal fa-door-open"></i>
                            <!-- <img class="sidebar-img " src="{{ asset('img/icons8-warning-64.png') }}" alt=""> -->
                            Accruals
                        </a>
                    </li>

                </ul>

            </div>

        </div>
        <div id="app"
            class="absolute bottom-0 left-0 w-[15rem] h-14 bg-[#fdfdfd] border-t border-[#0002] flex items-center justify-start">
            <logout-component />
        </div>
    </div>
</nav>
<!-- <script>
    $(document).ready(function() {
        $("#sidebar-search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#sidebar_admin li").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script> -->