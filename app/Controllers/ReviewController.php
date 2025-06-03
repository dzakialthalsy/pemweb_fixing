<?php
namespace App\Controllers;

require_once APP_ROOT . '/app/Core/Session.php';
require_once APP_ROOT . '/app/Core/Functions.php';
require_once APP_ROOT . '/app/Models/ReviewFilm.php';

use App\Models\ReviewFilm;

class ReviewController {

    private $pdo;
    private $ReviewFilmModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->ReviewFilmModel = new ReviewFilm($pdo);
    }

    public function index() {
        $reviews = ReviewFilm::all();
        return view('review/index', ['reviews' => $reviews]);
    }

    public function create() {
        return view('reviews/create');
    }

    public function store() {
        $data = [
            'film_id' => $_POST['film_id'],
            'user_id' => $_POST['user_id'],
            'rating' => $_POST['rating'],
            'review_text' => $_POST['review_text']
        ];

        ReviewFilm::create($data);
        header('Location: /reviews');
        exit();
    }

    public function show($id) {
        $review = ReviewFilm::find($id);
        return view('reviews/show', ['review' => $review]);
    }
}