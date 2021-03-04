<?php

namespace App;

use PDO;

class PaginatedQuery {

    private $query;
    private $queryCount;
    private $pdo;
    private $perPage;
    private $count;
    private $items;

    public function __construct(string $query, string $queryCount, ?\PDO $pdo = null,int $perPage = 12)
    {
        $this->query = $query;
        $this->queryCount = $queryCount;
        $this->pdo = $pdo ?: Connection::getPdo();
        $this->perPage = $perPage;
    }

    public function getItems(string $classMapping): array{
        if (!$this->items) {
            $currentPage = $this->getCurrentPage();

            $count = (int)$this->pdo
                ->query($this->queryCount)
                ->fetch(PDO::FETCH_NUM)[0];

            $pages = $this->getPages();
            if($currentPage > $pages){
                throw new \Exception('Cette page n\'existe pas.');
            }

            $offset = $this->perPage * ($currentPage - 1);

            $this->items = $this->pdo->query($this->query . " LIMIT {$this->perPage} OFFSET $offset")
                ->fetchAll(PDO::FETCH_CLASS, $classMapping);
        }
        return $this->items;

    }

    public function previousPageLink(string $link): ?string {
        $currentPage = $this->getCurrentPage();
        if($currentPage <= 1) return null;
        if($currentPage > 2) $link .= '?page=' . ($currentPage - 1);
        return "<a href=\"{$link}\" class=\"btn btn-primary\">&laquo; Page Précédente</a>";
    }

    public function nextPageLink(string $link): ?string {
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();
        if($currentPage >= $pages) return null;
        $link .= '?page=' . ($currentPage + 1);
        return "<a href=\"{$link}\" class=\"btn btn-primary ml-auto\">Page Suivante &raquo;</a>";
    }

    private function getCurrentPage(): int
    {
        return URL::getPositiveInt('page',1);
    }

    private function getPages(): int
    {
        if(!$this->count){
            $this->count = (int)$this->pdo
                ->query($this->queryCount)
                ->fetch(\PDO::FETCH_NUM)[0];

        }
        return ceil($this->count / $this->perPage);
    }
}