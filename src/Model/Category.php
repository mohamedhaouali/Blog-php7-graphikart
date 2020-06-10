<?php


namespace App\Model;


class Category
{
 private $id;

 private $slug;

 private $name;

 private $post_id;

 private $post;

 public function getID (): ?int {
 return $this->id;
}

    public function setID (int $id): self
    {
        $this->id = $id;
        return $this;
    }

public function getSlug (): ?string {
  return $this->slug;
}

    public function setSlug (string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

public function getName (): ?string {
   return $this->name;
}


    public function setName (string $name): self
    {
        $this->name = $name;
        return $this;
    }

 public function getPostID (): ?INT {
   return $this->post_id;
 }

    public function setPost (Post $post) {
     $this->post = $post;
    }

}