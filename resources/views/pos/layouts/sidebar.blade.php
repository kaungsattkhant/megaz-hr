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
                        
            </ul>

        </div>

    </div>
    <div class="absolute bottom-0 left-0 w-[11vw] h-24 flex items-end justify-end"
        style="background: url(data:image/jpeg;base64,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);
        background-repeat: no-repeat;
  background-origin: content-box;
  background-position: center;
  background-size: cover;">
        <button class="w-full flex justify-between" style="
  text-shadow:2px 2px #000;">
            <span>
            &#129319;
            </span>
            <span>
            &#129320;
            </span>
        </button>
    </div>
</nav>
