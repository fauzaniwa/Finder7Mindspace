<?php
session_start();
// Aktifkan pelaporan kesalahan
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Koneksi ke database
include 'admin-one/dist/koneksi.php';

// Ambil user_id dari session jika ada
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Ambil id_event yang ingin ditampilkan, misalnya dari parameter URL
$id_event_target = isset($_GET['id_event']) ? intval($_GET['id_event']) : 0;

// Query untuk mendapatkan data dari tabel event dengan show_event = 1
$query_event = "SELECT id_event, judul_event, speakers_event, jadwal_event, waktu_event, lokasi_event, tiket_event, event_status, kuota FROM event WHERE show_event = 1";

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
    // Ambil ID event untuk menghitung peserta
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

    // Tutup statement count users
    mysqli_stmt_close($stmt_count_users);
}

// Tutup statement event
mysqli_stmt_close($stmt_event);

// Sekarang $events_data berisi data event lengkap dengan sisa kuota


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


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
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
    </style>
    <!-- Title Web & Icon -->
    <title>Detail Seminar</title>
    <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />
    <!-- Script Navbar Menu -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <!-- Script Cursor -->
    <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />
    <!-- Script Cursor -->
</head>

<body class="bg-[#000000]">
    <?php
    require '_navbar.php';
    ?>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Koneksi ke database
    
    // Ambil id_event dari URL
    $id_event = isset($_GET['id_event']) ? intval($_GET['id_event']) : 0;

    // Query untuk mendapatkan detail event
    $query_event = "SELECT * FROM event WHERE id_event = ?";
    $stmt_event = mysqli_prepare($koneksi, $query_event);
    mysqli_stmt_bind_param($stmt_event, "i", $id_event);
    mysqli_stmt_execute($stmt_event);
    $result_event = mysqli_stmt_get_result($stmt_event);
    $row_event = mysqli_fetch_assoc($result_event);

    // Jika tidak ada event dengan id tersebut
    if (!$row_event) {
        die("Event tidak ditemukan.");
    }

    // Ambil informasi pengguna (jika ada)
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
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
            } else {
                echo '<script>alert("Anda sudah memiliki tiket untuk event ini.");</script>';
            }
        }
    }
    // Menampilkan detail event
    ?>
    <section id="" class="w-full h-full pt-32 bg-[#0D0D0D] pb-32">
        <div
            class="flex flex-col lg:flex-row lg:justify-between w-[90%] mx-auto bg-[#131313] py-4 gap-6 lg:items-center px-4 rounded-xl">
            <div class="flex flex-col gap-4 lg:gap-2 w-full order-last lg:order-first">
                <div class="flex flex-wrap lg:flex-row gap-2 md:gap-4">
                    <h1 style="font-family: 'Work Sans'" class="text-white text-lg md:text-xl font-normal">
                        <?php echo htmlspecialchars($row_event['waktu_event']); ?>
                    </h1>
                    <li style="font-family: 'Work Sans'" class="text-white text-lg md:text-xl font-normal">

                        <?php // Menampilkan sisa kuota untuk id_event tertentu
                        if (array_key_exists($id_event_target, $events_data)) {
                            $sisa_kuota = $events_data[$id_event_target]['sisa_kuota'];
                            echo htmlspecialchars($sisa_kuota) . " Tiket Tersedia";
                        } else {
                            echo "Event tidak ditemukan.";
                        }
                        ?>


                    </li>
                    <li style="font-family: 'Work Sans'" class="text-white text-lg md:text-xl font-normal">
                        <?php echo htmlspecialchars($row_event['lokasi_event']); ?>
                    </li>
                </div>
                <div class="flex flex-col gap-4">
                    <h1 style="font-family: 'Work Sans'" class="text-white text-2xl lg:text-3xl font-medium">
                        <?php echo htmlspecialchars($row_event['judul_event']); ?>
                    </h1>
                    <h1 style="font-family: 'Work Sans'" class="text-white md:text-xl font-light">
                        <?php echo htmlspecialchars($row_event['speakers_event']); ?>
                    </h1>
                </div>
            </div>
            <img src="img/event/<?php echo htmlspecialchars($row_event['thumbnail_event']); ?>"
                class="order-first lg:order-last shrink-0 bg-cover h-full bg-white max-h-[350px] max-w-[350px]">
        </div>

        <section class="flex gap-5 justify-between w-[90%] lg-w-[60%] mx-auto my-4">
            <h2 class="text-white text-2xl md:text-3xl font-semibold font-work text-center">Deskripsi Acara</h2>
            <div class="flex gap-3 px-5"></div>
        </section>
        <p class="w-[90%] mx-auto text-white text-lg md:text-xl font-light font-work text-justify mb-4">
            <span class="font-reguler"><?php echo htmlspecialchars($row_event['deskripsi_event']); ?></span>
        </p>

        <section class="section main-section">
            <!-- Tombol -->
            <div
                class="flex w-full items-center bg-[#131313] fixed bottom-0 drop-shadow-lg border-t-[1px] border-[#202020]">
                <div class="w-full flex justify-center">
                    <?php if (!$user_id || !in_array($row_event['id_event'], $events_with_tickets)): ?>
                        <?php if ($row_event['event_status'] == 1): // Pengecekan status event ?>
                            <?php
                            // Mengambil sisa kuota untuk id_event tertentu
                            if (array_key_exists($id_event_target, $events_data)) {
                                $sisa_kuota = $events_data[$id_event_target]['sisa_kuota'];
                            } else {
                                $sisa_kuota = 0; // Atau tentukan nilai default jika event tidak ditemukan
                            }
                            ?>
                            <?php if ($sisa_kuota > 0): // Cek sisa kuota ?>
                                <form method="post" action=""> <!-- Ganti dengan aksi yang sesuai -->
                                    <input type="hidden" name="id_event"
                                        value="<?php echo htmlspecialchars($row_event['id_event']); ?>">
                                    <button type="submit" style="font-family: 'Work Sans'"
                                        class="w-[350px] max-w-[600px] flex justify-center items-center h-fit border-[1px] font-work hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full text-lg md:text-2xl my-4">
                                        Dapatkan Tiket
                                    </button>
                                </form>
                            <?php else: // Jika sisa kuota = 0 ?>
                                <button style="font-family: 'Work Sans'"
                                    class="w-[350px] max-w-[600px] flex justify-center items-center h-fit border-[1px] font-work hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full text-lg md:text-2xl my-4"
                                    disabled>
                                    Tiket telah habis
                                </button>
                            <?php endif; ?>
                        <?php elseif ($row_event['event_status'] == 2): // Status event belum dimulai ?>
                            <button style="font-family: 'Work Sans'"
                                class="w-[350px] max-w-[600px] flex justify-center items-center h-fit border-[1px] font-work hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full text-lg md:text-2xl my-4"
                                disabled>
                                Pendaftaran Belum Dibuka
                            </button>
                        <?php else: // Status event sudah berakhir ?>
                            <button style="font-family: 'Work Sans'"
                                class="w-[350px] max-w-[600px] flex justify-center items-center h-fit border-[1px] font-work hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full text-lg md:text-2xl my-4"
                                disabled>
                                Event Sudah Berakhir
                            </button>
                        <?php endif; ?>
                    <?php else: // Jika user sudah memiliki tiket ?>
                        <div>
                            <p style="font-family: 'Work Sans'"
                                class="w-[350px] max-w-[600px] flex justify-center items-center h-fit border-[1px] font-work hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full text-lg md:text-2xl my-4">
                                Kamu sudah memiliki tiket.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>



        </section>


        <?php
        // Tutup statement dan koneksi
        mysqli_stmt_close($stmt_event);
        mysqli_close($koneksi);
        ?>
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
    <!-- ------------ -->
    <!-- ----------- -->
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