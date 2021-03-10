<?php

namespace App\Models;

use App\Helpers\Text;
use DateTime;

class Post {

    private $id;
    private $name;
    private $content;
    private $slug;
    private $created_at;
    private $categories = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getExcerpt(): ?string
    {
        if ($this->content === null){
            return null;
        }

        return nl2br(htmlentities(Text::excerpt($this->content)));
    }

    public function getCreatedAt(): DateTime
    {
        return new DateTime($this->created_at);
    }

    public function setCreatedAt( $createdAt): self
    {
        $this->created_at = $createdAt;

        return $this;
    }

    public function getFormattedContent(): ?string
    {
        return nl2br(htmlentities($this->content));
    }

    /**
     * @return Category[]
     */
    public function getCategories (): array
    {
        return $this->categories;
    }

    public function addCategorie(Category $category): void
    {
        $this->categories[] = $category;
        $category->setPost($this);
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
}