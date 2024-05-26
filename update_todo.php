<?php
include 'config.php';
session_start();

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: login.php");
    exit;
}

$kullanici_id = $_SESSION['kullanici_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_id'])) {
    $update_id = $_POST['update_id'];

    $sql = "SELECT * FROM todo_listesi WHERE todo_id='$update_id' AND kullanici_id='$kullanici_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        header("Location: todo_list.php");
        exit;
    }
} else {
    header("Location: todo_list.php");
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Yapılacakları Düzenle</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body class="bg">
    <div class="d-flex d-f-col d-f-center">
        <h2 class="d-font-big">Yapılacakları Düzenle</h2>
        <form method="post" class="d-flex d-f-col" action="todo_list.php">
            <input type="hidden"  name="update_id" value="<?php echo $row['todo_id']; ?>">
            <label class="d-font-medium" for="update_baslik">Başlık</label>
            <input type="text" id="update_baslik" class="d-input"  name="update_baslik" value="<?php echo $row['baslik']; ?>" required><br>
            <label class="d-font-medium" for="update_paragraf">Paragraf</label>
            <textarea class="d-textarea" id="update_paragraf" name="update_paragraf" required><?php echo $row['paragraf']; ?></textarea><br>
            <input type="submit" class="d-btn-edit" value="Güncelle">
        </form>
    </div>
</body>
</html>
