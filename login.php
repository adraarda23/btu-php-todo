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
</head>
<body>
    <h2>Giriş Yap</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Kullanıcı Adı: <input type="text" name="kullaniciadi" required><br>
        Şifre: <input type="password" name="kullanici_sifresi" required><br>
        <input type="submit" value="Giriş Yap">
    </form>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <p>Hesabınız yok mu? <a href="register.php">Kayıt Ol</a></p>
</body>
</html>
