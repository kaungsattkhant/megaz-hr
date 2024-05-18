        <nav id="sidebar" class="side-bar w-fit pt-0 h-[100vh]">
            <div class="relative pb-12 overflow-y-hidden small-scrollbar h-[100vh]"
                onmouseover="this.style.overflowY='scroll'" onmouseout="this.style.overflowY='hidden'">

                <div class="relative w-[15rem] pt-12">
                    <ul class=" mb-4">
                        @if (checkFeaturePermission('staff'))
                            <li>
                                <a href="{{ route('staff') }}" class="flex items-center @yield('staffs')">
                                    <i class="fal fa-user  pr-3"></i>
                                    Staff
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
                                <a href="{{ route('items') }}" class="flex items-center @yield('items')">
                                    <i class="fal fa-hand-receiving  pr-3"></i>
                                    Items
                                </a>
                            </li>
                        @endif
                        @if (checkFeaturePermission('uom'))
                            <li>
                                <a href="{{ route('uoms') }}" class="flex items-center @yield('uoms')">
                                    <i class="fal fa-hand-receiving  pr-3"></i>
                                    UOMs
                                </a>
                            </li>
                        @endif
                        @if (checkFeaturePermission('item-usage-forecast'))
                            <li>
                                <a href="{{ route('item_usage_forecasts') }}"
                                    class="flex items-center @yield('item_usage_forecasts')">
                                    <i class="fal fa-truck-loading  pr-3"></i>
                                    Item Usage Forecasts
                                </a>
                            </li>
                        @endif

                        @if (checkFeaturePermission('menu'))
                            <li>
                                <a href="{{ route('menus') }}" class="flex items-center @yield('menus')">
                                    <i class="fal fa-clipboard-list  pr-3"></i>
                                    Selling Menus
                                </a>
                            </li>
                        @endif
                        @if (checkFeaturePermission('room'))
                            <li>
                                <a href="{{ route('room') }}" class="flex items-center @yield('room')">
                                    <i class="fal fa-user  pr-3"></i>
                                    Room
                                </a>
                            </li>
                        @endif

                        @if (checkFeaturePermission('table'))
                            <li>
                                <a href="{{ route('table') }}" class="flex items-center @yield('table')">
                                    <i class="fal fa-user  pr-3"></i>
                                    Table
                                </a>
                            </li>
                        @endif
                        @if (checkFeaturePermission('service'))
                            <li>
                                <a href="{{ route('services') }}" class="flex items-center @yield('services')">
                                    <i class="fal fa-user  pr-3"></i>
                                    Services
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
                        @endif
                        @if (checkFeaturePermission('complaint'))
                            <li>
                                <a href="{{ route('complains') }}" class="flex items-center @yield('complains')">
                                    <i class="fal fa-user  pr-3"></i>
                                    Complaints
                                </a>
                            </li>
                        @endif
                        @if (checkFeaturePermission('inventory'))
                            <li>
                                <a href="{{ route('inventories') }}" class="flex items-center @yield('inventories')">
                                    <i class="fal fa-inventory  pr-3"></i>
                                    Inventory
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
                        {{-- <li>
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
                                <i class="fal fa-sack-dollar  pr-3"></i>
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
                                        <a href="{{ route('owner_cash') }}"
                                            class="flex items-center @yield('owner_cash')">
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
                                        <a href="{{ route('kpay_bank') }}"
                                            class="flex items-center @yield('kpay_bank')">
                                            <i class="fal fa-tasks  pr-3"></i>
                                            KPay
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li> --}}
                        @if (checkFeaturePermission('purchase-order'))
                            <li>
                                <a href="{{ route('purchase_orders') }}"
                                    class="flex items-center @yield('purchase_orders')">
                                    <i class="fal fa-truck-loading  pr-3"></i>
                                    Purchase Orders
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('purchase_orders.left_items_index') }}"
                                    class="flex items-center @yield('purchase_order_left_items')">
                                    <i class="fal fa-truck-loading  pr-3"></i>
                                    Purchase Orders with Left Items
                                </a>
                            </li>
                        @endif
                        @if (checkFeaturePermission('fixed-asset'))
                            <li>
                                <a href="{{ route('fixed_assets.index') }}"
                                    class="flex items-center @yield('fixed_asset')">
                                    <i class="fal fa-truck-loading  pr-3"></i>
                                    Fixed Assets
                                </a>
                            </li>
                        @endif
                        @if (checkFeaturePermission('Inventory', ['Staff']))
                            <li>
                                <a href="{{ route('purchase_orders.confirm_poitems') }}"
                                    class="flex items-center @yield('confirm_purchase_order_items')">
                                    <i class="fal fa-truck-loading  pr-3"></i>
                                    Confirm Purchase Order Items
                                </a>
                            </li>
                        @endif
                        @if (checkFeaturePermission('inventory-transfer-list'))
                            <li>
                                <a href="{{ route('transfers.index') }}"
                                    class="flex items-center @yield('inventory_histories')">
                                    <i class="fal fa-user  pr-3"></i>
                                    Inventory Transfer Histories
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('transfers.receives') }}"
                                    class="flex items-center @yield('inventory_receives')">
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
                        @endif

                    </ul>

                </div>

            </div>
            <div id="app"
                class="absolute bottom-0 left-0 w-[15rem] h-14 bg-[#111c43] border-t border-[#0002] flex items-center justify-start">
                <logout-component />
            </div>
        </nav>
