<?php
session_start();

// Koneksi ke database
include 'admin-one/dist/koneksi.php';

// Ambil user_id dari session jika ada
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Query untuk mendapatkan data dari tabel event dengan show_event = 1 dan diurutkan berdasarkan urutan_show
$query_event = "SELECT id_event, judul_event, speakers_event, jadwal_event, waktu_event, kuota, lokasi_event, tiket_event, event_status 
FROM event 
WHERE show_event = 1 
ORDER BY urutan_show ASC";


// Persiapkan statement untuk query event
$stmt_event = mysqli_prepare($koneksi, $query_event);
if (!$stmt_event) {
  die('Prepare statement event failed: ' . mysqli_error($koneksi));
}
mysqli_stmt_execute($stmt_event);

// Ambil hasil query data event
$result_event = mysqli_stmt_get_result($stmt_event);

// Array untuk menyimpan data event
$events_data = [];
while ($row_event = mysqli_fetch_assoc($result_event)) {
  $id_event = $row_event['id_event'];

  // Query untuk menghitung jumlah pengguna yang mendaftar untuk event ini
  $query_count_users = "SELECT COUNT(*) as total FROM tiket WHERE id_event = ?";
  $stmt_count_users = mysqli_prepare($koneksi, $query_count_users);
  mysqli_stmt_bind_param($stmt_count_users, "i", $id_event);
  mysqli_stmt_execute($stmt_count_users);
  $result_count_users = mysqli_stmt_get_result($stmt_count_users);
  $row_count_users = mysqli_fetch_assoc($result_count_users);

  // Total kuota dan total pengguna yang telah mendaftar
  $total_kuota = isset($row_event['kuota']) ? intval($row_event['kuota']) : 0; // Pastikan menjadi integer
  $total_users = intval($row_count_users['total']); // Pastikan total_users adalah integer

  // Hitung sisa kuota
  $sisa_kuota = $total_kuota - $total_users;

  // Tambahkan sisa kuota ke data event
  $row_event['sisa_kuota'] = $sisa_kuota;

  // Simpan data event ke dalam array
  $events_data[$id_event] = $row_event; // Simpan berdasarkan id_event
}

// Tutup statement event
mysqli_stmt_close($stmt_event);

// Jika user sudah login, cek tiket yang dimiliki
$events_with_tickets = [];
if ($user_id) {
  // Query untuk mengecek apakah event sudah terhubung dengan user di tabel tiket
  $query_check_tiket = "SELECT id_event FROM tiket WHERE id_user = ?";

  // Persiapkan statement untuk query tiket
  $stmt_check_tiket = mysqli_prepare($koneksi, $query_check_tiket);
  if (!$stmt_check_tiket) {
    die('Prepare statement check tiket failed: ' . mysqli_error($koneksi));
  }
  mysqli_stmt_bind_param($stmt_check_tiket, "i", $user_id);
  mysqli_stmt_execute($stmt_check_tiket);

  // Ambil hasil query data tiket
  $result_check_tiket = mysqli_stmt_get_result($stmt_check_tiket);

  // Array untuk menyimpan id_event yang sudah terhubung dengan user
  while ($row_check_tiket = mysqli_fetch_assoc($result_check_tiket)) {
    $events_with_tickets[] = $row_check_tiket['id_event'];
  }

  // Tutup statement check tiket
  mysqli_stmt_close($stmt_check_tiket);
}


// Fungsi untuk generate tiket code
function generateTicketCode($id_event, $user_id)
{
  // Generate 6 digit random alphanumeric
  $random_part = substr(str_shuffle(str_repeat('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', 6)), 0, 6);
  $random_partt = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ', 2)), 0, 2);
  // Combine components into tiket_code
  $tiket_code = $random_partt . $id_event . $user_id . $random_part;

  return $tiket_code;
}

// Handle POST request untuk mendapatkan tiket
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_event'])) {
  // Jika user tidak login, beri alert untuk login
  if (!$user_id) {
    echo '<script>alert("Harap Login terlebih dahulu!");</script>';
  } else {
    // Ambil id_event dari form
    $id_event = $_POST['id_event'];

    // Jika user belum memiliki tiket untuk event tersebut, insert tiket baru
    if (!in_array($id_event, $events_with_tickets)) {
      // Generate tiket_code
      $tiket_code = generateTicketCode($id_event, $user_id);

      // Query untuk insert tiket baru
      $query_insert_tiket = "INSERT INTO tiket (id_user, id_event, tiket_code, created_tiket) VALUES (?, ?, ?, NOW())";

      // Persiapkan statement untuk insert tiket
      $stmt_insert_tiket = mysqli_prepare($koneksi, $query_insert_tiket);
      if (!$stmt_insert_tiket) {
        die('Prepare statement insert tiket failed: ' . mysqli_error($koneksi));
      }

      // Bind parameter ke statement
      mysqli_stmt_bind_param($stmt_insert_tiket, "iis", $user_id, $id_event, $tiket_code);

      // Eksekusi statement
      if (mysqli_stmt_execute($stmt_insert_tiket)) {
        // Jika insert berhasil, beri alert sukses
        echo "<script>
                alert('Ticket berhasil di claim. Cek profile untuk mengambil tiket.');
                document.location='account.php';
              </script>";
      } else {
        // Jika insert gagal, beri alert gagal
        echo '<script>alert("Gagal mengambil tiket. Silakan coba lagi.");</script>';
      }

      // Tutup statement insert tiket
      mysqli_stmt_close($stmt_insert_tiket);
    }
  }
}

// Tutup koneksi MySQL
mysqli_close($koneksi);
?>



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
  <style>
    .filter-button.active {
      background-color: #FFFFFF;
      color: #000000;
    }
  </style>
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
  <style>
    .button-container {
      display: flex;
      gap: 10px;
      margin: 20px;
    }

    .hidden {
      display: none;
    }

    .button {
      font-family: 'Work Sans';
      border: 1px solid white;
      padding: 10px 20px;
      color: white;
      background: transparent;
      border-radius: 50px;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      text-align: center;
    }

    .button:hover {
      background: rgba(255, 255, 255, 0.25);
    }
  </style>

  <title>Finder 6 - Homepage</title>
  <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

  <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />

  <link rel="stylesheet" href="style.css" />

  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

