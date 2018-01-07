<?php

namespace App\Controller;

use App\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @Route("/", name="products")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(Product::class)->findAll();
        // replace this line with your own code!
        return $this->render('product/index.html.twig', ['products' => $products]);
    }

    /**
     * @Route("/product", name="show_product")
     * @return Response
     */
    public function show()
    {
        return $this->render('product/show.html.twig', ['products' => []]);
    }
}
