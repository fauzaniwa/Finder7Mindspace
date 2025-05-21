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
  <title>Daftar Akun</title>
  <link rel="icon" href="./img/FinderLogo.svg" type="image/x-icon" />
  <!-- Script Navbar Menu -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <!-- Script Cursor -->
  <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css" />
  <!-- Script Cursor -->
</head>

<body>
  <section id="register" class="bg-cover bg-center w-full h-full flex items-center justify-center"
    style="background-image: url(./img/bgregister.png);">
    <form action="systemdata.php" method="POST">
      <div class="flex flex-col items-center px-6 py-8 bg-white bg-opacity-10 rounded-xl gap-4 w-fit">

        <h1 class="text-2xl md:text-3xl text-white font-semibold">Daftar Akun</h1>
        <hr>

        <div class="flex-col gap-2 w-[350px]">
          <h1 class="text-lg md:text-xl text-white font-normal font-work">Nama Lengkap</h1>
          <input type="text" name="nama" class="w-[350px] h-10 rounded-lg px-2 font-work font-medium" required>
        </div>

        <div class="flex-col gap-2 w-[350px]">
          <h1 class="text-lg md:text-xl text-white font-normal font-work">Nomer Handphone</h1>
          <input type="number" name="no_hp" class="w-[350px] h-10 rounded-lg px-2 font-work font-medium" required>
        </div>

        <div class="flex-col gap-2 w-[350px]">
          <h1 class="text-lg md:text-xl text-white font-normal font-work">Tanggal Lahir</h1>
          <input type="date" name="tgl_lahir" class="w-[350px] h-10 rounded-lg px-2 font-work font-medium" required>
        </div>

        <div class="flex-col gap-2 w-[350px]">
          <h1 class="text-lg md:text-xl text-white font-normal font-work">Instansi</h1>
          <input type="text" name="instansi" class="w-[350px] h-10 rounded-lg px-2 font-work font-medium" required>
        </div>

        <hr>
        <h1 class="text-2xl md:text-3xl text-white font-semibold">Informasi Login</h1>
        <hr>

        <div class="flex-col gap-2 w-[350px]">
          <h1 class="text-lg md:text-xl text-white font-normal font-work">Email</h1>
          <input type="email" name="email" class="w-[350px] h-10 rounded-lg px-2 font-work font-medium" required>
        </div>

        <div class="flex-col gap-2 w-[350px] relative">
          <h1 class="text-lg md:text-xl text-white font-normal font-work">Password</h1>
          <div class="relative">
            <input type="password" name="password" id="passwordInput" class="w-full h-10 rounded-lg px-2 font-work font-medium"
              required>
            <span id="togglePassword"
              class="absolute inset-y-0 right-0 flex items-center justify-center pr-3 cursor-pointer text-sm text-black">
              Show
            </span>
          </div>
        </div>

        <div class="flex-col gap-2 w-[350px]">
          <label class="flex items-center gap-2">
            <input type="checkbox" class="rounded-lg px-2 font-work font-medium" required>
            <span class="text-white font-work">Saya setuju dengan Kebijakan Privasi.</span>
          </label>
        </div>

        

        <button type="submit" name="register"
          class="text-base w-full lg:text-xl text-white px-6 py-4 bg-[#BA1F36] rounded-lg font-work hover:bg-[#ba1f1f] duration-150 hover:drop-shadow-md">Daftar
          Sekarang</button>

          <p class="text-white">Sudah memiliki akun? Login <span><a href="login.php" class="font-bold text-[#BA1F36]">di
              sini.</a></span></p>

      </div>
    </form>
  </section>

      <!-- JavaScript untuk menampilkan pesan error -->
      <script>
        <?php if (isset($_SESSION['message'])): ?>
            alert("<?php echo $_SESSION['message']; ?>");
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
    </script>
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

<script>
  const togglePassword = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('passwordInput');

  togglePassword.addEventListener('click', function () {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    togglePassword.textContent = type === 'password' ? 'Show' : 'Hide';
  });
</script>


</html>