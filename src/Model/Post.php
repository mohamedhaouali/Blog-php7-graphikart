<?php

namespace App\Model;

use App\Helpers\Text;

class Post
{
    private $id;

    private $name;

    private $content;

    private $created_at;

    private $slug;

    private $categories = [];

    public function getName(): ?string {

        return $this->name;
    }

    public function setName(string $name): self {

        $this->name = $name;
        return $this;
    }

    public function getContent(): ?string {

        return $this->content;
    }

    public function setContent (string $content): self {

        $this->content = $content;

        return $this;
    }


    public function getFormattedContent (): ?string {

        return nl2br(htmlentities($this->content));
    }


    public function getExcerpt (): ?string {
        if($this->content === null)  {
            return null;
        }
        return nl2br(htmlentities(Text::excerpt($this->content, 60)));
    }

    public function getCreatedAt (): \DateTime
    {
        return new \DateTime($this->created_at);
    }

    public function setCreatedAt (string $date): self
    {

        $this->created_at = $date;
        return $this;
    }


    public function getSlug (): ?string
    {
       return $this->slug;
    }

    public function setSlug (string $slug): self
    {
         $this->slug = $slug;
         return $this;
    }

    public function getID (): ?int
    {
        return $this->id;
    }

    public function setID (int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Category[]
     */

    public function getCategories ():array
    {
        return $this->categories;
    }
    //method pour ajout categorie

    public function getCategoriesIds (): array
    {
      $ids = [];
      foreach ($this->categories as $category){
          $ids[] = $category->getID();
      }
      return $ids;
    }

    public function setCategories (array $categories): self
    {
      $this->categories = $categories;
      return $this;
    }



// zidna cette fonction

    public function addCategory (Category $category): void
    {
        $this->categories[] =  $category;
        //setPost min class Category
        $category->setPost($this);
    }

}