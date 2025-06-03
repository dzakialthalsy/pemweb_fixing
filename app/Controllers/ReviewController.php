<?php
// niflix_project/app/Controllers/ReviewController.php

require_once APP_ROOT . '/app/Core/Session.php';
require_once APP_ROOT . '/app/Core/Functions.php';
require_once APP_ROOT . '/app/Models/ReviewFilm.php';

class ReviewController {

    private $pdo;
    private $reviewFilmModel; // Ubah nama properti menjadi camelCase untuk konsistensi

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        // BARIS INI DIPERBAIKI: Seharusnya membuat instance dari ReviewFilm, bukan ReviewController
        $this->reviewFilmModel = new ReviewFilm($pdo);
    }

    public function index() {
        // Memanggil metode instance dari model
        $reviews = $this->reviewFilmModel->all(); 
        view('review_film/index', [
            'reviews' => $reviews, 
            'title' => 'Daftar Review Film'
        ]);
    }

    public function create() {
        // Anda mungkin perlu mengambil daftar film untuk dropdown di sini
        // Misalnya: $filmModel = new Film($this->pdo); $films = $filmModel->getAllFilms();
        // Untuk saat ini, saya akan melewati array kosong jika tidak ada data film yang diambil.
        // Anda perlu menambahkan logika untuk mengambil daftar film jika diperlukan.
        view('review_film/create', [
            'title' => 'Tambah Review Film',
            'films' => [] // Ganti dengan data film yang sebenarnya jika ada
        ]);
    }

    public function store() {
        if (!Session::has('user')) {
            redirect('/auth/login?message=' . urlencode('Anda harus login untuk menambahkan review.') . '&type=error');
        }

        $data = [
            'film_id' => $_POST['film_id'] ?? null,
            'user_id' => Session::get('user')['id'] ?? null,
            'rating' => $_POST['rating'] ?? 0,
            'review_text' => trim($_POST['review_text'] ?? '')
        ];

        // Validasi dasar
        if (empty($data['film_id']) || empty($data['user_id']) || empty($data['review_text'])) {
            redirect('/review_film/create?message=' . urlencode('Semua field wajib diisi.') . '&type=error');
            return;
        }

        // Memanggil metode instance dari model
        if ($this->reviewFilmModel->create($data)) {
            redirect('/review_film?message=' . urlencode('Review berhasil ditambahkan!') . '&type=success');
        } else {
            redirect('/review_film/create?message=' . urlencode('Gagal menambahkan review.') . '&type=error');
        }
    }

    public function show($id) {
        // Memanggil metode instance dari model
        $review = $this->reviewFilmModel->find($id); 
        if (!$review) {
            redirect('/review_film?message=' . urlencode('Review tidak ditemukan.') . '&type=error');
        }
        view('review_film/show', [
            'review' => $review, 
            'title' => 'Detail Review Film'
        ]);
    }

    // Anda dapat menambahkan metode edit dan delete di sini jika diperlukan
    public function edit($id) {
        if (!Session::has('user')) {
            redirect('/auth/login?message=' . urlencode('Anda harus login untuk mengedit review.') . '&type=error');
        }

        $review = $this->reviewFilmModel->find($id);
        if (!$review) {
            redirect('/review_film?message=' . urlencode('Review tidak ditemukan.') . '&type=error');
        }

        // Hanya pemilik review atau admin yang bisa mengedit
        $currentUser = Session::get('user');
        if ($currentUser['id'] != $review['user_id'] && $currentUser['is_admin'] != 1) {
            redirect('/review_film/show/' . $id . '?message=' . urlencode('Anda tidak memiliki izin untuk mengedit review ini.') . '&type=error');
        }

        $message = null;
        $messageType = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rating = $_POST['rating'] ?? 0;
            $reviewText = trim($_POST['review_text'] ?? '');

            if (empty($reviewText)) {
                $message = 'Ulasan tidak boleh kosong.';
                $messageType = 'error';
            } else {
                if ($this->reviewFilmModel->update($id, $rating, $reviewText)) {
                    $message = 'Review berhasil diperbarui!';
                    $messageType = 'success';
                    redirect('/review_film/show/' . $id . '?message=' . urlencode($message) . '&type=' . urlencode($messageType));
                    exit();
                } else {
                    $message = 'Gagal memperbarui review.';
                    $messageType = 'error';
                }
            }
        }

        view('review_film/edit', [
            'title' => 'Edit Review Film',
            'review' => $review,
            'message' => $message,
            'message_type' => $messageType
        ]);
    }

    public function delete($id) {
        if (!Session::has('user')) {
            redirect('/auth/login?message=' . urlencode('Anda harus login untuk menghapus review.') . '&type=error');
        }

        $review = $this->reviewFilmModel->find($id);
        if (!$review) {
            redirect('/review_film?message=' . urlencode('Review tidak ditemukan.') . '&type=error');
        }

        // Hanya pemilik review atau admin yang bisa menghapus
        $currentUser = Session::get('user');
        if ($currentUser['id'] != $review['user_id'] && $currentUser['is_admin'] != 1) {
            redirect('/review_film?message=' . urlencode('Anda tidak memiliki izin untuk menghapus review ini.') . '&type=error');
        }

        $message = '';
        $messageType = '';

        if ($this->reviewFilmModel->delete($id)) {
            $message = 'Review berhasil dihapus!';
            $messageType = 'success';
        } else {
            $message = 'Gagal menghapus review.';
            $messageType = 'error';
        }
        redirect('/review_film?message=' . urlencode($message) . '&type=' . urlencode($messageType));
    }
}