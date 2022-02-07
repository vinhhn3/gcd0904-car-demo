<?php

namespace App\Controller;

use App\Entity\Car;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends Controller
{
    /**
     * @Route("/car",name="car_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $cars = $em->getRepository(Car::class)->findAll();
        
        return $this->render('car/index.html.twig', array(
            'cars' => $cars,
        ));
    }
    
    /**
     * Finds and displays a car entity.
     *
     * @Route("/car/{id}", name="car_show")
     */
    public function showAction(Car $car)
    {
        return $this->render('car/show.html.twig', array(
            'car' => $car,
        ));
    }
    
    /**
     * @Route("/car_desc", name="car_desc")
     */
    public function carDesc()
    {
        // Call Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        // Create Query
        $query = $em->createQuery(
            "
                SELECT c
                FROM App\Entity\Car c
                ORDER BY c.travelledDistance DESC
                "
        );
        
        // Execute Query
        $result = $query->getResult();
        
        // Send result to View for rendering
        return $this->render("car/index.html.twig", [
            "cars" => $result,
        ]);
    }
    
}
