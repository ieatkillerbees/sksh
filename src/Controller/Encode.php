<?php declare(strict_types=1);

namespace SkillshareShortener\Controller;

use League\Route\Http\Exception\BadRequestException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use SkillshareShortener\Encoder;
use SkillshareShortener\Model;
use SkillshareShortener\Repository;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class Encode
 *
 * @package SkillshareShortener\Controller
 */
class Encode
{
    private const BASEURL = "http://sk.sh/";
    /**
     * @var Encoder
     */
    private $encoder;
    /**
     * @var Repository\Link
     */
    private $linkRepository;

    /**
     * Encode constructor.
     * @param Encoder $encoder
     * @param Repository\Link $linkRepository
     */
    public function __construct(Encoder $encoder, Repository\Link $linkRepository)
    {
        $this->encoder = $encoder;
        $this->linkRepository = $linkRepository;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws BadRequestException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $payload = $request->getParsedBody();
        if (!isset($payload['url'])) {
            throw new BadRequestException();
        }

        if (filter_var($payload['url'], FILTER_VALIDATE_URL) === false) {
            throw new BadRequestException();
        }

        $link = $this->linkRepository->store((new Model\Link($payload['url'])));

        $response_payload = [
            "url" => $payload['url'],
            "short_link" => static::BASEURL . $this->encoder->encode($link->getId())
        ];

        return (new JsonResponse($response_payload, 201));
    }
}
