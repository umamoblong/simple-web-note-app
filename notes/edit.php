<?php
require_once '../config/database.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data catatan dari database
$sql = "SELECT * FROM notes WHERE id = $id";
$result = mysqli_query($conn, $sql);
$note = mysqli_fetch_assoc($result);

// Jika catatan tidak ditemukan, redirect ke index
if (!$note) {
    header('Location: ../index.php?message=Catatan tidak ditemukan!&type=danger');
    exit();
}

$title = $note['title'];
$content = $note['content'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi input
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    if (empty($title)) {
        $errors['title'] = 'Judul catatan harus diisi';
    } elseif (strlen($title) > 255) {
        $errors['title'] = 'Judul tidak boleh lebih dari 255 karakter';
    }
    
    if (empty($content)) {
        $errors['content'] = 'Isi catatan harus diisi';
    }
    
    // Jika tidak ada error, update ke database
    if (empty($errors)) {
        $title = mysqli_real_escape_string($conn, $title);
        $content = mysqli_real_escape_string($conn, $content);
        
        $sql = "UPDATE notes SET title = '$title', content = '$content' WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            header('Location: ../index.php?message=Catatan berhasil diperbarui!&type=success');
            exit();
        } else {
            $errors['database'] = 'Gagal memperbarui catatan: ' . mysqli_error($conn);
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Catatan
                </h4>
            </div>
            <div class="card-body">
                <?php if (!empty($errors['database'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $errors['database']; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Catatan</label>
                        <input type="text" 
                               class="form-control <?php echo isset($errors['title']) ? 'is-invalid' : ''; ?>" 
                               id="title" 
                               name="title" 
                               value="<?php echo htmlspecialchars($title); ?>">
                        <?php if (isset($errors['title'])): ?>
                            <div class="invalid-feedback">
                                <?php echo $errors['title']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Isi Catatan</label>
                        <textarea class="form-control <?php echo isset($errors['content']) ? 'is-invalid' : ''; ?>" 
                                  id="content" 
                                  name="content" 
                                  rows="8"><?php echo htmlspecialchars($content); ?></textarea>
                        <?php if (isset($errors['content'])): ?>
                            <div class="invalid-feedback">
                                <?php echo $errors['content']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="../index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i>Perbarui Catatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="far fa-calendar me-2"></i>Info Catatan</h5>
                        <ul class="list-unstyled">
                            <li><small>Dibuat: <?php echo date('d/m/Y H:i', strtotime($note['created_at'])); ?></small></li>
                            <li><small>Diperbarui: <?php echo date('d/m/Y H:i', strtotime($note['updated_at'])); ?></small></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5><i class="fas fa-chart-pie me-2"></i>Statistik</h5>
                        <ul class="list-unstyled">
                            <li><small>Jumlah karakter: <?php echo strlen($content); ?></small></li>
                            <li><small>Jumlah kata: <?php echo str_word_count($content); ?></small></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>