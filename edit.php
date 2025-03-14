<?php
include 'config.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_produk = $_POST['nama_produk'];
    $harga = str_replace(['Rp. ', '.'], '', $_POST['harga']); // Hapus Rp. dan titik
    $stok = $_POST['stok'];

    $stmt = $conn->prepare("UPDATE products SET nama_produk=?, harga=?, stok=? WHERE id=?");
    $stmt->bind_param("sdii", $nama_produk, $harga, $stok, $id);
    $stmt->execute();

    header("Location: index.php"); // Redirect setelah update
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2 class="text-center">Edit Produk</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nama Produk:</label>
            <input type="text" name="nama_produk" class="form-control" value="<?= $product['nama_produk'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Harga:</label>
            <div class="input-group">
                <span class="input-group-text">Rp.</span>
                <input type="text" id="harga" name="harga" class="form-control" value="<?= 'Rp. ' . number_format($product['harga'], 0, ',', '.') ?>" required onkeyup="formatRupiah(this)">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Stok:</label>
            <input type="number" name="stok" class="form-control" value="<?= $product['stok'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>

    <script>
        function formatRupiah(input) {
            let angka = input.value.replace(/[^,\d]/g, '').toString();
            let number_string = angka.split(',').join('');
            let sisa = number_string.length % 3;
            let rupiah = number_string.substr(0, sisa);
            let ribuan = number_string.substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            input.value = "Rp. " + rupiah;
        }
    </script>
</body>
</html>
