<?php

class ReviewFilm {
    protected static $table = 'reviews_film';

    public static function create($data) {
        $db = static::getDB();
        $stmt = $db->prepare("
            INSERT INTO film_reviews (film_id, user_id, rating, review_text)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['film_id'],
            $data['user_id'],
            $data['review_text']
        ]);
    }

    public static function all() {
        $db = static::getDB();
        $stmt = $db->query("
            SELECT fr.*, f.title AS film_title, u.username 
            FROM reviews_film fr
            JOIN films f ON fr.film_id = f.id
            JOIN user u ON fr.user_id = u.id
            ORDER BY fr.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public static function find($id) {
        $db = static::getDB();
        $stmt = $db->prepare("
            SELECT fr.*, f.title AS film_title, u.username 
            FROM reviews_film fr
            JOIN films f ON fr.film_id = f.id
            JOIN user u ON fr.user_id = u.id
            WHERE fr.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