</head>

<body>
  <?php
  require '_navbar.php';
  ?>
  <section id="Homepage" style="background-image: url(img/bghero.png)"
    class="bg-cover flex container max-w-full h-screen bg-slate-400">
    <div class="w-full flex flex-col items-center my-auto gap-4 md:gap-5">
      <div class="container flex-col items-center gap-6">
        <img src="img/supgraf1.svg" alt="" class="w-4/6 md:w-1/2 lg:w-1/4 mx-auto" />
        <h1 style="font-family: 'Work Sans'"
          class="text-2xl mx-auto w-5/6 md:w-2/3 md:text-4xl lg:w-1/2 xl:w-1/3 text-center text-white">Jelajahi
          Nusantara Dengan Keberagaman Budaya nya</h1>
      </div>
      <!-- Countdown -->
      <svg id="_a4s7ic" viewBox="0 0 650 170" class="mt-[-10px]" fill="#ffffff" xmlns="http://www.w3.org/2000/svg"
        aria-labelledby="_a4s7ic_title _a4s7ic_desc">
        <title>Countdown timer to: 2024-10-21T00:00:00.000+07:00</title>
        <desc>A countdown timer to 2024-10-21T00:00:00.000+07:00 with days, hours, minutes, and seconds displayed.
        </desc>

        <rect x="0" y="0" width="650" height="170" style="fill: rgba(138, 19, 19, 0)" />

        <text x="12.5%" y="64%" text-anchor="middle" name="days">-</text>
        <text x="12.5%" y="93%" class="label" text-anchor="middle">Days</text>

        <text x="37.5%" y="64%" text-anchor="middle" name="hours">-</text>
        <text x="37.5%" y="93%" class="label" text-anchor="middle">Hours</text>

        <text x="62.5%" y="64%" text-anchor="middle" name="minutes">-</text>
        <text x="62.5%" y="93%" class="label" text-anchor="middle">Minutes</text>

        <text x="87.5%" y="64%" text-anchor="middle" name="seconds">-</text>
        <text x="87.5%" y="93%" class="label" text-anchor="middle">Seconds</text>

        <text x="0" y="0" text-anchor="middle" name="end" class="label" visibility="hidden"></text>

        <script type="text/javascript">
          (() => {
            const loadTime = new Date("2024-10-21T00:00:00.000+07:00");
            const [days, hours, minutes, seconds, end] = document.querySelectorAll('svg > [name]');
            const run = () => {
              const timeDiff = loadTime - Date.now();
              const totalSeconds = Math.max(0, Math.floor(timeDiff / 1000));
              const expired = totalSeconds <= 0;
              const [daysDiff, hoursDiff, minutesDiff, secondsDiff] = [
                Math.max(Math.floor(totalSeconds / 86400), 0),
                Math.max(Math.floor((totalSeconds % 86400) / 3600), 0),
                Math.max(Math.floor((totalSeconds % 3600) / 60), 0),
                Math.max(totalSeconds % 60, 0)
              ];
              days.textContent = daysDiff.toLocaleString();
              hours.textContent = hoursDiff;
              minutes.textContent = minutesDiff;
              seconds.textContent = secondsDiff;
              if (expired) {
                clearInterval(window.countdown_timer);
                document.querySelectorAll('text').forEach((el) => el.setAttribute('visibility', el.getAttribute("visibility") === "hidden" ? "visible" : "hidden"));
              }
            };
            window.countdown_timer = setInterval(run, 1000);
            run();
          })();
        </script>

        <style type="text/css">
          @import url('https://fonts.googleapis.com/css2?family=Work%20Sans:wght@400;600&display=swap');

          #_a4s7ic {
            font-family: Work Sans;
            height: 100%;
            width: 60%;
            max-width: 450px;
            max-height: 100px;
          }

          #_a4s7ic text {
            font-size: 6rem;
            font-weight: 600;
          }

          #_a4s7ic text.label {
            font-size: 2rem;
            font-weight: 400;
          }
        </style>
      </svg>

      <!-- ---------- -->

      <div class="button-container">
        <!-- Tombol Pelajari Lebih Lanjut -->
        <a href="#about" class="button" id="pelajari">Pelajari Lebih Lanjut</a>

        <!-- Tombol Lihat Pameran Virtual -->
        <a href="#karya" class="button hidden" id="pameran">Lihat Pameran Virtual</a>
        <a href="#jadwal"><button style="font-family: 'Work Sans'"
            class=" bg-[#0D0D0D] hover:bg-white hover:bg-opacity-25 py-2 px-6 text-white rounded-full md:text-lg">Lihat
            Acara</button></a>
      </div>
      <!-- Jika Belum 24 Juli 2024 -->
      <script>
        document.addEventListener('DOMContentLoaded', () => {
          const today = new Date();
          const targetDate = new Date('2024-07-24T00:00:00+07:00'); // 24 Juli 2024 WIB

          if (today >= targetDate) {
            document.getElementById('pelajari').classList.add('hidden');
            document.getElementById('pameran').classList.remove('hidden');
          } else {
            document.getElementById('pelajari').classList.remove('hidden');
            document.getElementById('pameran').classList.add('hidden');
          }
        });
      </script>


    </div>
  </section>
  <!-- ---------------------- -->
  <!-- ---------------------- -->

  <!-- About Us -->
  <section id="about"
    class="flex flex-col md:flex-row container max-w-full relative bg-[#0D0D0D] overflow-hidden py-10">
    <!-- Finder General -->
    <img src="img/aboutus.png" alt="" class="absolute top-1/3 min-h-max" />
    <div class="flex flex-col container max-w-full items-center gap-4 mt-20">
      <img src="img/(1) Finder 6 Logo Vertical Full White.png" alt="" class="h-[75px] md:h-[100px]" />
      <h1 style="font-family: 'Work Sans'" class="text-white text-2xl md:text-3xl font-semibold">Acara Finder</h1>
      <p style="font-family: 'Work Sans'" class="text-white w-4/5 lg:1/2 text-center text-sm md:text-lg">
        Finder adalah sebuah annual event yang diadakan oleh DKV UPI sebagai bentuk eksitensi diri terhadapa dunia.
        Dalam Finder terdapat beberapa rangkaian acara yang memiliki beber apa tujuan seperti memberikan wawasan
        mengenai desain
        serta hal-hal umum lainnya. Ada pula seperti perlombaan untuk menjadi wadah kreatifitas. Finder tiap munculnya
        selalu membawa tema untuk dijadikan sebagai dasar pembawaan.
      </p>
    </div>
    <!-- Finder 6 Deskrip -->
    <img src="img/aboutus.png" alt="" class="absolute top-1/3 min-h-max" />
    <div class="flex flex-col container max-w-full items-center gap-4 my-20">
      <img src="img/FinderLogo.svg" alt="" class="h-[75px] md:h-[100px]" />
      <h1 style="font-family: 'Work Sans'" class="text-white text-2xl md:text-3xl font-semibold">Finder 6</h1>
      <p style="font-family: 'Work Sans'" class="text-white w-4/5 lg:1/2 text-center text-sm md:text-lg">
        Finder 6 Pusaka adalah sebuah Estafet terhadap warisan budaya di Nusantara, yang menjadi esensi, keberadaan
        hidup manusia yang telah dilalui, saat ini dan nanti, hingga menjadi nilai yang abadi.
      </p>
    </div>
  </section>
  <!-- -------- -->
  <!-- Our Program -->

  <section id="program"
    class="flex flex-col items-center max-w-full overflow-hidden relative bg-[#0D0D0D] gap-6 py-10 pt-28">
    <div class="flex flex-col items-center">
      <h1 style="font-family: 'Work Sans'" class="text-2xl md:text-3xl text-white font-semibold">Program Finder</h1>
      <h2 style="font-family: 'Work Sans'" class="text-white text-center text-sm md:text-lg">Kami Menyediakan Beberapa
        Aktivitas Yang Seru</h2>
    </div>
    <!-- Grid Wrap -->
    <div class="grid container grid-cols-1 md:grid-cols-2 md:scale-100 lg:grid-cols-3 justify-items-center gap-4">
      <!-- Card 1 (Lomba) -->
      <a>
        <div class="flex container w-[340px] overflow-hidden rounded-lg">
          <div style="background-image: url(img/ourprogram/lomba.png)"
            class="container flex flex-col relative saturate-100 md:saturate-0 hover:saturate-100 duration-300">
            <img src="img/ourprogram/supgraflomba.svg" alt="" class="h-[55px] absolute ml-2 mt-2" />
            <div class="container flex flex-col ml-2 mb-2 mt-28">
              <h1 style="font-family: 'Work Sans'" class="text-2xl text-white font-semibold">Kompetisi</h1>
              <p style="font-family: 'Work Sans'" class="font-light text-lg text-white">
                Ikuti Berbagai Lomba Dan Menangkan <br />
                Hadiah Menarik
              </p>
            </div>
          </div>
        </div>
      </a>

      <!-- Card 2 (Seminar)-->
      <a>
        <div class="flex container w-[340px] overflow-hidden rounded-lg">
          <div style="background-image: url(img/ourprogram/seminar.png)"
            class="container flex flex-col relative saturate-100 md:saturate-0 hover:saturate-100 duration-300">
            <img src="img/ourprogram/supgrafseminar.svg" alt="" class="h-[55px] absolute ml-2 mt-2" />
            <div class="container flex flex-col ml-2 mb-2 mt-28">
              <h1 style="font-family: 'Work Sans'" class="text-2xl text-white font-semibold">Gelar Wicara</h1>
              <p style="font-family: 'Work Sans'" class="font-light text-lg text-white">Saksikan berbagai Seminar Dan
                Acara Talkshow Dalam Finder 6</p>
            </div>
          </div>
        </div>
      </a>
      <!-- Card 3 (Workshop)-->
      <div class="flex container w-[340px] overflow-hidden rounded-lg">
        <div style="background-image: url(img/ourprogram/workshop.png)"
          class="container flex flex-col relative saturate-100 md:saturate-0 hover:saturate-100 duration-300">
          <img src="img/ourprogram/supgrafworkshop.svg" alt="" class="h-[55px] absolute ml-2 mt-2" />
          <div class="container flex flex-col ml-2 mb-2 mt-28">
            <h1 style="font-family: 'Work Sans'" class="text-2xl text-white font-semibold">Loka Karya</h1>
            <p style="font-family: 'Work Sans'" class="font-light text-lg text-white">Ikuti Berbagai Kegiatan Loka Karya
              yang Seru & Menarik</p>
          </div>
        </div>
      </div>
      <!-- Card 4 (Festival)-->
      <div class="flex container w-[340px] overflow-hidden rounded-lg">
        <div style="background-image: url(img/ourprogram/festival.png)"
          class="container flex flex-col relative saturate-100 md:saturate-0 hover:saturate-100 duration-300">
          <img src="img/ourprogram/supgraffestival.svg" alt="" class="h-[55px] absolute ml-2 mt-2" />
          <div class="container flex flex-col ml-2 mb-2 mt-28">
            <h1 style="font-family: 'Work Sans'" class="text-2xl text-white font-semibold">Pertunjukan</h1>
            <p style="font-family: 'Work Sans'" class="font-light text-lg text-white">Kunjungi Berbagai Festival Budaya
              Dalam Finder Pusaka</p>
          </div>
        </div>
      </div>
      <!-- Card 5 (Exhibition)-->
      <a>
        <div class="flex container w-[340px] overflow-hidden rounded-lg">
          <div style="background-image: url(img/ourprogram/exhibition.png)"
            class="container flex flex-col relative saturate-100 md:saturate-0 hover:saturate-100 duration-300">
            <img src="img/ourprogram/supgrafexhibition.svg" alt="" class="h-[55px] absolute ml-2 mt-2" />
            <div class="container flex flex-col ml-2 mb-2 mt-28">
              <h1 style="font-family: 'Work Sans'" class="text-2xl text-white font-semibold">Pameran</h1>
              <p style="font-family: 'Work Sans'" class="font-light text-lg text-white">Kunjungi Berbagai Pameran Yang
                Mengingatkan Berbagai Budaya</p>
            </div>
          </div>
        </div>
      </a>
      <!-- Card 6 (Bazaar)-->
      <div class="flex container w-[340px] overflow-hidden rounded-lg">
        <div style="background-image: url(img/ourprogram/bazaar.png)"
          class="container flex flex-col relative saturate-100 md:saturate-0 hover:saturate-100 duration-300">
          <img src="img/ourprogram/supgrafbazaar.svg" alt="" class="h-[55px] absolute ml-2 mt-2" />
          <div class="container flex flex-col ml-2 mb-2 mt-28">
            <h1 style="font-family: 'Work Sans'" class="text-2xl text-white font-semibold">Bazar</h1>
            <p style="font-family: 'Work Sans'" class="font-light text-lg text-white">Ikuti Bazar dan Beli berbagai
              Banyak Hal Yang Bisa Kamu Dapatkan</p>
          </div>
        </div>
      </div>
      <!-- ------ -->
    </div>
    <!-- End Grid -->
  </section>

  <!-- ClickBaitKarya -->
  <section id="karya" class="container flex flex-col max-w-full bg-[#0D0D0D] items-center py-12 pt-28">
    <!-- Info Karya -->
    <div
      class="container flex flex-col md:flex-row max-w-full bg-gradient-to-br from-[#131313] to-[#1B1A1A] w-[92%] rounded-xl px-8 py-8 items-center gap-4">
      <!--  Column1-->
      <div class="flex flex-col gap-6 items-center md:items-start w-fit xl:w-[75%]">
        <h1 class="font-work font-bold text-xl md:text-2xl lg:text-3xl text-white w-fit text-center md:text-start">Lihat
          Berbagai Karya Finder</h1>
        <p class="text-base lg:text-lg text-white w-full lg:w-5/6 font-work font-light text-center md:text-start">
          Finder Menghadirkan Sebuah Pameran Virtual Dimana Kamu Bisa Melihat Karya - Karya yang Telah Dibuat Oleh
          Seniman Dan Designer Yang Berkontribusi Dalam Acara ini
        </p>
        <a id="pameran-button" href="#"><button
            class="text-base lg:text-xl text-white px-6 py-4 bg-[#BA1F36] w-fit rounded-lg font-work hover:bg-[#ba1f1f] duration-150">Lihat
            Karya</button></a>
      </div>
      <!-- Collumn 2 -->
      <div style="background-image: url(img/thumbnailpameran.png)"
        class="bg-cover bg-center w-[90%] lg:w-[60%] bg-slate-300 h-[250px] md:h-[350px] rounded-xl mt-4 md:mt-0 order-first md:order-last"></div>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        // Tanggal target (24 Juli 2024)
        const targetDate = new Date('2024-07-24T00:00:00+07:00'); // Waktu Indonesia Barat (UTC+7)
        const now = new Date();

        // Elemen tombol
        const button = document.getElementById('pameran-button');

        if (now >= targetDate) {
          // Jika sudah melewati atau sama dengan tanggal target, arahkan tombol ke detailpameran.php
          button.href = 'detailpameran.php';
        } else {
          // Jika belum mencapai tanggal target, tambahkan event listener untuk menampilkan alert
          button.addEventListener('click', function (event) {
            event.preventDefault(); // Mencegah navigasi default
            alert("Halaman pameran dapat dibuka pada 24 Juli 2024");
          });
        }
      });
    </script>
    <!--  -->
  </section>
  <!--ClickBaitKarya -->
  <!-- Jadwal -->
  <section id="jadwal" class="container flex flex-col max-w-full bg-[#0D0D0D] gap-4 py-10 pt-28">
    <!-- H1 -->
    <h1 style="font-family: 'Work Sans'" class="text-white px-6 md:px-16 text-2xl md:text-3xl md:mx-auto">Jadwal Acara
    </h1>

    <!-- Filter Jadwal -->
    <div class="flex flex-row gap-2  mx-auto justify-start max-w-[90%] overflow-x-auto snap-x">
      <div class="w-fit px-1 flex flex-shrink-0">
        <!-- Semua Jadwal -->
        <!-- <a href="?filter=#jadwal"
        class="filter-button py-2 px-4 text-white rounded-full hover:bg-white hover:bg-opacity-25 text-lg">Semua
        Jadwal</a> -->

        <!-- List Jadwal Unik dari Data -->
        <?php
        // Mengumpulkan jadwal_event unik dari data event
        $unique_jadwal_events = array_unique(array_column($events_data, 'jadwal_event'));

        // Menampilkan link filter untuk setiap jadwal_event unik
        foreach ($unique_jadwal_events as $jadwal_event) {
          // Format tanggal dari yyyy-mm-dd menjadi dd F
          $formatted_date = date('d F', strtotime($jadwal_event));

          // Tentukan kelas aktif untuk filter yang sedang dipilih
          $activeClass = isset($_GET['filter']) && $_GET['filter'] === urlencode($jadwal_event) ? 'active' : '';

          // Tampilkan link filter dengan format tanggal yang diinginkan
          echo '<a href="?filter=' . urlencode($jadwal_event) . '#jadwal" class="filter-button py-2 px-3 text-white rounded-full hover:bg-white hover:bg-opacity-25 text-base flex flex-shrink-0 ' . $activeClass . '">' . htmlspecialchars($formatted_date) . '</a>';
        }
        ?>
      </div>
    </div>

    <?php
    // Mengurutkan data event berdasarkan tanggal dan waktu
    usort($events_data, function ($a, $b) {
      return strtotime($b['jadwal_event']) - strtotime($a['jadwal_event']);
    });

    // Mengelompokkan data event berdasarkan tanggal
    $grouped_events = [];
    foreach ($events_data as $event) {
      $jadwal_event = $event['jadwal_event'];
      $grouped_events[$jadwal_event][] = $event;
    }

    // Membatasi data event menjadi 3 per tanggal
    $limited_events = [];
    foreach ($grouped_events as $jadwal_event => $events) {
      // Ambil maksimal 3 event untuk setiap jadwal_event
      $limited_events[$jadwal_event] = array_slice($events, 0, 3);
    }

    // Mengambil 3 event teratas secara keseluruhan untuk ditampilkan secara default
    $top_events = array_slice($events_data, 0, 3);

    // Event List
    $events_found = false;

    // Menampilkan event berdasarkan filter jadwal_event yang sudah dibatasi
    if (!isset($_GET['filter']) || $_GET['filter'] === '') {
      // Tampilkan 3 event teratas jika tidak ada filter
      foreach ($top_events as $event) {
        // Set flag bahwa setidaknya ada satu event yang akan ditampilkan
        $events_found = true;

        // Tampilkan event
        ?>
        <div
          class="flex flex-col lg:flex-row lg:justify-between mx-6 md:mx-16 border-b-[1px] border-b-white py-4 gap-6 lg:items-center">
          <!-- Flex-Kiri -->
          <div class="flex flex-col gap-4 lg:gap-2 w-full">
            <div class="flex flex-wrap lg:flex-row gap-2 md:gap-4 ">
              <h1 style="font-family: 'Work Sans'" class="text-white text-lg md:text-xl font-normal">
                <?php echo $event['waktu_event']; ?>
              </h1>
              <li style="font-family: 'Work Sans'" class="text-white text-lg md:text-xl font-normal">Kuota :
                <?php echo $event['kuota']; ?>
              </li>
            </div>
            <div class="flex flex-col gap-2">
              <h1 style="font-family: 'Work Sans'" class="text-white text-2xl lg:text-3xl font-medium">
                <?php echo $event['judul_event']; ?>
              </h1>
              <h1 style="font-family: 'Work Sans'" class="text-white md:text-lg font-light">By
                <?php echo $event['speakers_event']; ?>
              </h1>
            </div>
          </div>

          <!-- Tombol -->
          <div class="flex flex-col w-full md:max-w-[280px] items-start lg:items-center gap-4">
            <a href="detailevent.php?id_event=<?php echo $event['id_event']; ?>">
              <button style="font-family: 'Work Sans'"
                class="w-[275px] h-fit border-[1px] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg">
                Lihat Detail
              </button>
            </a>
            <?php if (!$user_id || !in_array($event['id_event'], $events_with_tickets)): ?>
              <?php
              // Mengambil sisa kuota dari $event langsung
              $sisa_kuota = isset($event['sisa_kuota']) ? $event['sisa_kuota'] : 0;
              ?>

              <?php if ($event['event_status'] == 1): // Pengecekan status event ?>
                <?php if ($sisa_kuota > 0): // Jika kuota masih ada ?>
                  <form method="post">
                    <input type="hidden" name="id_event" value="<?php echo $event['id_event']; ?>">
                    <button style="font-family: 'Work Sans'"
                      class="w-[275px] h-fit border-[1px] bg-white hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white hover:text-white rounded-full md:text-lg text-black">
                      Dapatkan Tiket
                    </button>
                  </form>
                <?php else: // Jika kuota habis ?>
                  <button style="font-family: 'Work Sans'"
                    class="w-[275px] h-fit border-[1px] bg-white hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white hover:text-white rounded-full md:text-lg text-black"
                    disabled>
                    Tiket telah habis
                  </button>
                <?php endif; ?>
              <?php elseif ($event['event_status'] == 2): // Status event belum dimulai ?>
                <button style="font-family: 'Work Sans'"
                  class="w-[275px] h-fit border-[1px] bg-white hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white hover:text-white rounded-full md:text-lg text-black"
                  disabled>Pendaftaran Belum Dibuka
                </button>
              <?php else: // Jika event sudah berakhir ?>
                <button style="font-family: 'Work Sans'"
                  class="w-[275px] h-fit border-[1px] bg-white hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white hover:text-white rounded-full md:text-lg text-black"
                  disabled>Event Sudah Berakhir
                </button>
              <?php endif; ?>
            <?php else: // Jika user sudah memiliki tiket ?>
              <div>
                <p style="font-family: 'Work Sans'" class="text-white text-lg md:text-xl">Kamu sudah memiliki tiket.</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <?php
      }
    } else {
      // Jika ada filter, tampilkan event sesuai dengan filter
      foreach ($limited_events as $jadwal_event => $events) {
        foreach ($events as $event) {
          // Cek apakah event harus ditampilkan berdasarkan filter jadwal
          if ($event['jadwal_event'] !== urldecode($_GET['filter'])) {
            continue; // Skip event jika tidak cocok dengan filter
          }

          // Set flag bahwa setidaknya ada satu event yang akan ditampilkan
          $events_found = true;

          // Tampilkan event
          ?>
          <div
            class="flex flex-col lg:flex-row lg:justify-between mx-6 md:mx-16 border-b-[1px] border-b-white py-4 gap-6 lg:items-center">
            <!-- Flex-Kiri -->
            <div class="flex flex-col gap-4 lg:gap-2 w-full">
              <div class="flex flex-wrap lg:flex-row gap-2 md:gap-4 ">
                <h1 style="font-family: 'Work Sans'" class="text-white text-lg md:text-xl font-normal">
                  <?php echo $event['waktu_event']; ?>
                </h1>
                <li style="font-family: 'Work Sans'" class="text-white text-lg md:text-xl font-normal">Kuota :
                  <?php echo $event['kuota']; ?>
                </li>
              </div>
              <div class="flex flex-col gap-2">
                <h1 style="font-family: 'Work Sans'" class="text-white text-2xl lg:text-3xl font-medium">
                  <?php echo $event['judul_event']; ?>
                </h1>
                <h1 style="font-family: 'Work Sans'" class="text-white md:text-lg font-light">By
                  <?php echo $event['speakers_event']; ?>
                </h1>
              </div>
            </div>

            <!-- Tombol -->
            <div class="flex flex-col w-full md:max-w-[280px] items-start lg:items-center gap-4">
              <a href="detailevent.php?id_event=<?php echo $event['id_event']; ?>">
                <button style="font-family: 'Work Sans'"
                  class="w-[275px] h-fit border-[1px] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg">
                  Lihat Detail
                </button>
              </a>
              <?php if (!$user_id || !in_array($event['id_event'], $events_with_tickets)): ?>
                <?php
                // Mengambil sisa kuota dari $event langsung
                $sisa_kuota = isset($event['sisa_kuota']) ? $event['sisa_kuota'] : 0;
                ?>

                <?php if ($event['event_status'] == 1): // Pengecekan status event ?>
                  <?php if ($sisa_kuota > 0): // Jika kuota masih ada ?>
                    <form method="post">
                      <input type="hidden" name="id_event" value="<?php echo $event['id_event']; ?>">
                      <button style="font-family: 'Work Sans'"
                        class="w-[275px] h-fit border-[1px] bg-white hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white hover:text-white rounded-full md:text-lg text-black">
                        Dapatkan Tiket
                      </button>
                    </form>
                  <?php else: // Jika kuota habis ?>
                    <button style="font-family: 'Work Sans'"
                      class="w-[275px] h-fit border-[1px] bg-white hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white hover:text-white rounded-full md:text-lg text-black"
                      disabled>
                      Tiket telah habis
                    </button>
                  <?php endif; ?>
                <?php elseif ($event['event_status'] == 2): // Status event belum dimulai ?>
                  <button style="font-family: 'Work Sans'"
                    class="w-[275px] h-fit border-[1px] bg-white hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white hover:text-white rounded-full md:text-lg text-black"
                    disabled>Pendaftaran Belum Dibuka
                  </button>
                <?php else: // Jika event sudah berakhir ?>
                  <button style="font-family: 'Work Sans'"
                    class="w-[275px] h-fit border-[1px] bg-white hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white hover:text-white rounded-full md:text-lg text-black"
                    disabled>Event Sudah Berakhir
                  </button>
                <?php endif; ?>
              <?php else: // Jika user sudah memiliki tiket ?>
                <div>
                  <p style="font-family: 'Work Sans'" class="text-white text-lg md:text-xl">Kamu sudah memiliki tiket.</p>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <?php
        }
      }
    }

    // Tampilkan pesan jika tidak ada event yang ditemukan
    if (!$events_found) {
      echo '<p class="text-white text-xl">Tidak ada event ditemukan.</p>';
    }
    ?>


    <!-- Tampilkan tombol lihat lainnya -->
    <div class="flex z-0 flex-col items-center self-center max-w-full gap-4 w-[812px]">
      <a href="ticket.php">
        <button style="font-family: 'Work Sans'"
          class="w-[300px] border-[1px] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg">
          Lihat Lainnya
        </button>
      </a>
    </div>



  </section>

  <!-- LOmba -->
  <section style="background-image: url(img/bglomba.png);"
    class=" bg-cover bg-center flex overflow-hidden relative flex-col justify-center px-9 text-xl h-[450px] text-center text-white bg-[#0D0D0D] max-md:px-5">
    <div class="flex z-0 flex-col items-center self-center max-w-full gap-4 w-[812px]">
      <p class="text-lg lg:text-xl font-work font-semibold  max-md:max-w-full"></p>
      28 Agustus - 15 Oktober
      </p>
      <h2 class="self-stretch text-2xl lg:text-4xl font-work font-semibold max-md:max-w-full">
        Ikuti Lomba Ilustrasi Finder 6 Kolaborasi Dengan Mizan Pustaka
      </h2>
      <p class="text-lg lg:text-xl font-work font-light  max-md:max-w-full">
        We are delighted to announce an international illustration competition hosted by Finder 6 Pusaka in partnership
        with Mizan, we extend a warm invitation to all of you to participate!
      </p>
      <a href="detaillomba.php"><button style="font-family: 'Work Sans'"
          class="border-[1px] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg">Lihat
          Detail</button></a>
    </div>
  </section>
  <!-- Lomba Finder -->
  <!-- Lomba 2 -->
  <section style="background-image: url(img/bgcosplay1.png);"
    class=" bg-cover bg-center flex overflow-hidden relative flex-col justify-center px-9 text-xl h-[450px] text-center text-white bg-[#0D0D0D] max-md:px-5">
    <div class="flex z-0 flex-col items-center self-center max-w-full gap-4 w-[812px]">
      <p class="text-lg lg:text-xl font-work font-semibold  max-md:max-w-full"></p>
      24 Oktober
      </p>
      <h2 class="self-stretch text-2xl lg:text-4xl font-work font-semibold max-md:max-w-full">
        Ikuti Kompetisi Cosplay Finder 6 Dan Menangkan Hadiah
      </h2>
      <p class="text-lg lg:text-xl font-work font-light  max-md:max-w-full">
        Kompetisi cosplay mengusung tema perpaduan antara budaya tradisional Indonesia dengan budaya Jepang, dimana
        menyesuaikan dengan tema acara FINDER 6th yaitu Pusaka: Merentang Waktu dan Ruang
      </p>
      <a href="detailcosplay.php"><button style="font-family: 'Work Sans'"
          class="border-[1px] text-base  hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg">Lihat
          Detail</button></a>
    </div>
  </section>
  <!-- ----- -->
  <!-- <section id="lomba" class="container flex flex-col max-w-full bg-[#0D0D0D] items-center py-12 pt-28"> -->
  <!-- Info Lomba -->
  <!-- <div class="container flex flex-col md:flex-row max-w-full bg-gradient-to-br from-[#131313] to-[#1B1A1A] w-[92%] rounded-xl px-8 py-8 items-center gap-4"> -->
  <!--  Column1-->
  <!-- <div class="flex flex-col gap-6 items-center md:items-start w-fit xl:w-[75%]">
          <h1 class="font-work font-bold text-xl md:text-2xl lg:text-3xl text-white w-fit text-center md:text-start">Ikuti Lomba Finder 6 Dan Menangkan Hadiah</h1>
          <p class="text-base lg:text-lg text-white w-full lg:w-5/6 font-work font-light text-center md:text-start">
            Perlombaan di bidang DKV yang menargetkan siswa sma, smk, mahasiswa, serta masyarakat umum. Pada lomba kali ini, finder akan mengadakan lomba-lomba yang juga bertujuan sebagai media publikasi Finder 6 serta pelestarian budaya
            indonesia ke masyarakat besar.
          </p>
          <a href="detaillomba.html" target="_blank"><button class="text-base lg:text-xl text-white px-6 py-4 bg-[#BA1F36] w-fit rounded-lg font-work hover:bg-[#ba1f1f] duration-150">Pelajari Lebih Lengkap</button></a>
        </div> -->
  <!-- Collumn 2 -->
  <!-- <div style="background-image: url(img/testlomba.png)" class="bg-cover w-[90%] lg:w-[60%] bg-slate-300 h-[250px] md:h-[350px] rounded-xl mt-4 md:mt-0"></div>
      </div> -->
  <!-- Info Lomba -->
  <!-- </section> -->
  <!-- Lomba Finder -->

  <!-- Guest Star -->
  <section id="gueststar" class="container flex flex-col max-w-full items-center bg-[#0D0D0D] gap-6 py-14">
    <div class="flex flex-col items-center gap-2">
      <h1 style="font-family: 'Work Sans'" class="text-2xl md:text-3xl text-white font-semibold">Temui Kolaborator Kami
      </h1>
      <h2 style="font-family: 'Work Sans'" class="text-white text-center text-sm md:text-lg">Temui beberapa Tokoh
        Terkenal</h2>
    </div>
    <!-- Grid -->
    <div class="grid container grid-cols-2 md:grid-cols-2 md:scale-100 lg:grid-cols-3 justify-items-center gap-2">

      <?php
      $koneksi = mysqli_connect($host, $username, $password, $database);

      // Periksa koneksi
      if (mysqli_connect_errno()) {
        die("Koneksi database gagal: " . mysqli_connect_error());
      }

      // Query untuk mengambil data speakers
      $query = "SELECT id_speaker, nama_speaker, instansi, deskripsi, kontak, foto_speaker, created_at, urutan FROM speakers ORDER BY urutan ASC";
      $result = mysqli_query($koneksi, $query);

      // Periksa apakah ada data
      if (mysqli_num_rows($result) > 0) {
        // Loop melalui hasil query dan tampilkan data
        while ($row = mysqli_fetch_assoc($result)) {
          $id_speaker = intval($row['id_speaker']);
          $nama = htmlspecialchars($row['nama_speaker']);
          $instansi = htmlspecialchars($row['instansi']);
          $foto = htmlspecialchars($row['foto_speaker']); // Nama file foto
      
          // Tentukan path foto default jika foto tidak ada
          $fotoPath = !empty($foto) ? 'img/speakers/' . $foto : 'img/narsum/segerahadir.png';

          echo '<div class="w-[340px] flex flex-col items-center scale-[54%] -m-12 md:scale-100 md:m-0">';
          echo '<a href="detailspeakers.php?id_speaker=' . $id_speaker . '">'; // Tambahkan tautan ke detail speaker
          echo '<img class="w-[280px]" src="' . $fotoPath . '" alt="' . $nama . '" />';
          echo '<h1 style="font-family: \'Work Sans\'" class="text-2xl text-white font-semibold">' . $nama . '</h1>';
          echo '</a>'; // Tutup tag <a>
          echo '<h2 style="font-family: \'Work Sans\'" class="font-light text-lg text-white">' . $instansi . '</h2>';
          echo '</div>';
        }
      } else {
        // Jika tidak ada data
        echo '<div class="w-[340px] flex flex-col items-center scale-[54%] -m-12 md:scale-100 md:m-0">';
        echo '<p style="font-family: \'Work Sans\'" class="text-white text-lg">Belum ada data speaker tersedia.</p>';
        echo '</div>';
      }

      // Tutup koneksi
      mysqli_close($koneksi);
      ?>

    </div>
  </section>



  <!-- FAQ -->
  <section class="flex flex-col container max-w-full bg-[#0D0D0D] px-6 md:px-16 py-10">
    <h1 class="text-white px-6 md:px-16 text-2xl md:text-3xl font-semibold md:mx-auto font-work">FAQ (Frequently Ask
      Question)</h1>

    <!-- Pertanyaan  -->
    <ul class="w-full mx-auto mt-2 divide-y py-4 border-b-[1px] border-white">

      <?php
      // Koneksi ke database
      $koneksi = mysqli_connect($host, $username, $password, $database);

      // Periksa koneksi
      if (mysqli_connect_errno()) {
        die("Koneksi database gagal: " . mysqli_connect_error());
      }

      // Query untuk mengambil data QnA
      $query = "SELECT topik, jawaban FROM qna WHERE status = 'active' ORDER BY created_at DESC";
      $result = mysqli_query($koneksi, $query);

      // Periksa apakah ada data
      if (mysqli_num_rows($result) > 0) {
        // Loop melalui hasil query dan tampilkan data
        while ($row = mysqli_fetch_assoc($result)) {
          $topik = htmlspecialchars($row['topik']);
          $jawaban = htmlspecialchars($row['jawaban']);

          echo '<li>';
          echo '<details class="group">';
          echo '<summary class="flex items-center gap-3 px-4 py-3 font-medium marker:content-none hover:cursor-pointer">';
          echo '<svg class="w-5 h-5 text-white transition group-open:rotate-90" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">';
          echo '<path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"></path>';
          echo '</svg>';
          echo '<span style="font-family: \'Work Sans\'" class="text-white text-lg md:text-xl font-normal">' . $topik . '</span>';
          echo '</summary>';
          echo '<!-- Jawaban -->';
          echo '<article class="px-4 pb-4">';
          echo '<p style="font-family: \'Work Sans\'" class="text-white text-base md:text-lg font-light">' . $jawaban . '</p>';
          echo '</article>';
          echo '</details>';
          echo '</li>';
        }
      } else {
        // Jika tidak ada data
        echo '<li>';
        echo '<p style="font-family: \'Work Sans\'" class="text-white text-base md:text-lg font-light px-4 py-4">Belum ada FAQ tersedia.</p>';
        echo '</li>';
      }

      // Tutup koneksi
      mysqli_close($koneksi);
      ?>

    </ul>
  </section>

  <!-- Sponsor -->
  <!-- <div class="flex-col items-center justify-center bg-[#0d0d0d]  overflow-hidden py-12  ">
    <div class="text-white text-2xl md:text-3xl font-semibold font-work w-full text-center">Our Sponsor</div>
    <div class="scroller mx-auto" data-direction="left" data-speed="slow">
      <div class="scroller__inner ">
        <img src="img/(1) Finder 6 Logo Vertical Full White.png" alt="" class="h-[150px]" />
        <img src="img/(1) Finder 6 Logo Vertical Full White.png" alt="" class="h-[150px]" />
        <img src="img/(1) Finder 6 Logo Vertical Full White.png" alt="" class="h-[150px]" />
        <img src="img/(1) Finder 6 Logo Vertical Full White.png" alt="" class="h-[150px]" />
        <img src="img/(1) Finder 6 Logo Vertical Full White.png" alt="" class="h-[150px]" />
        <img src="img/(1) Finder 6 Logo Vertical Full White.png" alt="" class="h-[150px]" />
      </div>
    </div>
  </div> -->

  <!-- Presented -->
  <div class="flex-col items-center justify-center bg-[#0d0d0d]  overflow-hidden py-6  ">
    <div class="text-white text-xl md:text-2xl font-semibold font-work w-full text-center">Presented</div>
    <div class="scroller mx-auto " data-direction="left" data-speed="slow">
      <div class="scroller__inner  ">
        <img src="img/logo/Hikavi Logo Main 1 Orange White.png" alt="" class="h-[75px]" />
        <img src="img/logo/Japan Logo white-16.png" alt="" class="h-[75px]" />
        <img src="img/logo/logo dkv white-01.png" alt="" class="h-[75px]" />
        <img src="img/logo/UPI white-05.png" alt="" class="h-[75px]" />

      </div>
    </div>
  </div>
  <!-- Supported By -->
  <div class="flex-col items-center justify-center bg-[#0d0d0d]  overflow-hidden py-6  ">
    <div class="text-white text-xl md:text-2xl font-semibold font-work w-full text-center">Supported By</div>
    <div class="scroller mx-auto " data-direction="left" data-speed="slow">
      <div class="scroller__inner  ">
        <img src="img/logo_support/FPSD.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/ADGI BDG.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/Gekrafs.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/EDURank.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/BDF.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/IKA White.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/Mizan.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/Cronos white-02.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/Sangnila white.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/Lentera.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/Kema white-01.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/Accon Logo white.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/aidia.png" alt="" class="h-[100px]" />\
        <img src="img/logo_support/AINAKI White-01.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/IMADjI DKV_Logo-02.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/IMG-20241005-WA0011.jpg" alt="" class="h-[100px]" />
        <img src="img/logo_support/IMG_4993.PNG" alt="" class="h-[100px]" />
        <img src="img/logo_support/Kaliatibe White.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/Logo #EB1.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/logo 2.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/logo bdg connex.jpg" alt="" class="h-[100px]" />
        <img src="img/logo_support/LOGO BFC OFFICIAL.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/logo hiphip.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/logo png.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/OZ BANDUNG_1OZ BANDUNG_3 white.jpg" alt="" class="h-[100px]" />
        <img src="img/logo_support/Paduan Cahaya.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/TekGraf.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/warbe.png" alt="" class="h-[100px]" />
        <img src="img/logo_support/WhatsApp Image 2024-10-15 at 11.50.11.jpeg" alt="" class="h-[100px]" />
      </div>
    </div>
  </div>
  <!-- Map Finder -->
  <div class="flex flex-col max-w-full items-center text-center gap-6 text-white bg-[#0d0d0d] pt-12 pb-20">
    <div class="text-white text-2xl md:text-3xl font-semibold font-work">Finder 6 Map</div>
    <div class="w-5/6 max-w-[1200px] overflow-hidden rounded-xl">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.2348973267035!2d107.59106691057444!3d-6.862428093107444!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6b943c2c5ff%3A0xee36226510a79e76!2sUniversitas%20Pendidikan%20Indonesia!5e0!3m2!1sid!2sid!4v1710655960109!5m2!1sid!2sid"
        width="1366" height="400" style="border: 0" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <a href="https://maps.app.goo.gl/w12NySVz2bjC6X527" target="_blank"><button
        class="border-[1px] font-work hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg">Check
        On Google Map</button></a>
  </div>
  <?php
  require '_footer.php';
  ?>
  <!-- J56w5l -->
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
</body>
<script>
  // Script untuk mengatur kelas aktif pada tombol filter
  document.addEventListener('DOMContentLoaded', function () {
    // Ambil semua elemen tombol filter
    const filterButtons = document.querySelectorAll('.filter-button');

    // Tambah event listener untuk setiap tombol filter
    filterButtons.forEach(button => {
      button.addEventListener('click', function () {
        // Hapus kelas 'active' dari semua tombol filter
        filterButtons.forEach(btn => btn.classList.remove('active'));

        // Tambah kelas 'active' pada tombol yang diklik
        this.classList.add('active');
      });
    });
  });
</script>
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
<!-- Scroll Animatioin -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="system.js"></script>
<!-- ------- -->

</html>