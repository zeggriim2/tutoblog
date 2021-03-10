<?php

namespace App\Table;

use App\Models\Post;
use App\PaginatedQuery;
use \PDO;

final class PostTable extends Table {

    protected $table = "post";
    protected $class = Post::class;


    public function findPaginated()
    {
        $paginatedQuery = new PaginatedQuery(
            "SELECT * FROM post ORDER BY created_at DESC",
            "SELECT COUNT(id) FROM post ",
            $this->pdo
        );

        $posts = $paginatedQuery->getItems(Post::class);

        (new CategoryTable($this->pdo))->hydratePosts($posts);

        return [$posts, $paginatedQuery];

    }

    public function findPaginatedForCategory(int $categoryId)
    {
        $paginatedQuery = new PaginatedQuery(
            "SELECT p.* 
                FROM post p
                JOIN post_category pc ON p.id = pc.post_id
                WHERE pc.category_id = {$categoryId}
        ORDER BY created_at ",
            "SELECT COUNT(category_id) FROM post_category WHERE category_id = {$categoryId}"
        );

        /** @var Post[] */
        $posts = $paginatedQuery->getItems(Post::class);

        (new CategoryTable($this->pdo))->hydratePosts($posts);

        return [$posts, $paginatedQuery];
    }

    public function update(Post $post): void
    {
        $query = $this->pdo->prepare("UPDATE " . $this->table . " SET name = :name, slug = :slug, content = :content, created_at = :created  WHERE id = :id");
        $ok = $query->execute(
            [   'name'      => $post->getName(),
                'slug'      => $post->getSlug(),
                'content'   => $post->getContent(),
                'created'   => $post->getCreatedAt()->format('Y-m-d H:i:s'),
                'id'        => $post->getId()
            ]);
        if ($ok === false){
            throw new \Exception("Impossible de mettre à jour l'enregistrement $id dans le table {$this->table}");
        }
    }


}