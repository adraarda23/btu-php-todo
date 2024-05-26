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


    $delete_todo_kullanicilar = "DELETE FROM todo_listesi_kullanicilar WHERE todo_id='$delete_id'";
    if ($conn->query($delete_todo_kullanicilar) !== TRUE) {
        echo "Error: " . $delete_todo_kullanicilar . "<br>" . $conn->error;
    }


    $delete_todo = "DELETE FROM todo_listesi WHERE todo_id='$delete_id' AND kullanici_id='$kullanici_id'";
    if ($conn->query($delete_todo) !== TRUE) {
        echo "Error: " . $delete_todo . "<br>" . $conn->error;
    }
}


$sql = "SELECT * FROM todo_listesi WHERE kullanici_id='$kullanici_id'";
$result = $conn->query($sql);


$shared_sql = "SELECT t.todo_id, t.baslik, t.paragraf FROM todo_listesi t
               INNER JOIN todo_listesi_kullanicilar tk ON t.todo_id = tk.todo_id
               WHERE tk.kullanici_id = '$kullanici_id'";
$shared_result = $conn->query($shared_sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yapılacaklar Listesi</title>
    <link rel="stylesheet" href="styles/style.css">
    <script src="scripts/main.js"></script>
</head>
<body class="bg">
    <div class="d-flex d-f-col d-f-center d-w-100">
        <h2 class="d-font-big">Yapılacaklar Listesi</h2>
        <div class="tab">
            <button class="tablinks" onclick="openTab(event, 'kendiListem')">Kendi Listem</button>
            <button class="tablinks" onclick="openTab(event, 'paylasilanlar')">Paylaşılanlar</button>
        </div>
        <div id="kendiListem" class="tabcontent d-w-50 ">
            <form method="post" class="d-flex d-f-col d-f-center " action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input class="d-input m-1 rm-m-l" type="text" name="baslik" placeholder="Görev Başlığı" required><br>
                <textarea class="d-textarea m-1 rm-m-l d-h-20 d-w-100" name="paragraf" placeholder="Yapılacak Görevleri Yazınız..." required></textarea><br>
                <div class="d-flex d-f-space d-w-70">
                    <input class="d-btn" type="submit" value="Ekle">
                    <a href="logout.php" class="d-btn-delete rm-dec m-l-1">Çıkış Yap</a>
                </div>
            </form>
            <h3 class="d-font-medium">Yapılacaklar:</h3>
            <ul class="d-w-100">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <li class="rm-ls d-border m-b-1">
                        <div class="d-flex d-f-space m-1">
                            <h3 class="d-font-medium d-wrap"><?php echo $row['baslik']; ?></h3>
                            <div class="d-flex d-f-col d-f-center">
                                <div class="d-flex">
                                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <input type="hidden" name="delete_id" value="<?php echo $row['todo_id']; ?>">
                                        <input type="submit" class="d-btn-delete m-r-1" value="Sil">
                                    </form>
                                    <form method="post" action="update_todo.php">
                                        <input type="hidden" name="update_id" value="<?php echo $row['todo_id']; ?>">
                                        <input type="submit" class="d-btn-edit" value="Düzenle">
                                    </form>
                                </div>
                                <form method="post" action="add_partner.php" class="m-t-1">
                                    <input type="hidden" name="add_partner" value="<?php echo $row['todo_id']; ?>">
                                    <input type="submit" class="d-btn-add" value="Partner Ekle">
                                </form>
                            </div>
                        </div>
                        <p class="d-font d-wrap"><?php echo nl2br($row['paragraf']); ?></p>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <div id="paylasilanlar" class="tabcontent d-w-50">
            <h3 class="d-font-medium">Paylaşılanlar:</h3>
            <ul class="d-w-100">
                <?php while ($shared_row = $shared_result->fetch_assoc()) { ?>
                    <li class="rm-ls d-border m-b-1">
                        <div class="d-flex d-f-space m-1">
                            <h3 class="d-font-medium d-wrap"><?php echo $shared_row['baslik']; ?></h3>
                        </div>
                        <p class="d-font d-wrap"><?php echo nl2br($shared_row['paragraf']); ?></p>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <script>
        document.getElementsByClassName('tablinks')[0].click();
    </script>
</body>
</html>
