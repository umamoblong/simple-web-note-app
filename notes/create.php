<?php
require_once '../config/database.php';

$title = $content = '';
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
    
    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {
        $title = mysqli_real_escape_string($conn, $title);
        $content = mysqli_real_escape_string($conn, $content);
        
        $sql = "INSERT INTO notes (title, content) VALUES ('$title', '$content')";
        
        if (mysqli_query($conn, $sql)) {
            header('Location: ../index.php?message=Catatan berhasil ditambahkan!&type=success');
            exit();
        } else {
            $errors['database'] = 'Gagal menyimpan catatan: ' . mysqli_error($conn);
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Catatan Baru
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
                               value="<?php echo htmlspecialchars($title); ?>"
                               placeholder="Masukkan judul catatan">
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
                                  rows="8"
                                  placeholder="Tulis isi catatan Anda di sini..."><?php echo htmlspecialchars($content); ?></textarea>
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
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Catatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Tips Menulis Catatan
                </h5>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>Gunakan judul yang jelas dan deskriptif</li>
                    <li>Pisahkan ide dengan paragraf atau poin-poin</li>
                    <li>Gunakan format yang konsisten</li>
                    <li>Periksa ejaan dan tata bahasa</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>