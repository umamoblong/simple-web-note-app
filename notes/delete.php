<?php
require_once '../config/database.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data catatan untuk konfirmasi
$sql = "SELECT * FROM notes WHERE id = $id";
$result = mysqli_query($conn, $sql);
$note = mysqli_fetch_assoc($result);

// Jika catatan tidak ditemukan, redirect ke index
if (!$note) {
    header('Location: ../index.php?message=Catatan tidak ditemukan!&type=danger');
    exit();
}

// Hapus catatan jika konfirmasi diberikan
if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
    $sql = "DELETE FROM notes WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        header('Location: ../index.php?message=Catatan berhasil dihapus!&type=success');
        exit();
    } else {
        header('Location: ../index.php?message=Gagal menghapus catatan!&type=danger');
        exit();
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Penghapusan
                </h4>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <i class="fas fa-trash-alt fa-4x text-danger mb-3"></i>
                    <h4>Apakah Anda yakin ingin menghapus catatan ini?</h4>
                </div>
                
                <div class="card mb-4">
                    <div class="card-body text-start">
                        <h5 class="card-title"><?php echo htmlspecialchars($note['title']); ?></h5>
                        <p class="card-text">
                            <?php 
                            $content = htmlspecialchars($note['content']);
                            echo strlen($content) > 150 ? substr($content, 0, 150) . '...' : $content;
                            ?>
                        </p>
                        <small class="text-muted">
                            Dibuat: <?php echo date('d/m/Y H:i', strtotime($note['created_at'])); ?>
                        </small>
                    </div>
                </div>
                
                <div class="d-flex justify-content-center">
                    <a href="../index.php" class="btn btn-secondary me-3">
                        <i class="fas fa-times me-1"></i>Batal
                    </a>
                    <a href="?id=<?php echo $id; ?>&confirm=yes" class="btn btn-danger">
                        <i class="fas fa-check me-1"></i>Ya, Hapus
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informasi
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-0">
                    <i class="fas fa-exclamation-circle text-warning me-2"></i>
                    Tindakan ini tidak dapat dibatalkan. Setelah dihapus, catatan tidak dapat dikembalikan.
                </p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>