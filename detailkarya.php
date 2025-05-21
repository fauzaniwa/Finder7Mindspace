<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Sambungkan ke database
require_once "admin-one/dist/koneksi.php"; // Sesuaikan dengan lokasi file koneksi

// Fungsi untuk menghitung selisih waktu dan formatnya
function format_comment_time($commented_at)
{
  $comment_time = strtotime($commented_at);
  $current_time = time();
  $diff = $current_time - $comment_time;

  if ($diff < 60) {
    return "baru saja";
  } elseif ($diff < 3600) {
    $minutes = floor($diff / 60);
    return "$minutes menit yang lalu";
  } elseif ($diff < 86400) {
    $hours = floor($diff / 3600);
    return "$hours jam yang lalu";
  } else {
    return date("d M Y", $comment_time);
  }
}

// Ambil ID karya dari URL jika tersedia
$id_karya = isset($_GET['id']) ? intval($_GET['id']) : 0;
$judul_karya = isset($_GET['karya']) ? trim($_GET['karya']) : '';

if ($id_karya > 0) {
  // Ambil komentar berdasarkan ID karya
  $query_comments = "SELECT k.komentar, k.commented_at, u.nama AS username FROM komentar k JOIN user u ON k.user_id = u.id_user WHERE k.id_karya = ? ORDER BY k.commented_at DESC";
  $stmt_comments = mysqli_prepare($koneksi, $query_comments);
  if ($stmt_comments === false) {
    die("Error preparing the statement: " . mysqli_error($koneksi));
  }
  mysqli_stmt_bind_param($stmt_comments, "i", $id_karya);
} elseif (!empty($judul_karya)) {
  // Ambil ID karya berdasarkan judul karya
  $query_id_karya = "SELECT id_karya FROM karya WHERE judul_karya = ?";
  $stmt_id_karya = mysqli_prepare($koneksi, $query_id_karya);
  if ($stmt_id_karya === false) {
    die("Error preparing the statement: " . mysqli_error($koneksi));
  }
  mysqli_stmt_bind_param($stmt_id_karya, "s", $judul_karya);
  mysqli_stmt_execute($stmt_id_karya);
  $result_id_karya = mysqli_stmt_get_result($stmt_id_karya);

  if ($result_id_karya && mysqli_num_rows($result_id_karya) > 0) {
    $row_id_karya = mysqli_fetch_assoc($result_id_karya);
    $id_karya = $row_id_karya['id_karya'];

    // Ambil komentar berdasarkan ID karya
    $query_comments = "SELECT k.komentar, k.commented_at, u.nama AS username FROM komentar k JOIN user u ON k.user_id = u.id_user WHERE k.id_karya = ? ORDER BY k.commented_at DESC";
    $stmt_comments = mysqli_prepare($koneksi, $query_comments);
    if ($stmt_comments === false) {
      die("Error preparing the statement: " . mysqli_error($koneksi));
    }
    mysqli_stmt_bind_param($stmt_comments, "i", $id_karya);
  } else {
    die("Karya not found.");
  }
} else {
  // Handle error jika tidak ada ID atau judul_karya yang valid
  die("Invalid request. Missing ID or karya parameter.");
}

mysqli_stmt_execute($stmt_comments);
$result_comments = mysqli_stmt_get_result($stmt_comments);

