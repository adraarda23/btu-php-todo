<?php
include 'config.php';
session_start();

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: login.php");
    exit;
}

$kullanici_id = $_SESSION['kullanici_id'];

// Yeni yapılacak ekleme
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['baslik']) && isset($_POST['paragraf'])) {
    $baslik = $conn->real_escape_string($_POST['baslik']);
    $paragraf = $conn->real_escape_string($_POST['paragraf']);

    $sql = "INSERT INTO todo_listesi (kullanici_id, baslik, paragraf) VALUES ('$kullanici_id', '$baslik', '$paragraf')";
    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Yapılacak güncelleme
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_id'])) {
    $update_id = $_POST['update_id'];
    $update_baslik = $conn->real_escape_string($_POST['update_baslik']);
    $update_paragraf = $conn->real_escape_string($_POST['update_paragraf']);

    $sql = "UPDATE todo_listesi SET baslik='$update_baslik', paragraf='$update_paragraf' WHERE todo_id='$update_id' AND kullanici_id='$kullanici_id'";
    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Yapılacak silme
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $sql = "DELETE FROM todo_listesi WHERE todo_id='$delete_id' AND kullanici_id='$kullanici_id'";
    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM todo_listesi WHERE kullanici_id='$kullanici_id'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Yapılacaklar Listesi</title>
</head>
<body>
    <h2>Yapılacaklar Listesi</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Başlık: <input type="text" name="baslik" required><br>
        Paragraf: <textarea name="paragraf" required></textarea><br>
        <input type="submit" value="Ekle">
    </form>

    <h3>Yapılacaklar:</h3>
    <ul>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <li>
                <strong><?php echo $row['baslik']; ?></strong>: <?php echo $row['paragraf']; ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="display:inline;">
                    <input type="hidden" name="delete_id" value="<?php echo $row['todo_id']; ?>">
                    <input type="submit" value="Sil">
                </form>
                <form method="post" action="update_todo.php" style="display:inline;">
                    <input type="hidden" name="update_id" value="<?php echo $row['todo_id']; ?>">
                    <input type="submit" value="Düzenle">
                </form>
            </li>
        <?php } ?>
    </ul>
    <a href="logout.php">Çıkış Yap</a>
</body>
</html>
