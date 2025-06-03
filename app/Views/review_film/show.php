<?php
    require_once APP_ROOT . '/app/Views/includes/header.php';
?>

<h2>Detail Review Film</h2>

<p><strong>Judul Film:</strong> <?= htmlspecialchars($review['film_title'] ?? 'Tidak Diketahui') ?></p>
<p><strong>Reviewer:</strong> <?= htmlspecialchars($review['username'] ?? 'Anonim') ?></p>

<?php if (isset($review['rating'])): ?>
    <p><strong>Rating:</strong> <?= (int)$review['rating'] ?>/10</p>
<?php endif; ?>

<p><strong>Ulasan:</strong></p>
<p><?= nl2br(htmlspecialchars($review['review_text'] ?? '')) ?></p>

<a href="/reviews">Kembali ke Daftar Review</a>

<?php
    // Memuat footer
    require_once APP_ROOT . '/app/Views/includes/footer.php';
?>