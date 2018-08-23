<?php declare(strict_types=1);

namespace SkillshareShortener\Repository;

use PDO;
use SkillshareShortener\Model;

class RedirectRecord
{
    /**
     * @var PDO
     */
    private $database;

    /**
     * RedirectRecord constructor.
     * @param PDO $database
     */
    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    /**
     * @param Model\RedirectRecord $redirectRecord
     * @return Model\RedirectRecord
     */
    public function store(Model\RedirectRecord $redirectRecord): Model\RedirectRecord
    {
        $statement = $this->database->prepare(
            "INSERT INTO `redirect_records`" .
            "(`link_id`, `requested_url`, `remote_address`, `user_agent`, `accepted_languages`)" .
            " VALUES (:link_id, :requested_url, :remote_address, :user_agent, :accepted_languages);"
        );

        $statement->execute([
            "link_id" => $redirectRecord->getLinkId(),
            "requested_url" => $redirectRecord->getRequestedUrl(),
            "remote_address" => $redirectRecord->getRemoteAddress(),
            "user_agent" => $redirectRecord->getUserAgent(),
            "accepted_languages" => $redirectRecord->getAcceptedLanguages()
        ]);

        return $redirectRecord;
    }
}
