<?php declare(strict_types=1);

namespace SkillshareShortener\Controller;

use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Class Index
 * @package SkillshareShortener\Controller
 */
class Index
{
    /**
     * @var Engine
     */
    private $engine;

    /**
     * Index constructor.
     * @param Engine $engine
     */
    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->engine->render('index'));
    }
}
