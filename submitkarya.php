<?php
include 'admin-one/dist/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil dan bersihkan data dari form
    $kategori = bersihkanInput($_POST["kategori_karya"]);
    $nama = bersihkanInput($_POST["Nama_Lengkap"]);
    $telepon = bersihkanInput($_POST["Nomor_Telepon"]);
    $email = bersihkanInput($_POST["Email"]);
    $instansi = bersihkanInput($_POST["Instansi"]);
    $judul = bersihkanInput($_POST["Judul_Karya"]);
    $sosial = bersihkanInput($_POST["Media_Sosial"]);
    $deskripsi = bersihkanInput($_POST["Deskripsi_Karya"]);
    $link = bersihkanInput($_POST["Link_Karya"]);

    // Simpan ke database
    $stmt = $koneksi->prepare("INSERT INTO submission (kategori_karya, nama, nomor_telepon, email, instansi, judul_karya, media_sosial, deskripsi_karya, link_karya) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $kategori, $nama, $telepon, $email, $instansi, $judul, $sosial, $deskripsi, $link);

    if ($stmt->execute()) {
        echo "<script>alert('Berhasil Submit!'); window.location.href='submitkarya.php';</script>";
    } else {
        echo "Gagal: " . $stmt->error;
    }

    $stmt->close();
    $koneksi->close();
}
?>


<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> -->
     <script src="https://cdn.tailwindcss.com"></script>
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
      .modal {
  display: none;
}

