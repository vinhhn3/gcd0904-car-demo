<?php

namespace App\Controller;

use App\Entity\Car;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    
    /**
     * @Route("/api/cars/{id}", methods={"GET"}, name="api_get_cars_by_id")
     */
    public function getCarsById($id): JsonResponse
    {
        // Call Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        // Call Car Repo
        $carRepo = $em->getRepository(Car::class);
        
        // Get car by id
        $result = $carRepo->find($id);
        
        // Return a json response
        return new JsonResponse($result, 200, []);
    }
    
    /**
     * @Route("/api/cars/delete/{id}", methods={"DELETE"}, name="api_cars_delete_by_id")
     */
    public function deleteCarById($id): JsonResponse
    {
        // Call Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        // Call Car Repo
        $carRepo = $em->getRepository(Car::class);
        
        // Find car in Database
        $carInDb = $carRepo->find($id);
        
        // If car does not exist
        if (!$carInDb) {
            $data = [
                'status' => 404,
                'message' => "Car Not Found ..."
            ];
            return new JsonResponse($data, 404, []);
        }
        
        // Remove Car from Database if it exists
        $em->remove($carInDb);
        $em->flush();
        $data = [
            'status' => 200,
            'message' => 'Car deleted ...'
        ];
        
        return new JsonResponse($data, 200, []);
    }
    
    /**
     * @Route("/api/cars/new", methods={"POST"}, name="api_cars_create")
     */
    public function createCar(Request $request)
    {
        // Decode Request sent from Client
        $requestDecoded = $this->transformJsonBody($request);
        
        // Create new Object Car
        $car = new Car();
        
        // Set properties of $car
        $car->setMake($request->get('make'));
        $car->setModel($request->get('model'));
        $car->setTravelledDistance($request->get('travelledDistance'));
        
        // Call Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        // Add new Car to Database
        $em->persist($car);
        $em->flush();
        
        // Return 200 status code
        $data = [
            'status' => 200,
            'message' => 'Car added ...'
        ];
        return new JsonResponse($data, 200, []);
        
    }
    
    private function transformJsonBody(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        
        if ($data === null) {
            return $request;
        }
        
        $request->request->replace($data);
        
        return $request;
    }
    
    /**
     * @Route("/api/cars/edit/{id}", methods={"PUT", "PATCH"}, name="api_cars_edit")
     */
    public function editCar($id, Request $request)
    {
        // Find Car by $id
        $em = $this->getDoctrine()->getManager();
        $carRepo = $em->getRepository(Car::class);
        $car = $carRepo->find($id);
        
        // If $car does not exist, returns 404
        if (!$car) {
            $data = [
                'status' => 404,
                'message' => "Car Not Found ..."
            ];
            return new JsonResponse($data, 404, []);
        }
        // If $car exists
        // Set properties of $car sent by Request
        // Save to Database
        // Return 200
        $request = $this->transformJsonBody($request);
        $car->setMake($request->get('make'));
        $car->setModel($request->get('model'));
        $car->setTravelledDistance($request->get('travelledDistance'));
        
        $em->persist($car);
        $em->flush();
        
        $data = [
            'status' => 200,
            'message' => "Car updated ..."
        ];
        
        return new JsonResponse($data, 200, []);
    }
}
