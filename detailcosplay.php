
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- CDN Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600&display=swap" rel="stylesheet" />
    <!--  Font -->

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
    <!-- ----------- -->

    <style type="text/tailwindcss">

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
    <!-- Title Web & Icon -->
    <title>Finder - Detail Cosplay</title>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const today = new Date();
            const targetDate = new Date('2024-07-24T00:00:00+07:00'); // 24 Juli 2024 WIB

            if (today < targetDate) {
                alert('Halaman ini hanya dapat diakses setelah 24 Juli 2024.');
                window.location.href = 'homepage.php'; // Redirect ke homepage.php
            }
        });
    </script>
    <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />
    <!-- Script Navbar Menu -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <!-- Script Cursor -->
    <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />
    <!-- Script Cursor -->
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php
    require '_navbar.php';
    ?>


    <!-- Detail Lomba -->
    <!-- Detail Lomba -->
    <section id="" class="w-full h-full pt-32 bg-[#0D0D0D] pb-32">
        <!-- <div class="w-[90%] mx-auto pt-72 h-72 bg-slate-400"></div> -->
        <section class="flex gap-5 justify-between w-[90%] lg-w-[60%] mx-auto my-4">
            <h2 class="text-white text-2xl md:text-3xl font-semibold font-work text-center">Deskripsi Lomba</h2>
            <div class="flex gap-3 px-5">
                <div></div>
            </div>
        </section>
        <p class="w-[90%] mx-auto text-white text-lg md:text-xl font-light font-work text-justify mb-4">
            <span class="font-reguler">"Tema besar FINDER 6th adalah "PUSAKA," yang berarti warisan budaya. Dengan kerja sama mitra Jepang, tema cosplay diangkat menjadi "Pusaka: Merentang Waktu dan Ruang," yang mengajak peserta mengeksplorasi karakter dari budaya tradisional dan pop culture Indonesia serta Jepang. Cosplayer dapat memadukan unsur klasik dan modern dari salah satu budaya untuk menciptakan karya yang unik.Sesuai dengan tema yang diusung yakni “Pusaka: Merentang Waktu dan Ruang”, para peserta diberi pilihan untuk memerankan karakter autentik ataupun akulturasi dari kedua budaya antara Nusantara dan Jepang.

        </p>
        <!-- Peraturan -->
        <div style="background-image: url(img/bgcosplay2.png);" class="bg-cover bg-center flex justify-center items-center h-[350px] w-[90%] lg-w-[60%] bg-neutral-900 mx-auto">
            <!-- <div style="background-image: url(./img/postig.png)" class="shrink-0 bg-cover bg-white aspect-square h-[320px] w-[320px]"></div> -->
        </div>

        <section class="flex gap-5 justify-between w-[90%] lg-w-[60%] mx-auto my-4">
            <h2 class="text-white text-2xl md:text-3xl font-semibold font-work ">Peraturan Lomba </h2>
            <div class="flex gap-3 px-5">
                <div></div>
            </div>
        </section>
        <p class="w-[90%] lg-w-[60%] mx-auto text-white text-lg md:text-xl font-light font-work text-justify">
            Berikut Peraturan Untuk Mengkuti Kompetisi Ilustrasi Finder 6
        </p>
        <ul class="w-[86%] mx-auto my-4">
            <li class="mt-2 list-disc text-white text-lg md:text-xl font-light font-work text-justify">Batas Akhir Pendaftaran 20 Oktober 2024</li>
            <li class="mt-2 list-disc text-white text-lg md:text-xl font-light font-work text-justify">Pelakasanaan Lomba Pada 24 Oktober 2024</li>
            <li class="mt-2  list-disc text-white text-lg md:text-xl font-light font-work text-justify">Lomba terbuka untuk umum</li>
            <li class="mt-2list-disc text-white text-lg md:text-xl font-light font-work text-justify">Peserta berkompetisi secara individu serta memilih tema sesuai “KOLABORASI BUDAYA INDONESIA DAN JEPANG”. </li>
            <li class="mt-2 list-disc text-white text-lg md:text-xl font-light font-work text-justify">Peserta wajib mendaftar melalui Google Form yang telah disediakan.</li>
            <li class="mt-2 list-disc text-white text-lg md:text-xl font-light font-work text-justify">Pendaftaran lomba gratis.</li>
            <li class="mt-2 list-disc text-white text-lg md:text-xl font-light font-work text-justify">Kostum yang digunakan tidak mengandung unsur SARA, kekerasan atau pornografi.</li>
            <li class="mt-2 list-disc text-white text-lg md:text-xl font-light font-work text-justify">Peserta wajib hadir tepat waktu sesuai jadwal yang ditentukan.</li>

        </ul>
        <!-- Tombol -->
        <a href="https://docs.google.com/document/d/19oFxhH68nxuqoMFIV3I0qnyA-f_FgV5ARHPLr-z85ZI/edit?usp=drivesdk" target="_blank">
            <div class="flex w-full items-center mb-4">
                <button class="w-[90%] lg-w-[60%] flex justify-center items-center mx-auto h-fit border-[1px] font-work hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full text-lg md:text-2xl my-4">
                    Download Guidebook <img src="./img/description_FILL0_wght200_GRAD0_opsz24 1.svg" alt="" />
                </button>
            </div>
        </a>
        <a href="https://forms.gle/UZznAMQPPQNbMk716" target="_blank">
            <div class="flex w-full items-center">
                <button class="w-[90%] lg-w-[60%] mx-auto h-fit font-work py-2 px-6 bg-[#BA1F36] hover:bg-[#ba1f36e7] duration-300 text-white rounded-full text-lg md:text-2xl">Ikuti Lomba</button>
            </div>
        </a>
    </section>


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
</body>
<!-- Cursor CDN -->
<script src="https://unpkg.com/kursor"></script>
<script>
    new kursor({
        type: 4,
        removeDefaultCursor: true,
        color: '#ffffff',
    });
</script>
<!-- Cursor CDN -->


</html>