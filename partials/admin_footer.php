        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php foreach ($vendorScripts as $script): ?>
<script src="<?php echo e($script); ?>"></script>
<?php endforeach; ?>

<script src="../assets/js/admin.js"></script>

<?php foreach ($pageScripts as $script): ?>
<script src="<?php echo e($script); ?>"></script>
<?php endforeach; ?>
</body>
</html>
