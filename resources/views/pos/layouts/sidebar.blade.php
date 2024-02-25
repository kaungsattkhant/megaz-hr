<nav id="sidebar" class="pos-side-bar shadow-lg">
    <div class="relative pb-12 overflow-y-hidden small-scrollbar h-[100vh]"
        onmouseover="this.style.overflowY='scroll'"
        onmouseout="this.style.overflowY='hidden'">

        <div class="relative w-[11vw] pt-8 px-7">
            <ul class=" mb-4">
                <li class="mb-4">
                    <a href="#" class="flex items-center flex-col  rounded-lg px-6 py-12 @yield('home')">
                        <i class="fas fa-house mb-1.5 text-2xl"></i>
                        <span class="">
                            Home
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center flex-col  rounded-lg px-6 py-12">
                        <i class="fas fa-house mb-1.5 text-2xl"></i>
                        <span class="">
                            Menu
                        </span>
                    </a>
                </li>
                        
            </ul>

        </div>

    </div>
    <div class="absolute bottom-0 left-0 w-[11vw] h-14 bg-[#000] border-t border-[#0002] flex items-center justify-start">
        <button class="w-full text-left pl-12">
            <i class="fal fa-sign-out pr-3"></i>Logout
        </button>
    </div>
</nav>
