<?php declare(strict_types=1);

namespace SkillshareShortener\Controller;

use League\Route\Http\Exception\BadRequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SkillshareShortener\Encoder;
use SkillshareShortener\Model;
use SkillshareShortener\Repository;
use Zend\Diactoros\Response\RedirectResponse;

/**
 * Class Redirect
 * @package SkillshareShortener\Controller
 */
class Redirect
{
    /**
     * @var Repository\Link
     */
    private $linkRepository;

    /**
     * @var Encoder
     */
    private $encoder;
    /**
     * @var Repository\RedirectRecord
     */
    private $redirectRecordRepository;

    /**
     * Redirect constructor.
     * @param Encoder $encoder
     * @param Repository\Link $linkRepository
     * @param Repository\RedirectRecord $redirectRecordRepository
     */
    public function __construct(
        Encoder $encoder,
        Repository\Link $linkRepository,
        Repository\RedirectRecord $redirectRecordRepository
    ) {
        $this->linkRepository = $linkRepository;
        $this->encoder = $encoder;
        $this->redirectRecordRepository = $redirectRecordRepository;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array $args
     * @return ResponseInterface
     * @throws BadRequestException
     */
    public function __invoke(ServerRequestInterface $request, array $args = []): ResponseInterface
    {
        if (!isset($args['short_link'])) {
            throw new BadRequestException();
        }
        $link_id = (int) $this->encoder->decode($args['short_link']);
        $link = $this->linkRepository->findById($link_id);

        $this->recordRedirect($request, $link_id);

        return new RedirectResponse($link->getUrl());
    }

    /**
     * @param ServerRequestInterface $request
     * @param int $link_id
     */
    private function recordRedirect(ServerRequestInterface $request, int $link_id): void
    {
        $server_params = $request->getServerParams();
        $record = new Model\RedirectRecord(
            $link_id,
            $server_params['REMOTE_ADDR'] ?? "",
            (string) $request->getUri(),
            $server_params['HTTP_USER_AGENT'] ?? "",
            $server_params['HTTP_ACCEPT_LANGUAGE'] ?? ""
        );

        $this->redirectRecordRepository->store($record);
    }
}
