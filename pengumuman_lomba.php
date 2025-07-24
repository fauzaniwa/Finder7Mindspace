<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        work: ['Work Sans'],
                    },
                    animation: {
                        'spin-slow': 'spin 4s linear infinite',
                        'loop-scroll': 'loop-scroll 10s linear infinite',
                    },
                    keyframes: {
                        'loop-scroll': {
                            from: { transform: 'translateX(0)' },
                            to: { transform: 'translateX(-100%)' },
                        },
                    },
                },
            },
        };
    </script>
    <style type="text/tailwindcss">
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }


        .navbar-scrolled {
        box-shadow: 2px 2px 30px #000000;
      }
      .ext-scrolled {
        color: black;
      }
      .navbar {
        transition: all 0.5s;
      }
      .scroller {
        max-width: 600px;
      }

      .scroller__inner {
        padding-block: 1rem;
        display: flex;
        flex-wrap: wrap;
        gap: 3rem;
      }

      .scroller[data-animated='true'] {
        overflow: hidden;
        -webkit-mask: linear-gradient(90deg, transparent, white 20%, white 80%, transparent);
        mask: linear-gradient(90deg, transparent, white 20%, white 80%, transparent);
      }

      .scroller[data-animated='true'] .scroller__inner {
        width: max-content;
        flex-wrap: nowrap;
        animation: scroll var(--_animation-duration, 40s) var(--_animation-direction, forwards) linear infinite;
      }

      .scroller[data-direction='right'] {
        --_animation-direction: reverse;
      }

      .scroller[data-direction='left'] {
        --_animation-direction: forwards;
      }

      .scroller[data-speed='fast'] {
        --_animation-duration: 20s;
      }

      .scroller[data-speed='slow'] {
        --_animation-duration: 60s;
      }

      @keyframes scroll {
        to {
          transform: translate(calc(-50% - 0.5rem));
        }
      }

      /* for testing purposed to ensure the animation lined up correctly */
      .test {
        background: red !important;
      }
    </style>
    <title>Finder - Lomba Ilustrasi</title>
    <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />
    <!-- Script Navbar Menu -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <!-- Script Cursor -->
    <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />
    <!-- Script Cursor -->
    <link rel="stylesheet" href="style.css" />
</head>

