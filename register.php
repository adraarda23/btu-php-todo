<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kullaniciadi = $conn->real_escape_string($_POST['kullaniciadi']);
    $kullanici_sifresi = $_POST['kullanici_sifresi'];

    // Kullanıcı adının zaten var olup olmadığını kontrol et
    $sql = "SELECT * FROM kullanicilar WHERE kullaniciadi = '$kullaniciadi'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $error = "Bu kullanıcı adı zaten alınmış.";
    } else {
        // Şifreyi hash'lemek güvenlik açısından önemlidir
        $kullanici_sifresi_hash = password_hash($kullanici_sifresi, PASSWORD_DEFAULT);

        $sql = "INSERT INTO kullanicilar (kullaniciadi, kullanici_sifresi) VALUES ('$kullaniciadi', '$kullanici_sifresi_hash')";

        if ($conn->query($sql) === TRUE) {
            header("Location: login.php");
        } else {
            $error = "Kayıt işlemi sırasında hata: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body class="bg">
    <div class="d-flex d-f-col d-f-center">
        <h2 class="d-font-big">Kayıt Ol</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input class="d-input m-1 rm-m-l" type="text" name="kullaniciadi" placeholder="Kullanıcı Adı" required><br>
            <input class="d-input m-1 rm-m-l" type="password" name="kullanici_sifresi" placeholder="Şifre" required><br>
            <div class="d-flex d-f-space">
                <input class="d-btn" type="submit" value="Kayıt Ol">
                <a href="login.php" class="d-font">Geri Dön</a>
            </div>
        </form>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        
    </div>
</body>
</html>
