<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengurusan Klinik Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            font-size: 16px;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-image: url('bckg.png');
            background-size: cover;
            color: #CFE0EB;
            height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        header {
            background-color: #d0efff;
            padding: 15px;
            text-align: center;
            border-bottom: 4px solid #d1d1d1;
            position: relative;
        }

        h1 {
            color: black;
            margin-bottom: 5px;
            font-size: 24px;
        }

        nav ul {
            list-style: none;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin: 0 10px;
        }

        nav ul li a {
            text-decoration: none;
            color: black;
            font-weight: 600;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: blue;
        }

        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }

        .hero {
            display: flex;
            justify-content: center;
            align-items: stretch;
            gap: 20px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: stretch;
            width: 90%;
            gap: 5%;
        }

        .section {
            background: transparent;
            color: black;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            flex: 1;
            max-width: 250px;
            min-width: 200px;
            transition: transform 0.3s;
            text-decoration: none;
        }

        .section:hover {
            transform: scale(1.05);
        }

        .section h2 {
            font-size: 1.5em;
            margin-bottom: 15px;
        }

        .section p {
            font-size: 1em;
            margin-bottom: 15px;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #d0efff;
            color: black;
        }

        .logo {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .logo img {
            width: 90px;
        }
        
        .pdflink {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #2c3e50;
            padding: 5px 15px;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        .pdflink a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            transition: background-color 0.3s, color 0.3s;
        }
        
        @media only screen and (max-width:800px) {
        /* tablets */
        .main {
            width: 80%;
            padding: 0;
        }
        .right {
            width: 100%;
        }
        }
        @media only screen and (max-width:500px) {
        /* phone */
        .menu, .main, .right {
            width: 100%;
        }

    </style>
</head>
<body>

    <header>
    <div class="logo">
        <img src="mcorplogo.png" alt="Logo">        
    </div>
    <h1>Sistem Pengurusan Peruntukan Perubatan (SPPP)</h1>
    <div class="pdflink">
        <a href="manualpengguna.pdf" target="_blank"><i class='bx bxs-file-pdf'></i> Manual Pengguna</a>
    </div>
    </header>
    <main>
        <section class="hero">
            <div class="container">
                <a href="ClinicLogin.php" class="section">
                    <h2>Klinik</h2>
                    <p>Akses semua sumber klinik.</p>
                </a>
                <a href="AdminLogin.php" class="section">
                    <h2>Admin</h2>
                    <p>Uruskan semua tugas pentadbiran.</p>
                </a>
                <a href="StaffLogin.php" class="section">
                    <h2>Staff</h2>
                    <p>Lihat sumber dan kakitangan.</p>
                </a>
                <a href="keranilogin.php" class="section">
                    <h2>Kerani</h2>
                    <p>Uruskan serba sedikit tugas pentadbiran.</p>
                </a>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Unit Teknologi Komunikasi & Maklumat MCORP. All rights reserved.</p>
    </footer>
</body>
</html>