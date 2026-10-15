<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            font-style: normal;
            font-size: 18px;    
        }

        body {
            background: #CFE0EB;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column; 
            min-height: 100vh;
        }

        .screen { /**kotak */
            background: white;
            position: relative;
            height: 600px;
            width: 360px;
            box-shadow: 0px 0px 24px #5c5696;
        }

        .screen__content {
            z-index: 1;
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 100px; 
        }

        .login__field {
            padding: 20px 0px;
            position: relative;
        }

        .login__icon {
            position: absolute;
            top: 30px;
            color: #7875b5;
        }

        .login__input {
            border: none;
            border-bottom: 2px solid #d1d1d4;
            background: none;
            padding: 10px;
            padding-left: 24px;
            font-weight: 700;
            width: 75%;
            transition: 0.2s;
        }

        .login__input:active,
        .login__input:focus,
        .login__input:hover {
            outline: none;
            border-bottom-color: #6a679e;
        }

        .login__submit {
            background: #fff;
            font-size: 14px;
            margin-top: 30px;
            padding: 16px 20px;
            border-radius: 26px;
            border: 1px solid #AECBD6;
            text-transform: uppercase;
            font-weight: 700;
            display: flex;
            align-items: center;
            width: 100%;
            color: #black;
            box-shadow: 0px 2px 2px #AECBD6;
            cursor: pointer;
            transition: 0.2s;
        }

        .login__submit:active,
        .login__submit:focus,
        .login__submit:hover {
            border-color: #6a679e;
            outline: none;
        }

        .button__icon {
            font-size: 24px;
            margin-left: auto;
            color: #7875b5;
        }

        .logo {
            position: absolute;
            top: 40px;
            left: 50%;
            transform: translateX(-50%);
            width: 200px; 
            height: auto;
        }

        .logo-text {
            position: absolute;
            top: 200px; 
            left: 50%;
            transform: translateX(-50%);
            font-family: Raleway, sans-serif;
            font-size: 30px; 
            font-weight: 700;
            color: #000;
            text-align: center;
            margin-top: 5px;
        }

        .login {
            width: 320px;
            padding: 30px;
            padding-top: 200px; 
        }

        .toggle-password {
            position: absolute;
            right: 20px;
            top: 38px;
            cursor: pointer;
            color: #7875b5;
        }

        .backmainpage {
            text-align: center;
            text-decoration: none;
            color: #007bff;
            font-size: 20px;
            margin-top: 10px; 
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
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="screen">
            <div class="screen__content">
                <img src="mcorplogo.png" alt="Logo" class="logo">
                <p class="logo-text">Log Masuk Kerani</p>
                <form action="keranisslogin.php" method="post" class="login">
                    <?php if(isset($_GET['error'])) { ?>
                    <p class="error"><?php echo $_GET['error']; ?></p>
                    <?php } ?>
                    <div class="login__field">
                        <i class="login__icon fas fa-user"></i>
                        <input type="text" name="username" class="login__input" placeholder="Nama Pengguna">
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-lock"></i>
                        <input type="password" name="password" id="password" class="login__input" placeholder="Kata Laluan">
                        <i class="toggle-password fas fa-eye" id="togglePassword"></i>
                    </div>
                    <button class="button login__submit">
                        <span class="button__text">Log Masuk</span>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="backmainpage">
        <a href="index.php"><i class='bx bx-log-out-circle'></i> Kembali ke Dashboard</a>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    
</body>
</html>
