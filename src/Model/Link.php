<?php declare(strict_types=1);

namespace SkillshareShortener\Model;

/**
 * Class Link
 * @package SkillshareShortener\Model
 */
class Link
{
    private $url;
    private $url_hash;
    private $id;
    private $created;

    /**
     * Link constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
        $this->url_hash = (sha1($url));
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getUrlHash(): string
    {
        return $this->url_hash;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated(\DateTime $created): void
    {
        $this->created = $created;
    }

    /**
     * @param string $url_hash
     */
    public function setUrlHash(string $url_hash): void
    {
        $this->url_hash = $url_hash;
    }
}
