<?php
    require("function.php");
    $error = "";
    $success = "";
    if(isset($_POST['tombol_register'])){
        $register_result = register($_POST);
        if($register_result === true){
            $success = "Register berhasil! Silakan login.";
        }else{
            $error = $register_result; // Akan berisi pesan error dari fungsi register()
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Page - SIMBS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f6f7fb;
        }
        .login-card {
            margin-top: 10%; /* Penyesuaian margin agar lebih terlihat */
            padding: 30px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 4px 18px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="login-card">
                <h3 class="text-center mb-4">Register SIMBS</h3>

                <?php if($error) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <?php if($success) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username..." autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Masukkan email..." autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password..." autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Konfirmasi Password</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password..." autocomplete="off" required>
                    </div>
                    <button type="submit" name="tombol_register" class="btn btn-primary w-100">Register</button>
                    <p class="mt-2">Sudah punya akun? <a href="login.php">Login</a></p>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>