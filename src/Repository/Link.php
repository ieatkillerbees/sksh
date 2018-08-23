<?php declare(strict_types=1);

namespace SkillshareShortener\Repository;

use RuntimeException;
use PDO;
use SkillshareShortener\Model;

/**
 * Class Link
 * @package SkillshareShortener\Repository
 */
class Link
{
    /**
     * @var PDO
     */
    private $database;


    /**
     * Link constructor.
     * @param PDO $database
     */
    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    /**
     * @param Model\Link $link
     * @return Model\Link
     */
    public function store(Model\Link $link): Model\Link
    {
        $statement = $this->database->prepare("INSERT INTO `links` (`url`, `url_hash`) VALUES (:url, :url_hash)");

        $statement->execute([
            "url" => $link->getUrl(),
            "url_hash" => $link->getUrlHash()
        ]);

        $link->setId($this->database->lastInsertId());
        return $link;
    }

    /**
     * @param int $id
     * @return Model\Link
     */
    public function findById(int $id): Model\Link
    {
        $statement = $this->database->prepare("SELECT * FROM `links` WHERE `id` = :id LIMIT 1");
        $statement->execute(["id" => $id]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            throw new RuntimeException("Not found");
        }

        $link = new Model\Link($result['url']);
        $link->setId((int) $result['id']);
        $link->setCreated(\DateTime::createFromFormat("Y-m-d H:i:s", $result['created']));
        $link->setUrlHash($result['url_hash']);
        return $link;
    }
}
