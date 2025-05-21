<nav>
    <div class="w-full h-24 fixed bg-transparent top-0 flex z-50 justify-center">
        <div
            class="flex w-full h-full bg-[#000000] bg-opacity-0 navbar mx-auto my-auto py-2 pl-4 md:px-8 gap-3 justify-between backdrop-blur-md">
            <div class="flex items-center gap-4 w-[220px]">
                <a href="homepage.php" class="md:h-2/3 my-auto"><img src="img/(1) Finder 6 Logo Vertical Full White.png" alt=""
                        class="w-[33%] h-[33%] md:w-full md:h-full" /></a>
            </div>

            <!-- Nav Asli -->

            <div class="hidden md:flex gap-6 justify-center">
                <a href="homepage.php#about" style="font-family: 'Work Sans'" class="flex"><button
                        class="text-sm lg:text-xl text-white txt1">Tentang Kami</button></a>
                <a href="homepage.php#program" style="font-family: 'Work Sans'" class="flex"><button
                        class="text-sm lg:text-xl text-white txt2">Program</button></a>
                <a href="homepage.php#jadwal" style="font-family: 'Work Sans'" class="flex"><button
                        class="text-sm lg:text-xl text-white txt">Jadwal</button></a>
                <a href="homepage.php#karya" style="font-family: 'Work Sans'" class="flex"><button
                        class="text-sm lg:text-xl text-white txt">Pameran</button></a>
            </div>

            <!-- Tombol Login -->
            <div class="md:flex items-center hidden gap-4 justify-end">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Jika sudah login, tampilkan tombol akun dengan dropdown menu -->
                    <div class="relative inline-block text-left">
                        <button onclick="toggleDropdown()" class="flex items-center focus:outline-none">
                            <img src="./img/iconakun.svg" alt="" />
                        </button>
                        <div id="dropdownMenu"
                            class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-md shadow-lg py-1">
                            <a href="account.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Jika belum login, tampilkan tombol login dan daftar -->
                    <a href="login.php" style="font-family: 'Work Sans'"
                        class="border-[1px] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg">Login</a>
                    <a href="register.php" style="font-family: 'Work Sans'"
                        class="bg-[#0D0D0D] hover:bg-white hover:bg-opacity-25 py-2 px-6 text-white rounded-full md:text-lg">Daftar</a>
                <?php endif; ?>
            </div>

            <!-- Tombol Menu -->

            <button class="bg-transparent aspect-square md:hidden text-center text-white">
                <ion-icon onclick="onToggleMenu(this)" name="menu" class="txt text-3xl cursor-pointer p-0"></ion-icon>
            </button>
        </div>

        <!-- Nav Menu -->
        <div
            class="nav-links flex flex-col absolute items-start bg-[#0D0D0D] bg-opacity-0 w-full p-4 shadow-2xl bottom-[120%] md:hidden text-center backdrop-blur-md">
            <a href="homepage.php#about"><button style="font-family: 'Work Sans'"
                    class="bg-transparent py-2 px-4 w-fit font-plus font-light text-white">Tentang Kami</button></a>
            <a href="homepage.php#program"><button style="font-family: 'Work Sans'"
                    class="bg-transparent py-2 px-4 w-fit font-plus font-light text-white">Program</button></a>
            <a href="homepage.php#jadwal"><button style="font-family: 'Work Sans'"
                    class="bg-transparent py-2 px-4 w-fit font-plus font-light text-white">Jadwal</button></a>
            <a href="homepage.php#karya"><button style="font-family: 'Work Sans'"
                    class="bg-transparent py-2 px-4 w-fit font-plus font-light text-white">Pameran</button></a>

            <!-- -------- -->
            <div class="flex flex-row items-center gap-6 justify-start mt-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="account.php"><img src="./img/iconakun.svg" alt="" /></a>
                    <a href="logout.php" style="font-family: 'Work Sans'"
                        class="border-[1px] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg">Logout</a>
                <?php else: ?>
                    <a href="login.php" style="font-family: 'Work Sans'"
                        class="border-[1px] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg">Login</a>
                    <a href="register.php" style="font-family: 'Work Sans'"
                        class="bg-[#0D0D0D] hover:bg-opacity-25 py-2 px-6 text-white rounded-full md:text-lg">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
        <!-- Akhir Navmenu -->
    </div>
</nav>
<!-- Navbar -->