<?php
include 'config.php';
session_start();

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: login.php");
    exit;
}

$kullanici_id = $_SESSION['kullanici_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['baslik']) && isset($_POST['paragraf'])) {
    $baslik = $conn->real_escape_string($_POST['baslik']);
    $paragraf = $conn->real_escape_string($_POST['paragraf']);

    $sql = "INSERT INTO todo_listesi (kullanici_id, baslik, paragraf) VALUES ('$kullanici_id', '$baslik', '$paragraf')";
    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_id'])) {
    $update_id = $_POST['update_id'];
    $update_baslik = $conn->real_escape_string($_POST['update_baslik']);
    $update_paragraf = $conn->real_escape_string($_POST['update_paragraf']);

    $sql = "UPDATE todo_listesi SET baslik='$update_baslik', paragraf='$update_paragraf' WHERE todo_id='$update_id' AND kullanici_id='$kullanici_id'";
    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

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
    <link rel="stylesheet" href="styles/style.css">
</head>
<body class="bg">
    <div class="d-flex d-f-col d-f-center">

        <h2 class="d-font-big">Yapılacaklar Listesi</h2>
        

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input class="d-input m-1 rm-m-l" type="text" name="baslik" placeholder="Görev Başlığı" required><br>
                <textarea class="d-textarea m-1 rm-m-l" name="paragraf" placeholder="Yapılacak Görevi Yazınız..." required></textarea><br>
                <div class="d-flex d-f-space">
                <input class="d-btn" type="submit" value="Ekle">
                <a href="logout.php" class="d-btn-delete rm-dec">Çıkış Yap</a>
        </div>
            </form>

        <h3 class="d-font-medium">Yapılacaklar:</h3>
        <ul class="d-w-30">
            <?php while ($row = $result->fetch_assoc()) { ?>
                
            <li class="rm-ls d-border m-b-1">
                <div class="d-flex d-f-space m-1">
                   <h3 class="d-font-medium d-wrap"><?php  echo $row['baslik']; ?></h3>
                    <div class="btn-group d-flex">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <input type="hidden" name="delete_id" value="<?php echo $row['todo_id']; ?>">
                            <input type="submit" class="d-btn-delete m-r-1" value="Sil">
                        </form>
                        <form method="post" action="update_todo.php">
                            <input type="hidden" name="update_id" value="<?php echo $row['todo_id']; ?>">
                            <input type="submit" class="d-btn-edit" value="Düzenle">
                        </form>
                    </div>
                </div>
                
                <p class="d-font d-wrap"><?php echo $row['paragraf']; ?></p>
                
                
            </li>
            <?php } ?>
        </ul>
        
    </div>
</body>
</html>
