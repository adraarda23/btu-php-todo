<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kullaniciadi = $_POST['kullaniciadi'];
    $kullanici_sifresi = $_POST['kullanici_sifresi'];

    $sql = "SELECT * FROM kullanicilar WHERE kullaniciadi='$kullaniciadi'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($kullanici_sifresi, $row['kullanici_sifresi'])) {
            $_SESSION['kullanici_id'] = $row['kullanici_id'];
            header("Location: todo_list.php");
        } else {
            $error = "Geçersiz kullanıcı adı veya şifre.";
        }
    } else {
        $error = "Geçersiz kullanıcı adı veya şifre.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="styles/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="bg d-flex d-f-center d-f-col">
        <h2 class="d-font-big">Hesap Bilgilerinizi Girin</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input class="d-input m-1 rm-m-l" type="text" name="kullaniciadi" placeholder="Kullanıcı Adı" required><br>
            <input class="d-input m-1 rm-m-l" type="password" name="kullanici_sifresi" placeholder="Şifre" required><br>
            <input class="d-btn" type="submit" value="Giriş Yap">
        </form>
        <?php if (!empty($error)) echo "<p class='d-error'>$error</p>"; ?>
        <p class="d-font">Hesabınız yok mu? <a class="rm-dec d-btn" href="register.php">Kayıt Ol</a></p>
</body>
</html>
