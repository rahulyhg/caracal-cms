<?php

namespace App\Business\Library;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Business\Library\LibraryRepository")
 */
class Library
{
    // Constructors

    public static function createEmpty(string $id): self
    {
        $instance = new self();

        $instance->id = $id;

        return $instance;
    }

    public static function create(string $id, string $name, ?string $description = null): self
    {
        $instance = self::createEmpty($id);

        $instance->name = $name;
        $instance->description = $description;

        return $instance;
    }

    // Behaviour

    public function rename(string $newName): void
    {
        $this->name = $newName;
    }

    public function changeDescription(string $newDescription): void
    {
        $this->description = $newDescription;
    }

    public function changeParent(self $parent): void
    {
        if ($this->parent === $parent) {
            return;
        }

        if ($this->parent) {
            $this->parent->removeChild($this);
        }

        $this->parent = $parent;

        $this->parent->addChild($this);
    }

    public function orphan(): void
    {
        if (!$this->parent) {
            return;
        }

        $this->parent->removeChild($this);

        $this->parent = null;
    }

    public function addChild(self $child): void
    {
        if ($this->children->contains($child)) {
            return;
        }

        $this->children->add($child);

        $child->changeParent($this);
    }

    public function removeChild(self $child): void
    {
        if (!$this->children->contains($child)) {
            return;
        }

        $this->children->removeElement($child);

        $child->orphan();
    }

    public function updateChildren(iterable $newChildren): void
    {
        $newChildren = (function (self ...$newChildren): array {
            return $newChildren;
        })(...$newChildren);

        $oldChildren = $this->getChildren();

        foreach ($oldChildren as $child) {
            $this->removeChild($child);
        }

        foreach ($newChildren as $child) {
            $this->addChild($child);
        }
    }

    public function addArticle(Article $article): void
    {
        if ($this->articles->contains($article)) {
            return;
        }

        $this->articles->add($article);

        $article->moveToLibrary($this);
    }

    public function removeArticle(Article $article): void
    {
        if (!$this->articles->contains($article)) {
            return;
        }

        $this->articles->removeElement($article);

        $article->removeFromLibrary();
    }

    public function updateArticles(iterable $newArticles): void
    {
        $newArticles = (function (Article ...$newArticles): array {
            return $newArticles;
        })(...$newArticles);

        $oldArticles = $this->getArticles() ?? [];

        foreach ($oldArticles as $article) {
            $this->removeArticle($article);
        }

        foreach ($newArticles as $article) {
            $this->addArticle($article);
        }
    }

    // Data

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getChildren(): array
    {
        return $this->children->toArray();
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function getArticles(): array
    {
        return $this->articles->toArray();
    }

    // Internal

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=32)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var Library|null
     *
     * @ORM\ManyToOne(targetEntity="Library")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Library", mappedBy="parent", cascade={"remove"})
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="library", cascade={"remove"})
     */
    private $articles;
}