.modal.show {
  display: flex;
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
    <!-- <link rel="stylesheet" href="style.css" /> -->
</head>

<body class="bg-black pt-40">
<!-- Trigger button (optional) -->
<!-- <button onclick="openModal()" class="bg-emerald-500 text-white px-4 py-2 rounded">Lihat Info</button> -->

<!-- Modal -->
<div id="infoModal" class="modal fixed inset-0 bg-black bg-opacity-60 hidden justify-center items-center z-50">
  <div class="bg-neutral-900 rounded-xl px-8 py-6 w-11/12 md:w-1/2 text-center text-white shadow-lg relative">
    <h2 class="text-xl md:text-2xl font-bold mb-4 text-emerald-400">Kami Akan Kembali!</h2>
    <p class="text-sm md:text-base text-neutral-300">Ayo persiapkan karya kamu dan kumpulkan di sini.</p>
    <button onclick="closeModal()" class="mt-6 bg-emerald-400 hover:bg-emerald-600 text-black px-6 py-2 rounded-xl shadow">
      Oke
    </button>
  </div>
</div>

<?php require '_navbar.php'; ?>
    <div class=" flex flex-col justify-center items-center pb-20 ">
        <div class="pb-10">
            <h1 class="font-bold text-3xl text-white"> Submit Karya</h1>
        </div>
        <!-- Switch Button -->
        <div class="flex bg-neutral-800 w-10/12 md:w-6/12 rounded-xl overflow-hidden font-semibold italic">
            <button type="button"
                class="switch-btn bg-emerald-400 text-black px-4 py-2 rounded-xl w-full duration-300 ease-in-out"
                data-value="poster">Poster Illustration</button>
            <button type="button"
                class="switch-btn bg-neutral-800 text-neutral-700 px-4 py-2 rounded-xl w-full duration-300 ease-in-out"
                data-value="desain karakter">Design Character</button>
        </div>
        <br>
        <div class="flex justify-center items-center py-12 px-5 bg-neutral-900 rounded-xl w-10/12 md:w-6/12 ">
            <form id="form" action="submitkarya.php" method="post" class="flex flex-col gap-5 w-full">

                <!-- Hidden Input untuk simpan pilihan aktif -->
                <input type="hidden" name="kategori_karya" id="kategori_karya" value="poster">

                <div class="flex gap-5 justify-center">
                    <input type="text" id="nama" name="Nama_Lengkap" placeholder="Nama Lengkap"
                        class=" rounded-lg pl-4 py-2 text-white bg-neutral-800 placeholder:text-neutral-700 w-full">
                    <input type="text" id="nomortelepon" name="Nomor Telepon" placeholder="Nomor Telepon"
                        class=" rounded-lg pl-4 py-2 text-white bg-neutral-800 placeholder:text-neutral-700 w-full">
                </div>
                <div class="flex gap-5 justify-center">
                    <input type="text" id="email" name="Email" placeholder="Email"
                        class=" rounded-lg pl-4 py-2 text-white bg-neutral-800 placeholder:text-neutral-700 w-full">
                    <input type="text" id="instansi" name="Instansi" placeholder="Instansi (Opsional)"
                        class=" rounded-lg pl-4 py-2 text-white bg-neutral-800 placeholder:text-neutral-700 w-full">
                </div>
                <div class="flex gap-5 justify-center">
                    <input type="text" id="judulkarya" name="Judul_Karya" placeholder="Judul Karya"
                        class=" rounded-lg pl-4 py-2 text-white bg-neutral-800 placeholder:text-neutral-700 w-full">
                    <input type="text" id="sosialmedia" name="Media_Sosial" placeholder="Media Sosial"
                        class=" rounded-lg pl-4 py-2 text-white bg-neutral-800 placeholder:text-neutral-700 w-full">
                </div>
                <div>
                    <textarea type="text" id="deskripsi" name="Deskripsi_Karya" placeholder="Deskripsi karya" rows="13"
                        cols="50"
                        class="rounded-lg px-4 py-2 text-white bg-neutral-800 w-full placeholder:text-neutral-700 placeholder:text-center placeholder:pt-32"></textarea>
                </div>
                <div>
                    <input type="text" id="linkkarya" name="Link_Karya" placeholder="Link Google Drive"
                        class="rounded-lg pl-4 py-2 text-white bg-neutral-800 w-full placeholder:text-neutral-700">
                    <p class="italic text-neutral-500 text-sm pt-2">*Google Drive berisi surat Pernyataan Orisinalitas,
                        Karya, dan video proses pembuatan</p>
                    <div class="hover:cursor-pointer">
                        <a href="submission.php#syarat"
                        class="italic text-emerald-400 hover:text-emerald-600 text-sm pt-2">Baca Ketentuan disini</a>
                    </div>
                </div>
                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-emerald-400 hover:bg-emerald-600 text-black px-20 py-2 rounded-xl shadow">Submit</button>

                </div>
            </form>
        </div>
    </div>

    <script>
  function openModal() {
    const modal = document.getElementById('infoModal');
    modal.classList.add('show');
    modal.classList.remove('hidden');
  }

  function closeModal() {
    const modal = document.getElementById('infoModal');
    modal.classList.remove('show');
    modal.classList.add('hidden');
  }

  // Modal muncul otomatis saat halaman dibuka
  window.addEventListener('load', () => {
    openModal();
  });
</script>

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
<script src="https://unpkg.com/kursor"></script>
<script>
    new kursor({
        type: 4,
        removeDefaultCursor: true,
        color: '#ffffff',
    });
</script>
<script>
    const buttons = document.querySelectorAll('.switch-btn');
    const hiddenInput = document.getElementById('kategori_karya');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            buttons.forEach(b => b.classList.remove('bg-emerald-400', 'text-black'));
            buttons.forEach(b => b.classList.add('bg-neutral-800', 'text-neutral-700'));

            btn.classList.add('bg-emerald-400', 'text-black');
            btn.classList.remove('bg-neutral-800', 'text-neutral-700');

            hiddenInput.value = btn.dataset.value;
        });
    });

    const form = document.getElementById('form');

    form.addEventListener('submit', function (e) {
        const inputs = form.querySelectorAll('input[type="text"], textarea');
        let valid = true;

        inputs.forEach(input => {
            if (input.id !== 'instansi' && input.value.trim() === '') {
                valid = false;
                input.classList.add('border', 'border-red-500');
            } else {
                input.classList.remove('border', 'border-red-500');
            }
        });

        if (!valid) {
            e.preventDefault(); // Mencegah submit
            alert("Mohon lengkapi semua field terlebih dahulu!");
        }
    });

</script>

</html>