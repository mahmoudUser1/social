<script src="<?php echo $js; ?>popper.min.js"></script>
<script src="<?php echo $js; ?>jquery-4.0.0.min.js"></script>
<script src="<?php echo $js; ?>bootstrap.min.js"></script>
<script src="<?php echo $js; ?>main.js"></script>
<?php if (isset($_SESSION['message']) || isset($_SESSION['error'])): ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            let modalMessage = document.getElementById("modalMessage");
            let messageType = '<?= isset($_SESSION['message']) ? 'success' : 'error' ?>';

            let messageText = "<?= addslashes(isset($_SESSION['message']) ? $_SESSION['message'] : $_SESSION['error']) ?>";
            let icon = messageType === 'success' ? '<i class="fa-solid fa-check-circle text-success me-2"></i>' : '<i class="fa-solid fa-exclamation-circle text-danger me-2"></i>';

            modalMessage.innerHTML = icon + messageText;
            modalMessage.className = messageType === 'success' ? 'text-success fw-semibold' : 'text-danger fw-semibold';

            <?php if (isset($_SESSION['message'])): ?>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            let modal = new bootstrap.Modal(
                document.getElementById("messageModal")
            );

            modal.show();
        });
    </script>

<?php endif; ?>
</body>

</html>