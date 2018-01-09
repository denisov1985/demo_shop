<?php

namespace App\Twig;

use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(ContainerInterface $container, SessionInterface $session)
    {
        $this->container = $container;
        $this->session = $session;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('filter_name', [$this, 'doSomething'], ['is_safe' => ['html']]),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_categories', [$this, 'doSomething']),
            new TwigFunction('get_orders_count', [$this, 'getOrdersCount']),
        ];
    }

    /**
     * Get orders count
     * @return mixed
     */
    public function getOrdersCount()
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository(Order::class);
        if (!$this->session->isStarted()) {
            $this->session->start();
        }
        $order = $repository->findBySession($this->session->get('token'));
        return $order->getProducts()->count();
    }

    public function doSomething()
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository(Product::class);
        $cat = $repository->getCategories();
        return $cat;
    }
}
