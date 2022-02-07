<?php

namespace App\Controller;

use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends Controller
{
    /**
     * @Route("/customer", name="customer")
     */
    public function index(): Response
    {
        return $this->render('customer/index.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }
    
    /**
     * @Route("/customer/all/ascending", name="customer_all_ascending")
     */
    public function customerAscending()
    {
        // Call Entity Manager
        $em = $this
            ->getDoctrine()
            ->getManager();
        
        // Call CustomerRepo
        $customerRepo = $em->getRepository(Customer::class);
        
        // Call function
        $result = $customerRepo->getCustomersAscending();
        
        // Return result to View
        return $this->render('customer/index.html.twig', [
            'customers' => $result
        ]);
    }
    
    /**
     * @Route("/customer/all/descending", name="customer_all_descending")
     */
    public function customerDescending()
    {
        // Call Entity Manager
        $em = $this
            ->getDoctrine()
            ->getManager();
        
        // Call CustomerRepo
        $customerRepo = $em->getRepository(Customer::class);
        
        // Call function
        $result = $customerRepo->getCustomersDescending();
        
        // Return result to View
        return $this->render('customer/index.html.twig', [
            'customers' => $result
        ]);
    }
}
