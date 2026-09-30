<script src="assets/js/app.js?v=<?= filemtime(BASE_PATH . '/public/assets/js/app.js') ?>"></script>
<?php foreach ($scripts ?? [] as $script): ?>
<script src="assets/js/<?= e($script) ?>?v=<?= filemtime(BASE_PATH . '/public/assets/js/' . $script) ?>"></script>
<?php endforeach; ?>
