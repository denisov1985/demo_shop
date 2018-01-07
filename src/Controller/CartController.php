<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends Controller
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(SessionInterface $session)
    {
        $session = $this->get('session');
        $session->start();
        // replace this line with your own code!
        return $this->render('cart/index.html.twig');
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function add(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');
        $session->start();

        $token = $session->get('token');
        if (is_null($token)) {
            $session->set('token', md5(mktime()));
            $token = $session->get('token');
        }

        $order = $em->getRepository(Order::class)->findOneBy(['session' => $token]);
        if (is_null($order)) {
            $order = new Order();
            $order->setSession($token);
            $order->setStatus(Order::STATUS_CREATED);
            $em->persist($order);
            $em->flush();
        }

        $product = $em->getRepository(Product::class)->find($id);

        $order->addProduct($product);
        $em->persist($order);
        $em->flush();

        return $this->redirect(
            $request->headers->get('referer')
        );
    }
}
