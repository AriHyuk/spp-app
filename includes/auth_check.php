<?php
// Check if user is logged in
if (!isset($_SESSION['id_petugas'])) {
    header("Location: ../login.php");
    exit();
}

// Check level if needed
function checkLevel($required_level) {
    if ($_SESSION['level'] !== $required_level) {
        header("Location: ../index.php?error=Akses ditolak!");
        exit();
    }
}
?>