<body class="bg-black">
    <?php
    require '_navbar.php'
        ?>
    <div
        class="w-2/3 h-3/4 blur-3xl absolute -z-10 rounded-full bg-[radial-gradient(circle,_#515151_0%,_rgba(244,114,182,0)_70%)] top-px left-1/2 -translate-x-1/2 -translate-y-1/2">
    </div>

    <!-- Header Navigasi -->
    <br><br><br><br><br>
    <div class="relative">
        <div
            class="pointer-events-none absolute right-0 top-0 h-full w-1/4 bg-gradient-to-l from-black  to-transparent z-10">
        </div>

        <div
            class="hidden md:flex pointer-events-none absolute left-0 top-0 h-full w-1/4 bg-gradient-to-r from-black  to-transparent z-10">
        </div>

        <section
            class="flex overflow-x-auto snap-x snap-mandatory gap-5 md:gap-20 px-7 md:px-24 scrollbar-hide scroll-smooth">
            <!-- CARD 1 -->
            <div class="snap-center shrink-0 w-full md:w-8/12 pt-20 h-full shadow-lg relative">
                <div class="absolute top-16 md:top-14 -left-3 md:-left-6 z-30">
                    <img src="./img/Lomba/Star 2.png" alt="" class="w-14 md:w-20 h-auto" />
                </div>
                <div class="w-full h-full">

                    <!-- Gambar/card dengan overflow-hidden -->
                    <div class="rounded-3xl overflow-hidden relative">
                        <div class="pointer-events-none absolute h-full w-full  bg-black opacity-80">
                        </div>
                        <img src="./img/Lomba/deschar1resize.jpg"
                            class="w-full h-full object-cover cursor-pointer popup-image"
                            data-img="./img/Lomba/deschar1resize.jpg">

                        <div class="pointer-events-none">
                            <div class="absolute flex flex-col text-white w-10/12 z-20 top-5 md:top-10 left-10">
                                <h1 class="font-bold text-xl lg:text-5xl mb-1 lg:mb-3 ">Juara 1</h1>
                                <h1 class="font-semibold italic text-lg lg:text-3xl mb-2 ">Desain Karakter</h1>
                            </div>
                            <div class="hidden md:flex absolute flex-col text-white z-20 bottom-10 left-10">
                                <h1 class=" font-semibold text-xl lg:text-3xl mb-2 "> Judul Karya</h1>
                                <h2 class="text-lg lg:text-xl">Nama Artist</h2>
                                <h3 class="text-lg lg:text-xl">Social Media</h3>
                                <p class="text-sm lg:text-base text-neutral-400 w-10/12">Lorem ipsum dolor sit amet
                                    consectetur
                                    adipisicing elit.
                                    Hic eum dignissimos nam odio vitae voluptatum voluptate iure vero porro dolorum!</p>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="flex md:hidden flex-col  text-white ">
                        <div class="flex flex-col ">
                            <h1 class="font-semibold italic text-lg md::text-3xl">Judul Karya</h1>
                            <h2 class="text-sm md::text-xl">Nama Artist</h2>
                            <h3 class="text-xs md::text-xl mb-2">Social Media</h3>
                            <p class="text-xs md::text-base text-neutral-400">Lorem ipsum dolor sit amet consectetur
                                adipisicing elit.
                                Hic eum dignissimos nam odio vitae voluptatum voluptate iure vero porro dolorum!</p>
                        </div>

                        <br>

                        <h1 class="p-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-transparent">Swipe</h1>
                    </div>


                </div>

            </div>



            <!-- CARD 2 -->
            <div class="snap-center shrink-0 w-full md:w-8/12 pt-20 h-full shadow-lg relative">
                <div class="absolute top-16 md:top-14 -left-3 md:-left-6 z-10">
                    <img src="./img/Lomba/Star 2.png" alt="" class="w-14 md:w-20 h-auto" />
                </div>

                <div class="flex w-full h-full ">
                    <!-- Gambar -->
                    <div class="w-full sm:w-1/2 relative rounded-3xl overflow-hidden">
                        <div class="pointer-events-none absolute h-full w-full bg-black opacity-80">
                        </div>
                        <img src="./img/Lomba/juara1resize.jpg"
                            class="w-full h-full object-cover cursor-pointer popup-image"
                            data-img="./img/Lomba/juara1resize.jpg">

                        <div class="flex sm:hidden pointer-events-none">
                            <div class="absolute flex flex-col text-white w-10/12 top-5 left-10">
                                <h1 class="font-bold text-xl md:text-5xl mb-1 md:mb-3 ">Juara 1</h1>
                                <h1 class="font-semibold italic text-lg md:text-3xl mb-2 ">Poster Ilustrasi</h1>
                            </div>
                            <div class="absolute flex flex-col text-white z-20 bottom-10 left-10">
                                <h1 class=" font-semibold text-base md:text-3xl mb-2 "> Judul Karya</h1>
                                <h2 class="text-sm md:text-xl">Nama Artist</h2>
                                <h3 class="text-xs italic md:text-xl mb-2">Social Media</h3>
                                <p class="text-xs md:text-base text-neutral-400 w-10/12">Lorem ipsum dolor sit amet
                                    consectetur
                                    adipisicing elit.
                                    Hic eum dignissimos nam odio vitae voluptatum voluptate iure vero porro dolorum!</p>
                            </div>
                        </div>
                    </div>

                    <!-- Teks -->
                    <div class="hidden w-1/2 pl-10 py-10 sm:flex flex-col justify-between text-white ">
                        <div>
                            <h1 class="font-bold text-2xl md:text-5xl mb-3 md:mb-5 ">Juara 1</h1>
                            <h1 class="font-semibold italic text-xl md:text-3xl mb-6 md:mb-10 ">Poster Ilustrasi</h1>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <h1 class="font-semibold italic text-xl md:text-3xl">Judul Karya</h1>
                            <h2 class="text-lg md:text-xl">Nama Artist</h2>
                            <h3 class="text-lg md:text-xl">Social Media</h3>
                            <p class="text-sm md:text-base text-neutral-400">Lorem ipsum dolor sit amet consectetur
                                adipisicing elit.
                                Hic eum dignissimos nam odio vitae voluptatum voluptate iure vero porro dolorum!</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <br><br><br><br><br><br><br>


    <!-- Header Navigasi -->
    <div
        class="flex flex-col md:flex-row mx-auto text-center justify-center gap-5 text-base md:text-lg font-semibold text-neutral-600 pb-10">
        <a href="#desain" class="nav-tab text-emerald-400 underline">Design Character</a>
        <a href="#poster" class="nav-tab">Poster Illustration</a>
    </div>
    <div class="relative">
        <div
            class="pointer-events-none absolute right-0 top-0 h-full w-1/4 bg-gradient-to-l from-neutral-950 to-transparent z-10">
        </div>
        <section
            class="desain flex overflow-x-auto snap-x snap-mandatory gap-5 md:gap-20 px-7 md:px-24 scrollbar-hide scroll-smooth ">
            <div id="desain"
                class="snap-center shrink-0 w-full md:w-10/12 h-fit md:h-auto rounded-3xl p-10 md:p-24 py-12 md:py-32 bg-neutral-800 text-white ">
                <h1 class="font-bold text-center text-2xl md:text-5xl mb-6 md:mb-10">Design Character</h1>
                <br>


                <div id="juara1" class="pb-32">

                    <div
                        class=" w-3/4 h-full mb-8 mx-auto justify-center rounded-3xl overflow-hidden shadow-lg relative ">
                        <img src="./img/Lomba/deschar1resize.jpg" alt=""
                            class="w-full h-full object-cover cursor-pointer popup-image"
                            data-img="./img/Lomba/deschar1resize.jpg">
                    </div>

                    <h1
                        class="font-bold text-end text-2xl p-5 rounded-xl md:text-5xl mb-6 md:mb-10 bg-gradient-to-l from-yellow-400 to-transparent">
                        Juara 1</h1>



                    <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Nama Karya</h1>
                    <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Nama Artist</h3>
                    <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5"> Social Media
                    </p>
                    <p class="font-normal text-justify text-sm md:text-base mb-3 md:mb-5"> (Deskripsi Karya)
                        Karena
                        untuk
                        memahami manusia, kita harus kembali pada cara pikir manusia itu sendiri. Tanpa
                        disadari,
                        keputusan
                        dan pilihan individu membentuk hampir seluruh aspek kehidupan kita. Perkembangan AI
                        membuat
                        manusia
                        semakin bergantung pada ciptaannya sendiri. Namun, adaptasi bukan soal bersaing,
                        melainkan
                        memanfaatkan keunikan manusia: kesadaran, emosi, kreativitas, dan pencarian makna.
                        Pameran ini
                        menjadi ruang bagi audiens untuk memahami cara pikir mereka, sekaligus belajar
                        berkontribusi
                        demi
                        kehidupan yang lebih harmonis. kesadaran, emosi, kreativitas, dan pencarian makna.
                    </p>
                </div>


                <div id="juara2" class="flex flex-col pb-32 w-full items-end relative">
                    <div class="flex flex-col w-full ">

                        <div
                            class="w-3/4 h-full mb-8 mx-auto justify-center rounded-3xl overflow-hidden shadow-lg relative ">
                            <img src="./img/Lomba/deschar2resize.jpg" alt=""
                                class="w-full h-full object-cover cursor-pointer popup-image"
                                data-img="./img/Lomba/deschar2resize.jpg">
                        </div>

                        <h1
                            class="font-bold text-end text-2xl p-5 rounded-xl md:text-5xl mb-6 md:mb-10 bg-gradient-to-l from-blue-500 to-transparent">
                            Juara 2</h1>



                        <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Nama Karya</h1>
                        <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Nama Artist
                        </h3>
                        <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5"> Social
                            Media </p>
                        <p class="font-normal text-justify text-sm md:text-base mb-3 md:mb-5"> (Deskripsi
                            Karya) Karena
                            untuk
                            memahami manusia, kita harus kembali pada cara pikir manusia itu sendiri. Tanpa
                            disadari,
                            keputusan
                            dan pilihan individu membentuk hampir seluruh aspek kehidupan kita. Perkembangan
                            AI membuat
                            manusia
                            semakin bergantung pada ciptaannya sendiri. Namun, adaptasi bukan soal bersaing,
                            melainkan
                            memanfaatkan keunikan manusia: kesadaran, emosi, kreativitas, dan pencarian
                            makna. Pameran
                            ini
                            menjadi ruang bagi audiens untuk memahami cara pikir mereka, sekaligus belajar
                            berkontribusi
                            demi
                            kehidupan yang lebih harmonis. kesadaran, emosi, kreativitas, dan pencarian
                            makna.</p>
                    </div>
                </div>


                <div id="juara3" class="flex flex-col pb-32 w-full items-start">
                    <div class="flex flex-col w-full h-auto gap-14 items-center ">

                        <div
                            class="w-3/4 h-full mb-8 mx-auto justify-center rounded-3xl overflow-hidden shadow-lg relative ">
                            <img src="./img/Lomba/deschar3resize.jpg" alt=""
                                class="w-full h-full object-cover cursor-pointer popup-image"
                                data-img="./img/Lomba/deschar3resize.jpg">
                        </div>

                        <div class="flex flex-col w-full justify-between">
                            <div>
                                <h1
                                    class="font-bold text-start text-2xl p-5 rounded-xl md:text-5xl mb-6 md:mb-10 bg-gradient-to-r from-emerald-500 to-transparent">
                                    Juara 3</h1>
                            </div>

                            <div class="flex flex-col ">
                                <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Nama
                                    Karya</h1>
                                <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Nama
                                    Artist</h3>
                                <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5">
                                    Social Media
                                </p>
                                <p class="font-normal text-justify text-sm md:text-base"> (Deskripsi Karya)
                                    Karena
                                    untuk
                                    memahami manusia, kita harus kembali pada cara pikir manusia itu
                                    sendiri. Tanpa
                                    disadari,
                                    keputusan
                                    dan pilihan individu membentuk hampir seluruh aspek kehidupan kita.
                                    Perkembangan AI
                                    membuat
                                    manusia
                                    semakin bergantung pada ciptaannya sendiri. Namun, adaptasi bukan soal
                                    bersaing,
                                    melainkan
                                    memanfaatkan keunikan manusia: kesadaran, emosi, kreativitas, dan
                                    pencarian makna.
                                    Pameran ini
                                    menjadi ruang bagi audiens untuk memahami cara pikir mereka, sekaligus
                                    belajar
                                    berkontribusi
                                    demi
                                    kehidupan yang lebih harmonis. kesadaran, emosi, kreativitas, dan
                                    pencarian makna.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="juarafavorit" class="flex flex-col pb-32 w-full items-end">
                    <div class="flex flex-col w-full">

                        <div
                            class=" w-3/4 h-full mb-8 mx-auto justify-center rounded-3xl overflow-hidden shadow-lg relative ">
                            <img src="./img/Lomba/favoritdesain.jpg" alt=""
                                class="w-full h-full object-cover cursor-pointer popup-image"
                                data-img="./img/Lomba/favoritdesain.jpg">
                        </div>

                        <h1
                            class="font-bold text-end text-2xl p-5 rounded-xl md:text-5xl mb-6 md:mb-10 bg-gradient-to-l from-pink-500 to-transparent">
                            Juara Favorit</h1>

                        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ut voluptates voluptatibus
                            recusandae accusantium dolore excepturi reiciendis sed eius illo ipsum.</p>
                        <br><br>
                        <div class="mx-auto">
                            <a href="">
                                <button
                                    class="submit-btn  bg-emerald-600 hover:bg-emerald-800 transition-all duration-300 ease-in-out px-10 py-3 md:px-20 md:py-5 rounded-2xl md:rounded-3xl mt-6 text-base md:text-xl text-white  ">Vote
                                    Sekarang</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>




            <!-- CARD POSTER -->

            <div id="poster"
                class="snap-center shrink-0 w-full md:w-10/12 h-fit md:h-auto rounded-3xl p-10 md:p-24 py-12 md:py-32 bg-neutral-800 text-white ">
                <h1 class="font-bold text-center text-2xl md:text-5xl mb-6 md:mb-10">Poster Ilustrasi</h1>
                <br>


                <div id="juara1" class="flex flex-col md:flex-row gap-10 pb-32">

                    <div class=" w-full md:w-1/2 h-full mb-8 rounded-3xl overflow-hidden shadow-lg relative ">
                        <img src="./img/Lomba/juara1resize.jpg" alt=""
                            class="w-full h-full object-cover cursor-pointer popup-image"
                            data-img="./img/Lomba/juara1resize.jpg">
                    </div>

                    <div class="w-full md:w-1/2">

                        <h1
                            class="font-bold text-end text-2xl p-5 rounded-xl md:text-5xl mb-6 md:mb-10 bg-gradient-to-l from-yellow-400 to-transparent">
                            Juara 1</h1>



                        <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Nama Karya</h1>
                        <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Nama Artist</h3>
                        <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5"> Social Media
                        </p>
                        <p class="font-normal text-justify text-sm md:text-base mb-3 md:mb-5"> (Deskripsi Karya)
                            Karena
                            untuk
                            memahami manusia, kita harus kembali pada cara pikir manusia itu sendiri. Tanpa
                            disadari,
                            keputusan
                            dan pilihan individu membentuk hampir seluruh aspek kehidupan kita. Perkembangan AI
                            membuat
                            manusia
                            semakin bergantung pada ciptaannya sendiri. Namun, adaptasi bukan soal bersaing,
                            melainkan
                            memanfaatkan keunikan manusia: kesadaran, emosi, kreativitas, dan pencarian makna.
                            Pameran ini
                            menjadi ruang bagi audiens untuk memahami cara pikir mereka, sekaligus belajar
                            berkontribusi
                            demi
                            kehidupan yang lebih harmonis. kesadaran, emosi, kreativitas, dan pencarian makna.
                        </p>

                    </div>
                </div>
                <div id="juara2" class="flex flex-col md:flex-row gap-10 pb-32">


                    <div class="w-full md:w-1/2">

                        <div class="flex md:hidden w-full md:w-1/2 h-full mb-8 rounded-3xl overflow-hidden shadow-lg relative ">
                            <img src="./img/Lomba/juara2resize.jpg" alt=""
                                class="w-full h-full object-cover cursor-pointer popup-image"
                                data-img="./img/Lomba/juara2resize.jpg">
                        </div>

                        <h1
                            class="font-bold text-end text-2xl p-5 rounded-xl md:text-5xl mb-6 md:mb-10 bg-gradient-to-l from-blue-500 to-transparent">
                            Juara 2</h1>



                        <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Nama Karya</h1>
                        <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Nama Artist</h3>
                        <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5"> Social Media
                        </p>
                        <p class="font-normal text-justify text-sm md:text-base mb-3 md:mb-5"> (Deskripsi Karya)
                            Karena
                            untuk
                            memahami manusia, kita harus kembali pada cara pikir manusia itu sendiri. Tanpa
                            disadari,
                            keputusan
                            dan pilihan individu membentuk hampir seluruh aspek kehidupan kita. Perkembangan AI
                            membuat
                            manusia
                            semakin bergantung pada ciptaannya sendiri. Namun, adaptasi bukan soal bersaing,
                            melainkan
                            memanfaatkan keunikan manusia: kesadaran, emosi, kreativitas, dan pencarian makna.
                            Pameran ini
                            menjadi ruang bagi audiens untuk memahami cara pikir mereka, sekaligus belajar
                            berkontribusi
                            demi
                            kehidupan yang lebih harmonis. kesadaran, emosi, kreativitas, dan pencarian makna.
                        </p>

                    </div>

                    <div
                        class="hidden md:flex w-full md:w-1/2 h-full mb-8 rounded-3xl overflow-hidden shadow-lg relative ">
                        <img src="./img/Lomba/juara2resize.jpg" alt=""
                            class="w-full h-full object-cover cursor-pointer popup-image"
                            data-img="./img/Lomba/juara2resize.jpg">
                    </div>
                </div>
                <div id="juara3" class="flex flex-col md:flex-row gap-10 pb-32">

                    <div class="w-full  md:w-1/2 h-full mb-8 rounded-3xl overflow-hidden shadow-lg relative ">
                        <img src="./img/Lomba/juara3resize.jpg" alt=""
                            class="w-full h-full object-cover cursor-pointer popup-image"
                            data-img="./img/Lomba/juara3resize.jpg">
                    </div>

                    <div class="w-full md:w-1/2">

                        <h1
                            class="font-bold text-end text-2xl p-5 rounded-xl md:text-5xl mb-6 md:mb-10 bg-gradient-to-l from-emerald-500 to-transparent">
                            Juara 3</h1>



                        <h1 class="font-bold text-start text-2xl md:text-5xl mb-6 md:mb-10">Nama Karya</h1>
                        <h3 class="font-semibold text-start text-lg md:text-xl mb-3 md:mb-5">Nama Artist</h3>
                        <p class="font-medium italic text-start text-lg md:text-xl mb-3 md:mb-5"> Social Media
                        </p>
                        <p class="font-normal text-justify text-sm md:text-base mb-3 md:mb-5"> (Deskripsi Karya)
                            Karena
                            untuk
                            memahami manusia, kita harus kembali pada cara pikir manusia itu sendiri. Tanpa
                            disadari,
                            keputusan
                            dan pilihan individu membentuk hampir seluruh aspek kehidupan kita. Perkembangan AI
                            membuat
                            manusia
                            semakin bergantung pada ciptaannya sendiri. Namun, adaptasi bukan soal bersaing,
                            melainkan
                            memanfaatkan keunikan manusia: kesadaran, emosi, kreativitas, dan pencarian makna.
                            Pameran ini
                            menjadi ruang bagi audiens untuk memahami cara pikir mereka, sekaligus belajar
                            berkontribusi
                            demi
                            kehidupan yang lebih harmonis. kesadaran, emosi, kreativitas, dan pencarian makna.
                        </p>

                    </div>
                </div>

                <div id="juarafavorit" class="flex flex-col md:flex-row  gap-10 pb-32 w-full">
                    <div
                        class="w-full md:w-1/2 h-full mb-8 rounded-3xl overflow-hidden shadow-lg relative bg-neutral-900">
                        <div class="absolute">
                            <h1 class="font-bold text-5xl text-white"> ?</h1>
                        </div>
                        <div class="pointer-events-none absolute h-full w-full bg-neutral-900 ">
                        </div>
                        <img src="./img/Lomba/juara4resize.jpg" alt=""
                            class="w-full h-full object-cover cursor-pointer popup-image bg-neutral-900"
                            data-img="./img/Lomba/favoritposter.jpg">
                    </div>

                    <div class="flex flex-col w-full md:w-1/2 md:justify-between justify-center mx-auto ">
                        <div>

                            <h1
                                class="font-bold text-end text-2xl p-5 rounded-xl md:text-5xl mb-6 md:mb-10 bg-gradient-to-l from-pink-500 to-transparent">
                                Juara Favorit</h1>
                        </div>
                        <div class="flex flex-col justify-center items-center">

                            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ut voluptates voluptatibus
                                recusandae accusantium dolore excepturi reiciendis sed eius illo ipsum.</p>
                            <br>
                            <div class="mx-auto justify-center pb-16">
                                <a href="">
                                    <button
                                        class="submit-btn  bg-emerald-600 hover:bg-emerald-800 transition-all duration-300 ease-in-out px-10 py-3 md:px-20 md:py-5 rounded-2xl md:rounded-3xl mt-6 text-base md:text-xl text-white  ">Vote
                                        Sekarang</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br><br><br>
    </div>
    </section>
    </div>
    <br><br><br><br><br><br><br>


    <!-- Script Toggle -->
    <script>
        const navLinks = document.querySelector('.nav-links');
        function onToggleMenu(e) {
            e.name = e.name === 'menu' ? 'close' : 'menu';
            navLinks.classList.toggle('-bottom-52');
        }
    </script>

    <!-- Script Toggle -->
    <!-- Script Navbar -->
    <script>
        const navEL = document.querySelector('.navbar');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 56) {
                navEL.classList.add('navbar-scrolled');
            } else if (window.scrollY < 56) {
                navEL.classList.remove('navbar-scrolled');
            }
        });
    </script>
    <script>
        const scrollers = document.querySelectorAll('.scroller');

        if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            addAnimation();
        }

        function addAnimation() {
            scrollers.forEach((scroller) => {
                scroller.setAttribute('data-animated', true);
                const scrollerInner = scroller.querySelector('.scroller__inner');
                const scrollerContent = Array.from(scrollerInner.children);
                scrollerContent.forEach((item) => {
                    const duplicatedItem = item.cloneNode(true);
                    duplicatedItem.setAttribute('aria-hidden', true);
                    scrollerInner.appendChild(duplicatedItem);
                });
            });
        }
    </script>
    <script src="system.js"></script>
    <!-- Tambahkan link Font Awesome di head -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Corosuel Animasi Js -->
    <script>
        const scrollers = document.querySelectorAll('.scroller');

        if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            addAnimation();
        }

        function addAnimation() {
            scrollers.forEach((scroller) => {
                scroller.setAttribute('data-animated', true);
                const scrollerInner = scroller.querySelector('.scroller__inner');
                const scrollerContent = Array.from(scrollerInner.children);
                scrollerContent.forEach((item) => {
                    const duplicatedItem = item.cloneNode(true);
                    duplicatedItem.setAttribute('aria-hidden', true);
                    scrollerInner.appendChild(duplicatedItem);
                });
            });
        }
    </script>

    <?php
    require '_footer.php';
    ?>

    <!-- Modal Gambar -->
    <div id="imageModal" class="fixed inset-0 z-50 bg-black bg-opacity-80 hidden items-center justify-center">
        <div class="relative">
            <button id="closeModal"
                class="absolute -top-0 -right-0 bg-white text-black rounded-xl px-4 p-2 hover:bg-gray-200 z-10">✕</button>
            <img id="modalImage" src="" class="max-w-[90vw] max-h-[90vh] rounded-xl shadow-2xl" />
        </div>
    </div>

    <script>
        // Ambil elemen-elemen yang dibutuhkan
        const modal = document.getElementById("imageModal");
        const modalImage = document.getElementById("modalImage");
        const closeModal = document.getElementById("closeModal");

        // Event saat klik gambar
        document.querySelectorAll(".popup-image").forEach(img => {
            img.addEventListener("click", () => {
                const src = img.getAttribute("data-img");
                modalImage.src = src;
                modal.classList.remove("hidden");
                modal.classList.add("flex");
            });
        });

        // Event tombol close
        closeModal.addEventListener("click", () => {
            modal.classList.add("hidden");
        });

        // Klik di luar gambar untuk menutup
        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.classList.add("hidden");
            }
        });
    </script>

    <script>
        const swiper = new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,

            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            breakpoints: {
                640: {
                    slidesPerView: 1.2,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    </script>

</body>
<script src="https://unpkg.com/kursor"></script>
<script>
    new kursor({
        type: 4,
        removeDefaultCursor: true,
        color: '#ffffff',
    });
</script>

<script>
    // Hapus localStorage lama (kalau pernah pakai sebelumnya)
    localStorage.removeItem('countdownEnd');

    // Target: 25 Juni 2025 pukul 23:59 WIB → 16:59 UTC
    const targetDate = new Date(Date.UTC(2025, 5, 25, 16, 59, 0));
    const endTime = targetDate.getTime();

    const digits = document.querySelectorAll('.digit');

    function updateCountdown() {
        const now = Date.now();
        let remaining = Math.floor((endTime - now) / 1000);

        if (remaining < 0) remaining = 0;

        const days = Math.floor(remaining / (24 * 3600));
        const hours = Math.floor((remaining % (24 * 3600)) / 3600);
        const minutes = Math.floor((remaining % 3600) / 60);

        const timeStr =
            String(days).padStart(2, '0') +
            String(hours).padStart(2, '0') +
            String(minutes).padStart(2, '0');

        digits.forEach((digitEl, index) => {
            digitEl.textContent = timeStr[index];
        });

        if (remaining <= 0) {
            clearInterval(timer);
            console.log("Waktu habis!");
        }
    }

    const timer = setInterval(updateCountdown, 1000);
    updateCountdown();




    // Fungsi reusable untuk setup observer dan tab scroll
    const section = document.querySelector('.desain');
    const navTabs = document.querySelectorAll('.nav-tab');

    function highlightTabById(id) {
        navTabs.forEach(tab => {
            const tabId = tab.getAttribute('href').substring(1);
            if (tabId === id) {
                tab.classList.add('text-emerald-400', 'underline');
                tab.classList.remove('text-neutral-600');
            } else {
                tab.classList.remove('text-emerald-400', 'underline');
                tab.classList.add('text-neutral-600');
            }
        });
    }

    function updateActiveTab() {
        let closest = null;
        let minDiff = Infinity;
        const center = section.scrollLeft + section.offsetWidth / 2;

        section.querySelectorAll('div[id]').forEach(el => {
            const elCenter = el.offsetLeft + el.offsetWidth / 2;
            const diff = Math.abs(center - elCenter);
            if (diff < minDiff) {
                minDiff = diff;
                closest = el;
            }
        });

        if (closest) highlightTabById(closest.id);
    }

    // Scroll listener
    section.addEventListener('scroll', updateActiveTab);

    // Click tab → scroll + highlight
    navTabs.forEach(tab => {
        tab.addEventListener('click', e => {
            e.preventDefault();
            const targetId = tab.getAttribute('href').substring(1);
            const target = document.getElementById(targetId);
            if (target) {
                section.scrollTo({
                    left: target.offsetLeft - section.offsetLeft,
                    behavior: 'smooth'
                });
                highlightTabById(targetId);
            }
        });
    });

    // Trigger saat halaman load
    window.addEventListener('load', updateActiveTab);
</script>

</html>