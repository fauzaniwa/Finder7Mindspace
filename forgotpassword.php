<?php
// Mulai session untuk penyimpanan sementara
session_start();

// Memasukkan file koneksi
include 'admin-one/dist/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    // Ambil email dari form dan bersihkan input
    $email = bersihkanInput($_POST['email']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Periksa apakah email ada dalam database pengguna (misalnya, di tabel users)
        $check_email_query = "SELECT * FROM user WHERE email = ?";
        $stmt = $koneksi->prepare($check_email_query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Jika email valid, generate OTP
            $otp_code = mt_rand(100000, 999999); // Menghasilkan 6 digit kode OTP
            $expires_at = date("Y-m-d H:i:s", strtotime('+3 minutes')); // Atur waktu kedaluwarsa OTP (15 menit)

            // Simpan OTP ke tabel resetpassword
            $insert_otp_query = "INSERT INTO resetpassword (email, otp_code, expires_at) VALUES (?, ?, ?)";
            $stmt = $koneksi->prepare($insert_otp_query);
            $stmt->bind_param('sss', $email, $otp_code, $expires_at);

            if ($stmt->execute()) {
                // Jika berhasil disimpan, kirim email ke pengguna
                $subject = "Permintaan Reset Password";
                $message = "Halo,\n\n" .
                    "Kami telah menerima permintaan untuk mereset kata sandi akun Anda.\n\n" .
                    "Berikut adalah kode OTP yang perlu Anda masukkan untuk melanjutkan proses reset password:\n\n" .
                    "Kode OTP Anda: $otp_code\n\n" .
                    "Kode ini akan kedaluwarsa dalam 3 menit.\n\n" .
                    "Jika Anda tidak melakukan permintaan ini, harap abaikan email ini.\n\n" .
                    "Terima kasih,\n" .
                    "Tim Finder DKVI UPI";
                $headers = "From: forgot@finderdkvupi.com\r\n" .
                    "Reply-To: forgot@finderdkvupi.com\r\n" .
                    "Content-Type: text/plain; charset=UTF-8\r\n";

                if (mail($email, $subject, $message, $headers)) {
                    // Simpan email ke session agar dapat digunakan di otp.php
                    $_SESSION['email'] = $email;

                    // Redirect ke halaman otp.php
                    header("Location: verifikasi.php");
                    exit();
                } else {
                    // Jika gagal mengirim email
                    $status_message = "Gagal mengirim email. Silakan coba lagi.";
                }
            } else {
                // Jika gagal menyimpan ke database
                $status_message = "Gagal memproses permintaan reset password.";
            }
        } else {
            // Jika email tidak ditemukan
            $status_message = "Email tidak ditemukan.";
        }
    } else {
        // Jika email tidak valid
        $status_message = "Email tidak valid.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<html lang="en" class="scroll-smooth">

</html>

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
        * {
        /* border: 1px solid red; */
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
    </style>
    <!-- Title Web & Icon -->
    <title>Reset Password</title>
    <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />
    <!-- Script Navbar Menu -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <!-- Script Cursor -->
    <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />
    <!-- Script Cursor -->
</head>

<body>
    <section id="reset" style="background-image: url(./img/bgregister.png)"
        class="bg-fil bg-cover w-full h-screen flex items-center justify-center">

        <form action="" method="POST">
            <div class="flex flex-col items-center w-fit px-6 py-20 bg-white bg-opacity-10 rounded-xl gap-4">
                <h1 class="text-2xl md:text-3xl text-white font-semibold">Reset Password</h1>
                <hr class="w-full">
                <p class="text-white text-center flex-col w-[350px]">
                    Setelah mengirimkan permintaan reset, Anda akan menerima email verifikasi.
                    Silakan periksa email Anda untuk mendapatkan 6 kode OTP yang diperlukan untuk melanjutkan.
                </p>
                <?php if (isset($status_message)) { ?>
                    <p class="text-white text-center flex-col w-[350px]">
                        <span><a href="register.php" class="font-bold text-[#BA1F36]"><?= $status_message; ?></a></span>
                    </p>
                <?php } ?>
                <div class="flex-col gap-2 w-[350px]">
                    <h1 class="text-lg md:text-xl text-white font-normal font-work">Email</h1>
                    <input type="email" name="email" class="w-[350px] h-10 rounded-lg px-2 font-work font-medium"
                        placeholder="example@mail.com" required>
                </div>

                <button type="submit" name="reset"
                    class="text-base w-full lg:text-xl text-white px-6 py-4 bg-[#BA1F36] rounded-lg font-work hover:bg-[#ba1f1f] duration-150 hover:drop-shadow-md">
                    Kirim Permintaan Reset
                </button>

            </div>
        </form>
    </section>

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