<?php declare(strict_types=1);

namespace SkillshareShortener\Model;

class RedirectRecord
{
    private $remote_address;
    private $requested_url;
    private $user_agent;
    private $accepted_languages;
    private $link_id;

    /**
     * RedirectRecord constructor.
     * @param int $link_id
     * @param string $remote_address
     * @param string $requested_url
     * @param string $user_agent
     * @param string $accepted_languages
     */
    public function __construct(
        int $link_id,
        string $remote_address,
        string $requested_url,
        string $user_agent,
        string $accepted_languages
    ) {
        $this->remote_address = $remote_address;
        $this->requested_url = $requested_url;
        $this->user_agent = $user_agent;
        $this->accepted_languages = $accepted_languages;
        $this->link_id = $link_id;
    }

    /**
     * @return string
     */
    public function getRemoteAddress(): string
    {
        return $this->remote_address;
    }

    /**
     * @return string
     */
    public function getRequestedUrl(): string
    {
        return $this->requested_url;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->user_agent;
    }

    /**
     * @return string
     */
    public function getAcceptedLanguages(): string
    {
        return $this->accepted_languages;
    }

    /**
     * @return int
     */
    public function getLinkId(): int
    {
        return $this->link_id;
    }
}
