        <nav id="sidebar" class="side-bar w-fit pt-0 h-[100vh]">
            <div class="relative pb-12 overflow-y-hidden small-scrollbar h-[100vh]"
            onmouseover="this.style.overflowY='scroll'"
            onmouseout="this.style.overflowY='hidden'">
                
                <div class="relative w-[16vw] pt-12">
                    <ul class=" mb-4">
                        <li>
                            <a href="#" class="flex items-center @yield('staffs')">
                                <i class="fal fa-user  pr-3"></i>
                                Staffs
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center @yield('tasks')">
                                <i class="fal fa-user  pr-3"></i>
                                Tasks
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center @yield('departments')">
                                <i class="fal fa-user  pr-3"></i>
                                Department
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center @yield('inventory')">
                                <i class="fal fa-user  pr-3"></i>
                                Inventory
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center @yield('menu')">
                                <i class="fal fa-user  pr-3"></i>
                                Menu
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center @yield('items')">
                                <i class="fal fa-user  pr-3"></i>
                                Items
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center @yield('services')">
                                <i class="fal fa-user  pr-3"></i>
                                Services
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>