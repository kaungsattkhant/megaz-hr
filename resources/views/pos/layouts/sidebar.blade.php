<nav id="sidebar" class="pos-side-bar shadow-lg">
    <div class="relative pb-12 overflow-y-hidden small-scrollbar h-[100vh]"
        onmouseover="this.style.overflowY='scroll'"
        onmouseout="this.style.overflowY='hidden'">

        <div class="relative w-[11vw] pt-8 px-7">
            <ul class=" mb-4">
                <li class="mb-4">
                    <a href="/pos/home" class="flex items-center flex-col  rounded-lg px-6 py-12 @yield('home')">
                        <i class="fas fa-house mb-1.5 text-2xl"></i>
                        <span class="">
                            Home
                        </span>
                    </a>
                </li>
                <li>
                    <a href="/pos/customer" class="flex items-center flex-col  rounded-lg px-6 py-12 @yield('customers')">
                        <i class="fas fa-users mb-1.5 text-2xl"></i>
                        <span class="">
                            Customers
                        </span>
                    </a>
                </li>
                <li>
                    <a href="/pos/customer_deposit" class="flex items-center flex-col  rounded-lg px-0 py-12 @yield('customer_deposit')">
                        <i class="fas fa-users mb-1.5 text-2xl"></i>
                        <span class="">
                            Customer Deposit
                        </span>
                    </a>
                </li>
                <!-- <li>
                    <a href="/pos/cashbook" class="flex items-center flex-col  rounded-lg px-6 py-12 @yield('cashbook')">
                        <i class="fas fa-users mb-1.5 text-2xl"></i>
                        <span class="">
                            Cash Book
                        </span>
                    </a>
                </li>
                <li>
                    <a href="/pos/invoices" class="flex items-center flex-col  rounded-lg px-6 py-12 @yield('invoices')">
                        <i class="fas fa-users mb-1.5 text-2xl"></i>
                        <span class="">
                            Invoices
                        </span>
                    </a>
                </li>
                <li>
                    <a href="/pos/invoices" class="flex items-center flex-col  rounded-lg px-6 py-12 @yield('invoices')">
                        <i class="fas fa-users mb-1.5 text-2xl"></i>
                        <span class="">
                            Invoices
                        </span>
                    </a>
                </li> -->
                <li>
                    <a href="/pos/invoices" class="flex items-center flex-col  rounded-lg px-6 py-12 @yield('invoices')">
                        <i class="fas fa-users mb-1.5 text-2xl"></i>
                        <span class="">
                            Invoices
                        </span>
                    </a>
                </li>
                <li>
                    <a href="/pos/cashbook" class="flex items-center flex-col  rounded-lg px-6 py-12 @yield('cashbook')">
                        <i class="fas fa-users mb-1.5 text-2xl"></i>
                        <span class="">
                            Cashbook
                        </span>
                    </a>
                </li>
                <li>
                    <a href="/booking" class="flex items-center flex-col  rounded-lg px-6 py-12 @yield('booking')">
                        <i class="fas fa-book mb-1.5 text-2xl"></i>
                        <span class="">
                            Booking
                        </span>
                    </a>
                </li>

                <li>
                    <a href="/food_orders" class="flex items-center flex-col  rounded-lg px-6 py-12 @yield('foodOrders')">
                        <i class="fas fa-burger-soda mb-1.5 text-2xl"></i>
                        <span class="">
                            Menu Order
                        </span>
                    </a>
                </li>

                <li>
                    <a href="/pos_order_items" class="flex items-center flex-col  rounded-lg px-6 py-12 @yield('posOrder')">
                        <i class="fas fa-burger-soda mb-1.5 text-2xl"></i>
                        <span class="">
                            Pos Order
                        </span>
                    </a>
                </li>
                <li>
                    <a href="/pos/ar" class="flex items-center flex-col  rounded-lg px-6 py-12 @yield('ar')">
                        <i class="fas fa-users mb-1.5 text-2xl"></i>
                        <span class="">
                            AR
                        </span>
                    </a>
                </li>

            </ul>


        </div>

    </div>
    <div id="app" class="absolute bottom-0 left-0 w-[11vw] h-24 flex items-end justify-end">
        <logout-component-pos/>
        <button class="w-full">
            <a href="/pos/ar" class="flex items-center flex-col  rounded-lg px-6 py-12 text-black">
                <i class="fas fa-users mb-1.5 text-2xl"></i>
                Log Out
            </a>
        </button>
    </div>


</nav>
