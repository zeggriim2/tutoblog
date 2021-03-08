<?php

namespace App\Table;

use App\Models\Category;
use PDO;

final class CategoryTable extends Table {


    protected $table = "category";
    protected $class = Category::class;


    /**
     * @param App\Model\Post[] $posts
     */
    public function hydratePosts(array $posts): void
    {
        $postsById = [];
        foreach ($posts as $post){
            $postsById[$post->getId()] = $post;
        }

        $categories = $this->pdo
            ->query("SELECT c.*, pc.post_id
                    FROM post_category pc
                    JOIN category c ON pc.category_id = c.id
                    WHERE pc.post_id IN (" . implode(' ,', array_keys($postsById)) .")
                ")->fetchAll(PDO::FETCH_CLASS, Category::class);

        // On parcourt les catégories
        foreach ($categories as $category){
            /** @var $category Category */
            $postsById[$category->getPostId()]->addCategorie($category);
        }
    }

}