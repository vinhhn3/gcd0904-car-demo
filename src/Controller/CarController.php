<?php

namespace App\Controller;

use App\Entity\Car;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
        
        // Call CarRepository
        $carRepo = $em->getRepository(Car::class);
        
        // Call Function
        $result = $carRepo->sortCarByTravelledDistanceDesc();
        
        // Send result to View for rendering
        return $this->render("car/index.html.twig", [
            "cars" => $result,
        ]);
    }
    
    /**
     * @Route("/car_asc", name="car_asc")
     */
    public function carAsc()
    {
        // Call Entity Manager
        $em = $this
            ->getDoctrine()
            ->getManager();
        
        // Call Repository
        $carRepo = $em->getRepository(Car::class);
        
        // Call Function Asc
        $result = $carRepo->sortCarByTravelledDistanceAsc();
        
        // Send result to View for rendering
        return $this->render("car/index.html.twig", [
            "cars" => $result,
        ]);
    }
    
    /**
     * @Route("/car/distance/{value}", name="find_by_distance")
     */
    public function findByDistance($value)
    {
        // Call Entity Manager
        $em = $this
            ->getDoctrine()
            ->getManager();
        
        // Call CarRepository
        $carRepo = $em->getRepository(Car::class);
        
        // Call Function
        $result = $carRepo->findByDistance($value);
        
        // Render result through View
        return $this->render('car/index.html.twig', array(
            'cars' => $result
        ));
    }
    
    /**
     * @Route("/cars/{make}", name="find_cars_by_make")
     */
    public function showCarsByMake(string $make): Response
    {
        // Call Entity Manager
        $em = $this
            ->getDoctrine()
            ->getManager();
        
        // Call CarRepository
        $carRepo = $em->getRepository(Car::class);
        
        // Call Function
        $result = $carRepo->findBy(array(
            'make' => $make
        ));
        
        // Send result to View
        return $this->render('car/index.html.twig', [
            'cars' => $result
        ]);
        
    }
    
    /**
     * @Route("/cars/{id}/parts", name="get_car_with_parts")
     */
    public function getCarWithParts($id)
    {
        // Call Entity Manager
        $em = $this
            ->getDoctrine()
            ->getManager();
        
        // Call Repository
        $carRepo = $em->getRepository(Car::class);
        
        // Call Function
        $car = $carRepo->find($id);
        
        // Return result to View
        return $this->render('car/details.html.twig', [
            'car' => $car
        ]);
    }
    
    /**
     * @Route("/api/cars", methods={"GET"}, name="api_get_cars")
     */
    public function getCars(): JsonResponse
    {
        // Call Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        // Call Car Repo
        $carRepo = $em->getRepository(Car::class);
        
        // Get all cars
        $result = $carRepo->findAll();
        
        // Return a json response
        return new JsonResponse($result, 200, []);
    }
}
