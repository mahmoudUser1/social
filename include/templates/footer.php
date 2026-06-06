<script src="<?php echo $js; ?>popper.min.js"></script>
<script src="<?php echo $js; ?>jquery-4.0.0.min.js"></script>
<script src="<?php echo $js; ?>bootstrap.min.js"></script>
<script src="<?php echo $js; ?>main.js"></script>
<?php if (isset($_SESSION['message']) || isset($_SESSION['error'])): ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            let modalMessage = document.getElementById("modalMessage");

            <?php if (isset($_SESSION['message'])): ?>
                modalMessage.textContent = "<?= addslashes($_SESSION['message']) ?>";
                <?php unset($_SESSION['message']); endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                modalMessage.textContent = "<?= addslashes($_SESSION['error']) ?>";
                <?php unset($_SESSION['error']); endif; ?>

            let modal = new bootstrap.Modal(
                document.getElementById("messageModal")
            );

            modal.show();
        });
    </script>

<?php endif; ?>
</body>

</html>