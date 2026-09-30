<script src="assets/js/app.js"></script>
<?php foreach ($scripts ?? [] as $script): ?>
<script src="assets/js/<?= e($script) ?>"></script>
<?php endforeach; ?>
