<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kullaniciadi = $_POST['kullaniciadi'];
    $kullanici_sifresi = $_POST['kullanici_sifresi'];

    // Şifreyi hash'lemek güvenlik açısından önemlidir
    $kullanici_sifresi_hash = password_hash($kullanici_sifresi, PASSWORD_DEFAULT);

    $sql = "INSERT INTO kullanicilar (kullaniciadi, kullanici_sifresi) VALUES ('$kullaniciadi', '$kullanici_sifresi_hash')";

    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
    } else {
        $error = "Kayıt işlemi sırasında hata: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kayıt Ol</title>
</head>
<body>
    <h2>Kayıt Ol</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Kullanıcı Adı: <input type="text" name="kullaniciadi" required><br>
        Şifre: <input type="password" name="kullanici_sifresi" required><br>
        <input type="submit" value="Kayıt Ol">
    </form>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
