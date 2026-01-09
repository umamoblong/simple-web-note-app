    </div>
    <footer class="footer mt-5 py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">Simple Note App &copy; <?php echo date('Y'); ?> - Latihan PHP Native</span>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Konfirmasi sebelum menghapus
        function confirmDelete(noteTitle) {
            return confirm(`Apakah Anda yakin ingin menghapus catatan: "${noteTitle}"?`);
        }
    </script>
</body>
</html>