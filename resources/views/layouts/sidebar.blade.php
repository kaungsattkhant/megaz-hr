<nav id="sidebar" class="side-bar w-fit pt-0 h-[100vh]">
    <div class="relative pb-20 overflow-y-hidden small-scrollbar h-[100vh]" onmouseover="this.style.overflowY='scroll'"
        onmouseout="this.style.overflowY='hidden'">

        <div class="relative w-[15rem] pt-12">
            <ul class=" mb-4">
                <!-- @if(checkFeaturePermission('financial-report')) -->

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
                            <li>
                                <a href="/cash_flow_statement"
                                    class="flex items-center text-left @yield('cash_flow_statement')">
                                    <i class="fal fa-tasks  pr-3"></i>
                                    Cash Flow
                                </a>
                            </li>
                            <li>
                                <a href="/indirect_cashflow_statement"
                                    class="flex items-center text-left @yield('indirect_cashflow_statement')">
                                    <i class="fal fa-tasks  pr-3"></i>
                                    Indirect Cash Flow
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- @endif -->
                @if(checkFeaturePermission('sale-target'))
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
                                <li>
                                    <a href="/sale_target_menu"
                                        class="flex items-center text-left @yield('sale_target_menu')">
                                        <i class="fal fa-tasks  pr-3"></i>
                                        Menu
                                    </a>
                                </li>
                                <li>
                                    <a href="/sale_target_position"
                                        class="flex items-center text-left @yield('sale_target_position')">
                                        <i class="fal fa-tasks  pr-3"></i>
                                        Position
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- @if (checkFeaturePermission('cash-flow-statement')) -->

                <!-- @endif -->


                @if(checkFeaturePermission('asset-depreciation-balance'))
                    <li>
                        <a href="{{ route('asset_list') }}"
                            class="flex items-center @yield('asset_depreciation_balance_list')">
                            <i class="fal fa-clipboard-list pr-3"></i>
                            Asset Depreciation Balance List
                        </a>
                    </li>
                @endif

                @if(checkFeaturePermission('duty'))
                    <li>
                        <a href="{{ route('duty') }}" class="flex items-center @yield('duty')">
                            <i class="fal fa-clipboard-list pr-3"></i>
                            Duty
                        </a>
                    </li>
                @endif

                <!-- <<<<<<< HEAD -->
                @if (checkFeaturePermission('staff'))
                    <li>
                        <a href="{{ route('staff') }}" class="flex items-center @yield('staffs')">
                            <i class="fal fa-user  pr-3"></i>
                            Staff
                        </a>
                    </li>
                @endif
                <!-- ======= -->
                <!-- @if (checkFeaturePermission('mrp'))
                    <li>
                        <a href="{{ route('MRP') }}" class="flex items-center @yield('mrp')">
                            <i class="fal fa-clipboard-list  pr-3"></i>
                            MRP
                        </a>
                    </li>
                @endif -->
                @if (checkFeaturePermission('mrp'))
                    <li>
                        <a href="{{ route('menu_categories') }}" class="flex items-center @yield('menu_categories')">
                            <i class="fal fa-clipboard-list  pr-3"></i>
                            Menu Categories
                        </a>
                    </li>
                    <!-- <li>
                        <a href="{{ route('menus') }}" class="flex items-center @yield('menus')">
                            <i class="fal fa-clipboard-list  pr-3"></i>
                            Selling Menus
                        </a>
                    </li> -->
                    <li>
                        <a href="{{ route('menu_sale_report.index') }}"
                            class="flex items-center @yield('menu_sale_report')">
                            <i class="fal fa-clipboard-list  pr-3"></i>
                            Menu Sale Report
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('menu_costing.index') }}" class="flex items-center @yield('menu_costing')">
                            <i class="fal fa-clipboard-list  pr-3"></i>
                            Menu Costing
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('MRP') }}" class="flex items-center @yield('mrp')">
                            <i class="fal fa-clipboard-list  pr-3"></i>
                            MRP
                        </a>
                    </li>
                @endif
                @if (checkFeaturePermission('room'))
                    <li>
                        <a href="{{ route('room') }}" class="flex items-center @yield('room')">
                            <i class="fal fa-microphone-alt  pr-3"></i>
                            Room
                        </a>
                    </li>
                @endif
                <!-- >>>>>>> origin/thhs/okr-backend -->

                @if(checkFeaturePermission('journal'))
                    <li>
                        <a href="{{ route('journal') }}" class="flex items-center @yield('journals')">
                            <i class="fal fa-books pr-3"></i>
                            Journal
                        </a>
                    </li>
                @endif

                @if(checkFeaturePermission('staff-balance'))
                    <li>
                        <a href="{{ route('advance') }}" class="flex items-center @yield('advanced')">
                            <i class="fal fa-balance-scale-right pr-3"></i>
                            Staff Balance
                        </a>
                    </li>
                @endif

                @if(checkFeaturePermission('prepaid'))
                    <li>
                        <a href="{{ route('prepaid') }}" class="flex items-center @yield('prepaid')">
                            <i class="fas fa-dollar-sign"></i>
                            Prepaid
                        </a>
                    </li>
                @endif

                @if(checkFeaturePermission('ar'))
                    <li>
                        <a href="{{ route('account_receivable') }}" class="flex items-center @yield('ar')">
                            <i class="fas fa-coins"></i>
                            AR
                        </a>
                    </li>
                @endif

                @if(checkFeaturePermission('skill'))
                    <li>
                        <a href="{{ route('skill') }}" class="flex items-center @yield('skill')">
                            <i class="far fa-award"></i>
                            Skill
                        </a>
                    </li>
                @endif

                @if(checkFeaturePermission('cooking-place'))
                    <li>
                        <a href="{{ route('cookingPlace') }}" class="flex items-center @yield('cooking_place')">
                            <i class="far fa-hat-chef"></i>
                            Cooking Place
                        </a>
                    </li>
                @endif

                @if (checkFeaturePermission('department'))
                    <li>
                        <a href="{{ route('departments') }}" class="flex items-center @yield('departments')">
                            <i class="fal fa-network-wired  pr-3"></i>
                            Department
                        </a>
                    </li>
                @endif
                @if (checkFeaturePermission('role'))
                    <li>
                        <a href="{{ route('roles') }}" class="flex items-center @yield('roles')">
                            <i class="fal fa-tasks  pr-3"></i>
                            Roles
                        </a>
                    </li>
                @endif
                @if (checkFeaturePermission('task'))
                    <li>
                        <a href="{{ route('tasks') }}" class="flex items-center @yield('tasks')">
                            <i class="fal fa-tasks  pr-3"></i>
                            Tasks
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('custom_tasks') }}" class="flex items-center @yield('custom_tasks')">
                            <i class="fal fa-tasks  pr-3"></i>
                            Custom Tasks
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('task_report') }}" class="flex items-center @yield('tasks_reports')">
                            <i class="fal fa-tasks  pr-3"></i>
                            Report Tasks
                        </a>
                    </li>

                @endif
                @if (checkFeaturePermission('area'))
                    <li>
                        <a href="{{ route('areas') }}" class="flex items-center @yield('areas')">
                            <i class="fal fa-network-wired  pr-3"></i>
                            Areas
                        </a>
                    </li>
                @endif
                @if (checkFeaturePermission('item'))
                    <li>
                        <a href="{{ route('brands') }}" class="flex items-center @yield('brands')">
                            <i class="fal fa-copyright  pr-3"></i>
                            Brands
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('items') }}" class="flex items-center @yield('items')">
                            <i class="fal fa-hand-receiving  pr-3"></i>
                            Items
                        </a>
                    </li>
                @endif
                @if (checkFeaturePermission('uom'))
                    <li>
                        <a href="{{ route('uoms') }}" class="flex items-center @yield('uom_conversions')">
                            <i class="fal fa-balance-scale  pr-3"></i>
                            UOMs
                        </a>
                    </li>
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
                            class="flex items-center @yield('item_usage_forecasts_month')">
                            <i class="fal fa-truck-loading  pr-3"></i>
                            Item Usage Forecasts By Month
                        </a>
                    </li>
                @endif

                @if (checkFeaturePermission('menu'))
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
                            class="flex items-center @yield('menu_sale_report')">
                            <i class="fal fa-clipboard-list  pr-3"></i>
                            Menu Sale Report
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('menu_costing.index') }}" class="flex items-center @yield('menu_costing')">
                            <i class="fal fa-clipboard-list  pr-3"></i>
                            Menu Costing
                        </a>
                    </li>
                @endif
                @if (checkFeaturePermission('room'))
                    <li>
                        <a href="{{ route('room') }}" class="flex items-center @yield('room')">
                            <i class="fal fa-microphone-alt  pr-3"></i>
                            Room
                        </a>
                    </li>
                @endif

                @if (checkFeaturePermission('table'))
                    <li>
                        <a href="{{ route('table') }}" class="flex items-center @yield('table')">
                            <i class="fal fa-utensils  pr-3"></i>
                            Table
                        </a>
                    </li>
                @endif
                @if (checkFeaturePermission('service'))
                    <li>
                        <a href="{{ route('services') }}" class="flex items-center @yield('services')">
                            <i class="fal fa-users-cog  pr-3"></i>
                            Services
                        </a>
                    </li>
                @endif
                @if (checkFeaturePermission('complaint'))
                    <li>
                        <a href="{{ route('complains') }}" class="flex items-center @yield('complains')">
                            <i class="fal fa-envelope-open-text  pr-3"></i>
                            Complaints
                        </a>
                    </li>
                @endif
                @if (checkFeaturePermission('inventory'))
                    <li>
                        <a href="{{ route('inventories') }}" class="flex items-center @yield('inventories')">
                            <i class="fal fa-inventory  pr-3"></i>
                            Inventories
                        </a>
                    </li>
                @endif
                @if (checkFeaturePermission('supplier'))
                    <li>
                        <a href="{{ route('suppliers.index') }}" class="flex items-center @yield('supplier')">
                            <i class="fal fa-tasks  pr-3"></i>
                            Suppliers
                        </a>
                    </li>
                @endif
                @if (checkFeaturePermission('cashbook'))
                    <li>
                        <a href="{{ route('accountings') }}" class="flex items-center @yield('accounting')">
                            <i class="fal fa-tasks  pr-3"></i>
                            Chart of Accounts (COA)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('financial_transactions') }}"
                            class="flex items-center @yield('financial_transactions')">
                            <i class="fal fa-tasks  pr-3"></i>
                            Financial Transactions
                        </a>
                    </li>
                    <li>
                        <button class="flex items-center pl-9 my-2 text-sm" type="button" data-te-collapse-init
                            data-te-ripple-init data-te-ripple-color="light" data-te-target="#collapseCashbooks"
                            aria-expanded="false" aria-controls="collapseExample">
                            <i class="fal fa-sack-dollar  pr-4"></i>
                            Cash Book
                        </button>

                        <div class="!visible hidden text-center" id="collapseCashbooks" data-te-collapse-item>
                            <ul>
                                <li>
                                    <a href="{{ route('office_cash') }}" class="flex items-center @yield('office_cash')">
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
                                    <a href="{{ route('service_cash') }}" class="flex items-center @yield('service_cash')">
                                        <i class="fal fa-tasks  pr-3"></i>
                                        Service Cash Book
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('advance_cash') }}" class="flex items-center @yield('advance_cash')">
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
                            </ul>
                        </div>
                    </li>
                    <li>
                        <button class="flex items-center pl-9 my-2 text-sm" type="button" data-te-collapse-init
                            data-te-ripple-init data-te-ripple-color="light" data-te-target="#collapseBankbooks"
                            aria-expanded="false" aria-controls="collapseExample">
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
                @if (checkFeaturePermission('purchase-order'))
                    <li>
                        <a href="{{ route('purchase_orders') }}" class="flex items-center @yield('purchase_orders')">
                            <i class="fal fa-truck-loading  pr-3"></i>
                            Purchase Orders
                        </a>
                    </li>
                @endif
                    @if (checkFeaturePermission('purchase-order-confirmation'))
                        <li>
                            <a href="{{ route('purchase_orders.confirm_poitems') }}"
                                class="flex items-center @yield('confirm_purchase_order_items')">
                                <i class="fal fa-truck-loading  pr-3"></i>
                                Confirm Purchase Order Items
                            </a>
                        </li>
                    @endif
                @if (checkFeaturePermission('purchase-order-item-left'))
                    <li>
                        <a href="{{ route('purchase_orders.left_items_index') }}"
                            class="flex items-center @yield('purchase_order_left_items')">
                            <i class="fal fa-truck-loading  pr-3"></i>
                            Purchase Orders with Left Items
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('procurement_order_items') }}" class="flex items-center @yield('procurement_order_items')">
                        <i class="fal fa-truck-loading  pr-3"></i>
                        Procurement Order Items
                    </a>
                </li>
                <li>
                    <a href="{{ route('arrival_items') }}" class="flex items-center @yield('arrival_items')">
                        <i class="fal fa-truck-loading  pr-3"></i>
                        Arrival Items
                    </a>
                </li>
                <li>
                    <a href="{{ route('purchase_order_invoices') }}" class="flex items-center @yield('purchase_order_invoices')">
                        <i class="fal fa-truck-loading  pr-3"></i>
                        Purchase Order Invoices
                    </a>
                </li>
                @if (checkFeaturePermission('fixed-asset'))
                    <li>
                        <a href="{{ route('assetItemList') }}" class="flex items-center @yield('asset_items')">
                            <i class="fal fa-truck-loading  pr-3"></i>
                            Asset Items
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('assetList') }}" class="flex items-center @yield('assets')">
                            <i class="fal fa-truck-loading  pr-3"></i>
                            Assets
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('fixed_assets.index') }}" class="flex items-center @yield('fixed_asset')">
                            <i class="fal fa-truck-loading  pr-3"></i>
                            Fixed Assets
                        </a>
                    </li>
                @endif
                @if (checkFeaturePermission('account-payables'))
                    <li>
                        <a href="{{ route('AP.index') }}" class="flex items-center @yield('account_payables')">
                            <i class="fal fa-truck-loading  pr-3"></i>
                            AP
                        </a>
                    </li>
                    {{-- <li>
                        <a href="{{ route('AP.history') }}" class="flex items-center @yield('ap_history')">
                            <i class="fal fa-truck-loading  pr-3"></i>
                            AP Transactions
                        </a>
                    </li> --}}
                @endif
                @if (checkFeaturePermission('inventory-stocks'))
                    <li>
                        <a href="{{ route('inventory_stocks.index') }}"
                            class="flex items-center @yield('inventory_stocks')">
                            <i class="fal fa-truck-loading  pr-3"></i>
                            Inventory Stocks
                        </a>
                    </li>
                @endif
                @if (checkFeaturePermission('inventory-transfer-list'))
                    <li>
                        <a href="{{ route('transfers.index') }}" class="flex items-center @yield('inventory_histories')">
                            <i class="fal fa-user  pr-3"></i>
                            Inventory Transfer Histories
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transfers.receives') }}" class="flex items-center @yield('inventory_receives')">
                            <i class="fal fa-user  pr-3"></i>
                            Inventory Receives List
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transfers.transfers') }}"
                            class="flex items-center @yield('inventory_transfers')">
                            <i class="fal fa-user  pr-3"></i>
                            Inventory Transfers List
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('used_defected_items.index') }}"
                            class="flex items-center @yield('used_defected_items')">
                            <i class="fal fa-user  pr-3"></i>
                            Used Defected Items
                        </a>
                    </li>
                @endif

                @if (checkFeaturePermission('room-discount'))
                    <li>
                        <a href="{{ route('room_discount.index') }}" class="flex items-center @yield('room_discount')">
                            <i class="fal fa-truck-loading  pr-3"></i>
                            Room Discount
                        </a>
                    </li>
                @endif

                @if (checkFeaturePermission('menu-service-discount'))
                    <li>
                        <a href="{{ route('menu_service_discount.index') }}"
                            class="flex items-center @yield('menu&service_discount')">
                            <i class="fal fa-truck-loading  pr-3"></i>
                            Menu Service Discount
                        </a>
                    </li>
                @endif

                @if (checkFeaturePermission('package'))
                    <li>
                        <a href="{{ route('packages.index') }}" class="flex items-center @yield('packages')">
                            <i class="fal fa-truck-loading  pr-3"></i>
                            Packages
                        </a>
                    </li>
                @endif
                @if(checkFeaturePermission('crm'))
                    <li>
                        <button class="flex items-center pl-9 my-2 text-sm" type="button" data-te-collapse-init
                            data-te-ripple-init data-te-ripple-color="light" data-te-target="#collapseCRM"
                            aria-expanded="false" aria-controls="collapseExample">
                            <i class="fal fa-sack-dollar  pr-3"></i>
                            CRM
                        </button>
                        <div class="!visible hidden text-center" id="collapseCRM" data-te-collapse-item>
                            <ul>
                                <li>
                                    <a href="{{ route('crm.customers.index') }}"
                                        class="flex items-center @yield('crm_customer_list')">
                                        <i class="fal fa-tasks  pr-3"></i>
                                        Customers
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('crm.customers.birthdays') }}"
                                        class="flex items-center @yield('customer_birthdays')">
                                        <i class="fal fa-tasks  pr-3"></i>
                                        Customer Birthdays
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('crm.level_discounts.index') }}"
                                        class="flex items-center @yield('customer_level_discounts')">
                                        <i class="fal fa-tasks  pr-3"></i>
                                        Customer Level Discounts
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('crm.birthday_discounts.index') }}"
                                        class="flex items-center @yield('birthday_promotions')">
                                        <i class="fal fa-tasks  pr-3"></i>
                                        Birthday Promotions
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if(checkFeaturePermission('accessory'))
                    <li>
                        <a href="/accessories" class="flex items-center @yield('accessories')">
                            <i class="fal fa-braille pr-3"></i>
                            Accessories
                        </a>
                    </li>
                @endif

                @if(checkFeaturePermission('objective'))
                    <li>
                        <a href="/OKR" class="flex items-center @yield('OKR')">
                            <i class="fal fa-braille pr-3"></i>
                            OKR
                        </a>
                    </li>
                @endif

                <!-- @if(checkFeaturePermission('accessory')) -->
                <li>
                    <a href="/menu_forecasting" class="flex items-center @yield('menu_forecasting')">
                        <i class="fal fa-braille pr-3"></i>
                        Menu Forecasting
                    </a>
                </li>
                <li>
                    <a href="/ktv_forecasting" class="flex items-center @yield('ktv_forecasting')">
                        <i class="fal fa-braille pr-3"></i>
                        KTV Forecasting
                    </a>
                </li>
                <!-- @endif -->

                @if(checkFeaturePermission('ktv-product-tree'))
                    <li>
                        <a href="/ktv_product_tree" class="flex items-center @yield('ktv_product_tree')">
                            <i class="fal fa-braille pr-3"></i>
                            Product Tree
                        </a>
                    </li>
                @endif
                <li>
                    <a href="/ktv_forecasting" class="flex items-center @yield('ktv_forecasting')">
                        <i class="fal fa-braille pr-3"></i>
                        KTV Forecasting
                    </a>
                </li>
                <li>
                    <a href="/okr_duty" class="flex items-center @yield('OKR_duty')">
                        <i class="fal fa-braille pr-3"></i>
                        OKR Duty
                    </a>
                </li>
                <li>
                    <a href="/time_shift" class="flex items-center @yield('time_shift')">
                        <i class="fal fa-braille pr-3"></i>
                        Time Shift
                    </a>
                </li>
                <li>
                    <a href="/gps" class="flex items-center @yield('gps')">
                        <i class="fal fa-braille pr-3"></i>
                        GPS
                    </a>
                </li>
                <li>
                    <a href="/contact" class="flex items-center @yield('contact')">
                        <i class="fal fa-braille pr-3"></i>
                        Contact
                    </a>
                </li>

            </ul>

        </div>

    </div>
    <div id="app"
        class="absolute bottom-0 left-0 w-[15rem] h-14 bg-[#111c43] border-t border-[#0002] flex items-center justify-start">
        <logout-component />
    </div>
</nav>
