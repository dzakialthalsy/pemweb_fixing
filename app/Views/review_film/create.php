<?php
    require_once APP_ROOT . '/app/Views/includes/header.php';
?>

<h2>Tambah Review Film</h2>

<form action="/reviews/store" method="POST">
    <label for="film_id">Judul Film:</label><br>
    <select name="film_id" id="film_id" required>
        <?php foreach ($films as $film): ?>
            <option value="<?= $film['id'] ?>"><?= htmlspecialchars($film['title']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="review_text">Ulasan:</label><br>
    <textarea name="review_text" id="review_text" rows="5" required></textarea><br><br>

    <input type="hidden" name="user_id" value="<?= $_SESSION['user_login'] ?? 1 ?>"><!-- ganti dengan user login -->

    <button type="submit">Kirim Review</button>
</form>

<?php
// Memuat footer
require_once APP_ROOT . '/app/Views/includes/footer.php';
?>