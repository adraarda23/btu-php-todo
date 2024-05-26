<?php

include 'config.php';
session_start();


if (!isset($_SESSION['kullanici_id'])) {
    header("Location: login.php");
    exit;
}

$kullanici_id = $_SESSION['kullanici_id'];


$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_partner']) && isset($_POST['kullaniciadi'])) {
    $todo_id = $_POST['add_partner'];
    $kullaniciadi = $conn->real_escape_string($_POST['kullaniciadi']);

    $kullanici_sorgu = "SELECT kullanici_id FROM kullanicilar WHERE kullaniciadi = '$kullaniciadi'";
    $kullanici_sonuc = $conn->query($kullanici_sorgu);

    if ($kullanici_sonuc->num_rows > 0) {
        $kullanici_row = $kullanici_sonuc->fetch_assoc();
        $eklenen_kullanici_id = $kullanici_row['kullanici_id'];

        $ekle_sorgu = "INSERT INTO todo_listesi_kullanicilar (todo_id, kullanici_id) VALUES ('$todo_id', '$eklenen_kullanici_id')";
        
        if ($conn->query($ekle_sorgu) === TRUE) {
            header("Location: todo_list.php");
            exit;
        } else {
            $error_message = "Hata: Görev eklenemedi.";
        }
    } else {
        $error_message = "Girdiğiniz kullanıcı adı mevcut değil.";
    }
}

// Veritabanı bağlantısını kapatın
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Ekle</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body class="bg">
    <div class="d-flex d-f-col d-f-center">
        <h2 class="d-font-big">Partner Ekle</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="m-t-1">
            <input type="text" class="d-input" name="kullaniciadi" placeholder="Kullanıcı Adı" required><br>
            <input type="hidden" name="add_partner" value="<?php echo $_POST['add_partner']; ?>">
            <div class="d-flex d-f-space">
                <input type="submit" class="d-btn-add m-t-1" value="Partner Ekle">
                <a href="todo_list.php" class="d-font">Geri Dön</a>
            </div>
            <?php if (!empty($error_message)) { ?>
                <p class='d-error'><?php echo $error_message; ?></p>
            <?php } ?>
        </form>
    </div>
</body>
</html>
