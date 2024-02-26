        <nav id="sidebar" class="side-bar w-fit pt-0 h-[100vh]">
            <div class="relative pb-12 overflow-y-hidden small-scrollbar h-[100vh]"
            onmouseover="this.style.overflowY='scroll'"
            onmouseout="this.style.overflowY='hidden'">

                <div class="relative w-[16vw] pt-12">
                    <ul class=" mb-4">
                        <li>
                            <a href="{{ route("staff") }}" class="flex items-center @yield('staffs')">
                                <i class="fal fa-user  pr-3"></i>
                                Staff
                            </a>
                        </li>
                        <li>
                            <a href="{{ route("tasks") }}" class="flex items-center @yield('tasks')">
                                <i class="fal fa-tasks  pr-3"></i>
                                Tasks
                            </a>
                        </li>
                        <li>
                            <a href="{{ route("departments") }}" class="flex items-center @yield('departments')">
                                <i class="fal fa-network-wired  pr-3"></i>
                                Department
                            </a>
                        </li>
                        <li>
                            <a href="{{ route("areas") }}" class="flex items-center @yield('areas')">
                                <i class="fal fa-network-wired  pr-3"></i>
                                Areas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route("roles") }}" class="flex items-center @yield('roles')">
                                <i class="fal fa-tasks  pr-3"></i>
                                Role
                            </a>
                        </li>
                        <li>
                            <a href="{{ route("inventories") }}" class="flex items-center @yield('inventories')">
                                <i class="fal fa-inventory  pr-3"></i>
                                Inventory
                            </a>
                        </li>
                        <li>
                            <a href="{{ route("menus") }}" class="flex items-center @yield('menu')">
                                <i class="fal fa-clipboard-list  pr-3"></i>
                                Menu
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center @yield('items')">
                                <i class="fal fa-hand-receiving  pr-3"></i>
                                Items
                            </a>
                        </li>
                        <li>
                            <a href="{{ route("roomandtable") }}" class="flex items-center @yield('table')">
                                <i class="fal fa-user  pr-3"></i>
                                Room / Table
                            </a>
                        </li>
                        <li>
                            <a href="{{ route("services") }}" class="flex items-center @yield('services')">
                                <i class="fal fa-user  pr-3"></i>
                                Services
                            </a>
                        </li>
                    </ul>

                </div>

            </div>
            <div class="absolute bottom-0 left-0 w-[16vw] h-14 bg-[#df3b06] border-t border-[#0002] flex items-center justify-start">
                <button class="w-full text-left pl-12">
                <i class="fal fa-sign-out pr-3"></i>Logout
                </button>
            </div>
        </nav>
