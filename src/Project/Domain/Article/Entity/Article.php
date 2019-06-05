<?php

namespace App\Project\Domain\Article\Entity;


use App\Project\Domain\User\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Sluggable\Util as Sluggable;

class Article
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var User
     */
    protected $author;

    /**
     * @var Tag|ArrayCollection
     */
    protected $tags;

    /**
     * @var ArrayCollection
     */
    protected $images;

    /**
     * @var ArrayCollection|User
     */
    protected $contributors;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;
    }

    /**
     * @return Tag|ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Tag|ArrayCollection $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
    }

    public function addTagFromName($name)
    {
        $tag = new Tag();
        $slug = Sluggable\Urlizer::urlize($name, '-');
        $tag->setTitle($name);
        $tag->setSlug($slug);

        $this->tags->add($tag);
    }

    /**
     * @return ArrayCollection
     */
    public function getImages(): ArrayCollection
    {
        return $this->images;
    }

    /**
     * @param ArrayCollection $images
     */
    public function setImages(ArrayCollection $images)
    {
        $this->images = $images;
    }

    /**
     * @return User|ArrayCollection
     */
    public function getContributors()
    {
        return $this->contributors;
    }

    /**
     * @param User|ArrayCollection $contributors
     */
    public function setContributors($contributors)
    {
        $this->contributors = $contributors;
    }

    /**
     * @param User $contributor
     */
    public function addContributor(User $contributor)
    {
        if (!$this->contributors->contains($contributor)) {
            $this->contributors->add($contributor);
        }
    }

    
}