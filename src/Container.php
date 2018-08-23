<?php declare(strict_types=1);

namespace SkillshareShortener;

use League\Container\Definition\DefinitionAggregateInterface;
use League\Container\Inflector\InflectorAggregateInterface;
use League\Container\ServiceProvider\ServiceProviderAggregateInterface;
use League\Plates\Engine;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Strategy\StrategyInterface;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SkillshareShortener\Controller\Encode;
use SkillshareShortener\Controller\Index;
use SkillshareShortener\Controller\Redirect;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Class Container
 * @package SkillshareShortener
 */
class Container extends \League\Container\Container
{
    public function __construct(
        ?DefinitionAggregateInterface $definitions = null,
        ?ServiceProviderAggregateInterface $providers = null,
        ?InflectorAggregateInterface $inflectors = null
    ) {
        parent::__construct($definitions, $providers, $inflectors);

        // Core objects
        $this->share(ResponseInterface::class);
        $this->share(ServerRequestInterface::class);

        $this->share('request', function (): ServerRequestInterface {
            return ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
        });

        $this->share(PDO::class, function (): PDO {
            return new PDO("sqlite:" . __DIR__ . "/../data/shortener.db");
        });

        $this->share(Engine::class, function (): Engine {
            return new Engine(__DIR__ . "/../templates");
        });

        $this->share(Encoder::class);


        // Repositories
        $this->share(Repository\Link::class)->addArgument($this->get(PDO::class));
        $this->share(Repository\RedirectRecord::class)->addArgument($this->get(PDO::class));


        // Controllers
        $this->share(Encode::class)
            ->addArgument($this->get(Encoder::class))
            ->addArgument($this->get(Repository\Link::class));

        $this->share(Redirect::class)
            ->addArgument($this->get(Encoder::class))
            ->addArgument($this->get(Repository\Link::class))
            ->addArgument($this->get(Repository\RedirectRecord::class));

        $this->share(Index::class)->addArgument($this->get(Engine::class));


        // The Router
        $this->share(Router::class, function (): Router {
            /** @var StrategyInterface $strategy */
            $strategy = (new ApplicationStrategy())->setContainer($this);
            /** @var Router $router */
            $router = (new Router())->setStrategy($strategy);

            $router->get('/', Index::class);
            $router->get('/{short_link}', Redirect::class);
            $router->post('/_encode', Encode::class);

            return $router;
        });

        $this->share(Encoder::class);
    }
}
