<?php

namespace App\Controller;

use App\Entity\Category;
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
        $builder = $em->getRepository(Product::class)
            ->createQueryBuilder('p');

        if ($request->query->getInt('category')) {
            $builder->andWhere('p.category = :id')
                ->setParameter('id', $request->query->getInt('_category'));
        }

        if ($request->query->get('product')) {
            $builder->andWhere('p.name LIKE :name')
                ->setParameter('name', '%' . $request->query->get('_product') . '%');
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $builder->getQuery(),
            $request->query->getInt('page', 1)/*page number*/,
            8
        );

        return $this->render('product/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * @Route("/product/{id}", name="show_product")
     * @return Response
     */
    public function show(Product $product)
    {
        return $this->render('product/show.html.twig', ['product' => $product]);
    }
}
