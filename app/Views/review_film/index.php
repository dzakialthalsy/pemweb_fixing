<?php
    require_once APP_ROOT . '/app/Views/includes/header.php';

    // Pastikan base Path tersedia untuk tautan
    $basePath = dirname($_SERVER['SCRIPT_NAME']);
    if ($basePath === '/') {
        $basePath = '';
    } else {
        $basePath = rtrim($basePath, '/');
    }
?>

<main>
    <div class="review-container"> <h2>Daftar Review Film</h2>
        <a href="<?= $basePath ?>/review_film/create" class="add-review-link">+ Tambah Review Baru</a>

        <?php if (empty($reviews)): ?>
            <p>Belum ada review film. Jadilah yang pertama memberikan review!</p>
        <?php else: ?>
            <ul>
                <?php foreach ($reviews as $review): ?>
                    <li>
                        <strong><?= htmlspecialchars($review['film_title'] ?? 'Judul Tidak Tersedia') ?></strong>
                        oleh <?= htmlspecialchars($review['username'] ?? 'Anonim') ?>
                        <?php if (isset($review['rating'])): ?>
                            - Rating: <?= (int)$review['rating'] ?>/10
                        <?php endif; ?>
                        <br>
                        <?= nl2br(htmlspecialchars($review['review_text'] ?? '')) ?>
                        <br>
                        <a href="<?= $basePath ?>/review_film/show/<?= $review['id'] ?? '' ?>">Lihat</a> |
                        <a href="<?= $basePath ?>/review_film/edit/<?= $review['id'] ?? '' ?>">Edit</a> |
                        <a href="<?= $basePath ?>/review_film/delete/<?= $review['id'] ?? '' ?>" onclick="return confirm('Hapus review ini?')">Hapus</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div> </main>

<?php
// Memuat footer
require_once APP_ROOT . '/app/Views/includes/footer.php';
?>