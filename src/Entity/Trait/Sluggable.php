<?php
namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;

trait Sluggable {
    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(): void
    {
        $this->slug = (new AsciiSlugger())->slug($this->id.'-'.$this->name.'-'.$this->surname);
    }
}