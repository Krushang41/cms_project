<?php include '../config/db.php'; 

    $baseUrl = getBaseUrl();

?>


</main>
    <footer class="admin-footer">
        <p>© 2024 CMS Admin Panel. All rights reserved.</p>
    </footer>
    <script src="<?php echo $baseUrl; ?>/assets/js/admin_scripts.js"></script>
    <script>
        function showSuccessToast(message) {
            toastr.success(message, 'Success', {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 5000,
            });
        }
    </script>
</body>
</html>
