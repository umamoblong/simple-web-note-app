<?php
require_once 'config/database.php';

$where_clouse = '';
if ($_GET['search'] ?? false) {
    $search_term = mysqli_real_escape_string($conn, $_GET['search']);
    $where_clouse = "WHERE title LIKE '%$search_term%' OR content LIKE '%$search_term%'";
}
// Ambil data catatan dari database
$sql = "SELECT * FROM notes " . $where_clouse . " ORDER BY updated_at DESC";
$result = mysqli_query($conn, $sql);

// Hitung total catatan
$total_notes = mysqli_num_rows($result);
?>

<?php include 'includes/header.php'; ?>

<h1 class="mb-4">Daftar Catatan</h1>

<?php if (isset($_GET['message'])): ?>
    <div class="alert alert-<?php echo $_GET['type'] ?? 'success'; ?> alert-dismissible fade show">
        <?php echo htmlspecialchars($_GET['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-chart-bar text-primary me-2"></i>Statistik
                </h5>
                <p class="card-text">Total Catatan: <strong><?php echo $total_notes; ?></strong></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-lightbulb text-warning me-2"></i>Tips
                </h5>
                <p class="card-text">Gunakan aplikasi ini untuk mencatat ide, tugas, atau apapun yang penting!</p>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan di index.php setelah bagian tips dan sebelum "Catatan Saya" -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="" class="row g-3">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari catatan berdasarkan judul atau isi..."
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i>Cari
                </button>
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Catatan Saya</h2>
    <a href="notes/create.php" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Tambah Catatan Baru
    </a>
</div>

<?php if ($total_notes > 0): ?>
    <div class="row">
        <?php while ($note = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-truncate"><?php echo htmlspecialchars($note['title']); ?></h5>
                        <p class="card-text">
                            <?php 
                            $content = htmlspecialchars($note['content']);
                            echo strlen($content) > 100 ? substr($content, 0, 100) . '...' : $content;
                            ?>
                        </p>
                        <small class="text-muted">
                            <i class="far fa-clock me-1"></i>
                            Diperbarui: <?php echo date('d/m/Y H:i', strtotime($note['updated_at'])); ?>
                        </small>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="notes/edit.php?id=<?php echo $note['id']; ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="notes/delete.php?id=<?php echo $note['id']; ?>" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirmDelete('<?php echo addslashes($note['title']); ?>')">
                            <i class="fas fa-trash me-1"></i>Hapus
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-secondary" 
                           data-bs-toggle="modal" 
                           data-bs-target="#noteModal<?php echo $note['id']; ?>">
                            <i class="fas fa-eye me-1"></i>Detail
                        </a>
                    </div>
                </div>
                
                <!-- Modal untuk melihat catatan lengkap -->
                <div class="modal fade" id="noteModal<?php echo $note['id']; ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><?php echo htmlspecialchars($note['title']); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p><?php echo nl2br(htmlspecialchars($note['content'])); ?></p>
                                <hr>
                                <small class="text-muted">
                                    <i class="far fa-calendar-plus me-1"></i>
                                    Dibuat: <?php echo date('d/m/Y H:i', strtotime($note['created_at'])); ?><br>
                                    <i class="far fa-calendar-check me-1"></i>
                                    Diperbarui: <?php echo date('d/m/Y H:i', strtotime($note['updated_at'])); ?>
                                </small>
                            </div>
                            <div class="modal-footer">
                                <a href="notes/edit.php?id=<?php echo $note['id']; ?>" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="text-center py-5">
        <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
        <h3>Belum ada catatan</h3>
        <p class="text-muted">Mulailah dengan membuat catatan pertama Anda!</p>
        <a href="notes/create.php" class="btn btn-primary btn-lg mt-3">
            <i class="fas fa-plus me-2"></i>Buat Catatan Pertama
        </a>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>