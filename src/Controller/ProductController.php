<?php

namespace App\Controller;

use App\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @Route("/", name="products")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Product::class)
            ->createQueryBuilder('p')
            ->getQuery();

        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)/*page number*/,
            8
        );

        return $this->render('product/index.html.twig', ['pagination' => $pagination]);
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