// Ambil jumlah komentar untuk judul karya
$query_karya_info = "SELECT comments FROM karya WHERE id_karya = ?";
$stmt_karya_info = mysqli_prepare($koneksi, $query_karya_info);
if ($stmt_karya_info === false) {
  die("Error preparing the statement: " . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt_karya_info, "i", $id_karya);
mysqli_stmt_execute($stmt_karya_info);
$result_karya_info = mysqli_stmt_get_result($stmt_karya_info);

if ($result_karya_info && mysqli_num_rows($result_karya_info) > 0) {
  $row_karya_info = mysqli_fetch_assoc($result_karya_info);
  $comments_count = $row_karya_info['comments'];
} else {
  $comments_count = 0; // Default jika tidak ada hasil
}

// Query untuk mendapatkan informasi karya
$query_karya = "SELECT * FROM karya WHERE id_karya = ?";
$stmt_karya = mysqli_prepare($koneksi, $query_karya);
if ($stmt_karya === false) {
  die("Error preparing the statement: " . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt_karya, "i", $id_karya);
mysqli_stmt_execute($stmt_karya);
$result_karya = mysqli_stmt_get_result($stmt_karya);

if ($result_karya && mysqli_num_rows($result_karya) > 0) {
  $row_karya = mysqli_fetch_assoc($result_karya);
  $judul_karya = $row_karya['judul_karya'];
  $nama_karya = $row_karya['nama_karya'];
  $nim = $row_karya['NIM'];
  $deskripsi = $row_karya['deskripsi'];
  $instagram = $row_karya['instagram'];
  $pict_karya = $row_karya['pict_karya'];
  $likes = $row_karya['likes'];
  $comments = $row_karya['comments'];
  $optional_karya = $row_karya['optional_karya'];
  $id_jenis = $row_karya['id_jenis'];

  // Ambil informasi jenis karya berdasarkan id_jenis
  $query_jenis_karya = "SELECT jenis FROM jenis_karya WHERE id_jenis = ?";
  $stmt_jenis_karya = mysqli_prepare($koneksi, $query_jenis_karya);
  if ($stmt_jenis_karya === false) {
    die("Error preparing the statement: " . mysqli_error($koneksi));
  }
  mysqli_stmt_bind_param($stmt_jenis_karya, "i", $id_jenis);
  mysqli_stmt_execute($stmt_jenis_karya);
  $result_jenis_karya = mysqli_stmt_get_result($stmt_jenis_karya);
  $row_jenis_karya = mysqli_fetch_assoc($result_jenis_karya);
  $kategori = $row_jenis_karya['jenis'];

  // Ambil dua angka pertama dari NIM dan tambahkan "20" di depan
  $nim_prefix = substr($nim, 0, 2);
  $angkatan = "20" . $nim_prefix;
} else {
  die("Karya tidak ditemukan.");
}

// Query untuk mendapatkan informasi likes
$query_likes = "SELECT u.nama AS username, l.liked_at FROM likes l JOIN user u ON l.user_id = u.id_user WHERE l.id_karya = ? ORDER BY l.liked_at DESC";
$stmt_likes = mysqli_prepare($koneksi, $query_likes);
if ($stmt_likes === false) {
  die("Error preparing the statement: " . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt_likes, "i", $id_karya);
mysqli_stmt_execute($stmt_likes);
$result_likes = mysqli_stmt_get_result($stmt_likes);

// Query untuk mengecek apakah user sudah memberikan like sebelumnya
$query_check_like = "SELECT id_like FROM likes WHERE id_karya = ? AND user_id = ?";
$stmt_check_like = mysqli_prepare($koneksi, $query_check_like);
if ($stmt_check_like === false) {
  die("Error preparing the statement: " . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt_check_like, "ii", $id_karya, $_SESSION['user_id']); // Memastikan $_SESSION['user_id'] adalah user_id
mysqli_stmt_execute($stmt_check_like);
mysqli_stmt_store_result($stmt_check_like);
$already_liked = mysqli_stmt_num_rows($stmt_check_like) > 0;

// Tentukan kelas CSS untuk tombol like berdasarkan status like
$likeButtonClass = $already_liked ? 'text-red-500' : 'text-white';

$shareUrl = "https://finderdkvupi.com/detailkarya.php?karya=" . rawurlencode($judul_karya);

?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
  <style>
    .modal {
      display: none;
      z-index: 9999;
    }

    .modal.active {
      display: flex;
    }

    .icon-lg {
      font-size: 1.5rem;
    }

    .modallike {
      display: none;
      z-index: 9999;
    }

    .modallike.show {
      display: flex;
    }

    /* Gaya scrollbar */
    .scrollbar-thin::-webkit-scrollbar {
      width: 8px;
    }

    .scrollbar-thin::-webkit-scrollbar-track {
      background: transparent;
      /* Track background */
    }

    .scrollbar-thin::-webkit-scrollbar-thumb {
      background: #000;
      /* Scrollbar thumb color */
      border-radius: 10px;
      /* Optional: rounded corners */
    }

    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
      background: #555;
      /* Optional: scrollbar thumb color on hover */
    }
  </style>
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <title><?php echo htmlspecialchars($judul_karya); ?></title>
  <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />
  <!-- Script Navbar Menu -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <!-- Script Cursor -->
  <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />
  <!-- Script Cursor -->
</head>

<body>
  <?php
  require '_navbar.php';
  ?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Detail Karya</title>
    <style>
      .modal {
        display: none;
        z-index: 9999;
      }

      .modal.active {
        display: flex;
      }

      .icon-lg {
        font-size: 1.5rem;
      }
    </style>
  </head>

  <body>
    <section id="" style="background-image: url(./img/bghero.png)"
      class="flex flex-col items-center pt-32 py-10 max-w-full bg-cover">
      <a href="detailpameran.php" class="w-full flex items-start max-w-[1100px] px-4">
        <img src="./img/arrow-left 1.svg" alt="" />
      </a>
      <div
        class="flex flex-col justify-center items-center w-full max-w-[300px] h-auto max-h-[400px] bg-slate-400 rounded-xl overflow-hidden">
        <img id="karya-img"
          src="<?php echo !empty($pict_karya) ? 'img/karya/' . $pict_karya : 'img/karya/default.jpg'; ?>" alt="Karya"
          class="object-cover w-full max-w-full h-auto cursor-pointer">
      </div>
      <div class="flex justify-center mt-4 gap-4">
        <!-- Tombol Like -->
        <div class="flex items-center">
          
          <button id="likeButton" class="like-button <?php echo $likeButtonClass ?> hover:text-red-500 mr-2 icon-lg"
            onclick="likeArtwork(<?php echo $id_karya; ?>)">
            <i class="fas fa-heart"></i></button>
          <span class="text-white" id="like-count"><?php echo $likes; ?></span>
        </div>
        <!-- Tombol Komentar -->
        <div class="flex items-center">
          <a href="#komentar" class="comment-button text-white hover:text-green-500 mr-2 icon-lg">
            <i class="fas fa-comment"></i>
          </a>
          <span class="text-white"><?php echo $comments; ?></span>
        </div>
        <!-- Tombol Share -->
        <a href="#share" class="share-button text-white hover:text-blue-500 icon-lg">
          <i class="fas fa-share"></i>
        </a>
      </div>
    </section>
    <!-- Detail Karya -->
    <section id="" class="max-w-full bg-[#0D0D0D] flex flex-col gap-8 mt-[-10px] pb-12 items-center">
      <div class="flex flex-col items-center ">
        <h1 class="text-white text-2xl md:text-3xl font-semibold font-work w-full text-center mt-8">
          <?php echo $judul_karya; ?>
        </h1>
        <h2 class="text-white text-xl md:text-2xl font-normal font-work w-full text-center mt-2">
          <?php echo $nama_karya; ?>
        </h2>
        <p class="text-white">Angkatan <?php echo $angkatan; ?> </p>
      </div>
      <section class="text-white">
        <a href="<?php echo $optional_karya; ?>" target="_blank"
          class="justify-center px-6 py-2.5 text-sm md:text-base bg-neutral-800 rounded-[43px] font-work">Lihat Karya
        </a>
      </section>
      <!-- Deskripsi Karya -->
      <section class="flex flex-col text-white w-[90%] max-w-[1100px]">
        <header class="flex gap-5 justify-between w-full">
          <h2 class="my-auto text-xl md:text-2xl font-work">Deskripsi Karya</h2>
          <span
            class="justify-center px-6 py-2.5 text-sm md:text-base bg-neutral-800 rounded-[43px] font-work"><?php echo $kategori; ?></span>
        </header>
        <p class="mt-3.5 w-full text-base md:text-lg font-work">
          <hr>
          <br>
          <?php echo $deskripsi; ?>
        </p>
      </section>


      <!-- Instagram -->
      <div class="flex justify-center w-full mt-4">
        <a href="https://instagram.com/<?php echo $instagram; ?>" target="_blank"
          class="justify-center flex border-[1px] p-3 w-[max-content] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg">
          <img class="h-[32px]" src="./img/ig.svg" alt="Instagram">
          <span> Instagram</span>
        </a>
      </div>


      <!-- Tombol Share -->
      <section id="share"
        class="pt-28 icon-lg flex flex-col gap-4 justify-center mt-4 gap-4 text-white w-[90%] max-w-[1100px] mt-8">
        <h2 class="text-xl md:text-2xl font-work mb-4">Share Karya</h2>
        <div class="share-buttons">
          <button class="share-button text-white hover:text-blue-500"
            onclick="copyToClipboard('<?php echo $shareUrl; ?>')">
            <i class="fas fa-copy"></i>
          </button>
          <button class="share-button text-white hover:text-green-500"
            onclick="shareToWhatsApp('<?php echo $shareUrl; ?>')">
            <i class="fab fa-whatsapp"></i>
          </button>
          <button class="share-button text-white hover:text-blue-400"
            onclick="shareToTwitter('<?php echo $shareUrl; ?>')">
            <i class="fab fa-twitter"></i>
          </button>
          <button class="share-button text-white hover:text-red-600"
            onclick="shareToInstagram('<?php echo $shareUrl; ?>')">
            <i class="fab fa-instagram"></i>
          </button>
          <button class="share-button text-white hover:text-blue-600"
            onclick="shareToTelegram('<?php echo $shareUrl; ?>')">
            <i class="fab fa-telegram"></i>
          </button>
        </div>
      </section>

      <!-- Tombol Like -->
      <section id="like" class="icon-lg flex flex-col gap-4 justify-center mt-4 text-white w-[90%] max-w-[1100px] mt-8">
        <h2 class="text-xl md:text-2xl font-work mb-4">Disukai Oleh</h2>
        <div class="flex items-center gap-4">
          <button class="share-button text-white hover:text-blue-500">
            <i class="fas fa-user"></i>
          </button>
          <p class="text-lg cursor-pointer" onclick="openModal()"><?php echo $likes; ?> orang
            lainnya</p>
        </div>
      </section>


      <!-- Modal Pop-up -->
      <div id="modallike"
        class="modallike hidden fixed inset-0 m-3 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full h-[350px] flex flex-col relative">
          <button onclick="closeModal()"
            class="text-gray-500 text-2xl absolute top-2 right-2 hover:text-gray-700 transition">&times;</button>
          <h2 class="text-xl font-semibold mb-4 sticky top-0 bg-white p-2">Disukai Oleh (<?php echo $likes; ?>)</h2>
          <div class="flex flex-col gap-4 overflow-y-auto scrollbar-thin scrollbar-thumb-black">
            <?php if (mysqli_num_rows($result_likes) > 0): ?>
              <?php while ($row_likes = mysqli_fetch_assoc($result_likes)): ?>
                <!-- item pengguna -->
                <div class="flex items-center gap-4 p-2 border-b border-gray-300">
                  <img class="w-[40px] h-[40px] rounded-full" src="img/profill.png" alt="User Icon">
                  <div>
                    <p class="font-semibold"><?php echo htmlspecialchars($row_likes['username']); ?></p>
                    <p class="text-gray-500 text-sm"><?php echo format_comment_time($row_likes['liked_at']); ?></p>
                  </div>
                </div>
              <?php endwhile; ?>
            <?php else: ?>
              <!-- Jika tidak ada data like -->
              <div class="flex justify-center items-center gap-4">
                <p class="text-center text-lg">Belum ada Like</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>


      <!-- Script untuk membuka dan menutup modal -->
      <script>
        function openModal() {
          document.getElementById('modallike').classList.remove('hidden');
        }

        function closeModal() {
          document.getElementById('modallike').classList.add('hidden');
        }
      </script>


      <!-- Section Komentar -->
      <section id="komentar1" class="text-white w-[90%] max-w-[1100px] mt-8">
        <h2 class="text-xl md:text-2xl font-work mb-4">Komentar (<?php echo $comments; ?>)</h2>
        <div class="flex flex-col gap-4">
          <?php
          if (mysqli_num_rows($result_comments) > 0) {
            while ($row_comment = mysqli_fetch_assoc($result_comments)) {
              $comment = htmlspecialchars($row_comment['komentar']);
              $username = htmlspecialchars($row_comment['username']);
              $comment_time = format_comment_time($row_comment['commented_at']);
              ?>
              <div class="flex items-start gap-4">
                <img class="w-[60px] h-[60px] rounded-full" src="img/profill.png" alt="Profile Picture">
                <div class="flex-1 bg-[#1A1A1A] rounded-lg p-4">
                  <h3 class="font-semibold"><?php echo $username; ?></h3>
                  <span class="text-xs"><?php echo $comment_time; ?></span>
                  <p class="text-sm md:text-base"><?php echo $comment; ?></p>
                </div>
              </div>
              <?php
            }
          } else {
            ?>
            <div class="flex justify-center items-center gap-4">
              <p class="text-center text-lg">Belum ada komentar</p>
            </div>
            <?php
          }
          ?>
        </div>
      </section>


      <!-- Form Komentar -->
      <section id="komentar" class="text-white w-[90%] max-w-[1100px] mt-8">
        <h2 class="text-xl md:text-2xl font-work mb-4">
          <?php if (isset($_SESSION['user_data'])): ?>
            Tambahkan komentar sebagai <span
              class="italic"><?php echo htmlspecialchars($_SESSION['user_data']['nama']); ?></span>
          <?php else: ?>
            Tambahkan Komentar
          <?php endif; ?>
        </h2>
        <form action="submit_comment.php" method="POST" class="flex flex-col gap-4">
          <input type="hidden" name="id_karya" value="<?php echo $id_karya; ?>">
          <textarea name="komentar" id="komentar" rows="5" class="rounded-lg bg-[#1A1A1A] p-4"
            placeholder="Ketik komentar Anda disini..." required></textarea>
          <button type="submit"
            class="border-[1px] p-3 w-[max-content] hover:bg-white hover:bg-opacity-25 py-2 px-6 border-white text-white rounded-full md:text-lg">Kirim
            Komentar</button>
        </form>
      </section>
    </section>


    </section>

    <!-- Modal -->
    <div id="modal" class="modal fixed inset-0 bg-black bg-opacity-75 justify-center items-center">
      <div class="relative">
        <img id="modal-img" src="" alt="Full Image" class="max-w-full max-h-screen object-contain">
        <button id="close-modal" class="absolute top-2 right-2 text-white text-2xl">&times;</button>
      </div>
    </div>
    <script>
      // Fungsi-fungsi share di luar fungsi shareKarya
      function copyToClipboard(text) {
        navigator.clipboard.writeText(text)
          .then(() => {
            alert('URL telah disalin ke clipboard: ' + text);
          })
          .catch(err => {
            console.error('Gagal menyalin URL: ', err);
          });
      }

      function shareToWhatsApp(url) {
        window.open('https://api.whatsapp.com/send?text=' + encodeURIComponent(url), '_blank');
      }

      function shareToTwitter(url) {
        window.open('https://twitter.com/intent/tweet?url=' + encodeURIComponent(url), '_blank');
      }

      function shareToInstagram(url) {
        // Tidak dapat langsung berbagi via web, hanya membuka Instagram dengan URL
        window.open('https://www.instagram.com/', '_blank');
      }

      function shareToTelegram(url) {
        window.open('tg://msg?url=' + encodeURIComponent(url), '_blank');
      }
    </script>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const img = document.getElementById('karya-img');
        const modal = document.getElementById('modal');
        const modalImg = document.getElementById('modal-img');
        const closeModal = document.getElementById('close-modal');

        img.addEventListener('click', () => {
          modal.classList.add('active');
          modalImg.src = img.src;
        });

        closeModal.addEventListener('click', () => {
          modal.classList.remove('active');
        });

        modal.addEventListener('click', (e) => {
          if (e.target === modal) {
            modal.classList.remove('active');
          }
        });
      });

      async function toggleLike(idKarya) {
        const likeButton = document.getElementById('likeButton');
        const likeCount = document.getElementById('like-count');

        // Check if the like button is currently liked
        const isLiked = likeButton.classList.contains('text-red-500');

        try {
          const response = await fetch('like_artwork_process.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ idKarya: idKarya }),
          });

          const data = await response.json();

          if (response.ok) {
            if (!isLiked) {
              likeButton.classList.add('text-red-500'); // Change button color to red
              likeButton.classList.remove('text-white'); // Remove white color
            } else {
              likeButton.classList.remove('text-red-500'); // Change button color to white
              likeButton.classList.add('text-white'); // Add white color
            }
            likeCount.textContent = data.likes; // Update like count from server response
          } else {
            console.error('Failed to like/unlike artwork:', data.message);
          }
        } catch (error) {
          console.error('Error performing like/unlike action:', error.message);
        }
      }


    </script>

  </body>

  </html>

  <!--  Footer -->
  <?php
  require '_footer.php';
  ?>

  <!-- Akhir Footer -->
  <!-- Modal -->
  <div id="modal" class="modal fixed gap-4 inset-0 bg-black bg-opacity-75 justify-center items-center">
    <div class="relative absolute">
      <img id="modal-img" src="" alt="Full Image" class="max-w-full max-h-screen object-contain">
      <button id="close-modal" class="absolute top-2 right-2 text-white text-2xl">&times;</button>
    </div>
  </div>
</body>

<!-- Cursor CDN -->
<script src="https://unpkg.com/kursor"></script>

<script>
  new kursor({
    type: 4,
    removeDefaultCursor: true,
    color: '#ffffff',
  });

  function likeArtwork(idKarya) {
    // Kirim permintaan Ajax ke server
    fetch('like_artwork_process.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ idKarya: idKarya }),
    })
      .then(response => response.json())
      .then(data => {
        console.log('Respons dari server:', data); // Tambahkan ini untuk melihat respons dari server di konsol

        if (data.success) {
          // Update jumlah like di UI
          const likeCountElement = document.getElementById(`like-count-${idKarya}`);
          likeCountElement.textContent = data.likes;

          // Ubah warna tombol like berdasarkan respons dari server
          const likeButton = document.querySelector(`.like-button[onclick="likeArtwork(${idKarya})"]`);
          if (data.alreadyLiked) {
            likeButton.classList.add('text-red-500');
            likeButton.classList.remove('text-white');
          } else {
            likeButton.classList.remove('text-red-500');
            likeButton.classList.add('text-white');
          }
        } else {
          alert('Gagal menambahkan like. Harap login terlebih dahulu.');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
      });
  }
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
<!-- Cursor CDN -->

</html>