<?php
namespace App\Entity;
class Article
{
    private $uri;
    private $title;
    public function setUri(string $uri) 
    {
        $this->uri = strtolower(str_replace(' ', '_', $uri));
        return $this;
    }
    
    public function getUri()
    {
        return $this->uri;
    }
    public function setTitle(string $title) 
    {
        $this->title = $title;
        return $this;
    }
    public function getTitle()
    {
        return $this->title;
    }
    
}