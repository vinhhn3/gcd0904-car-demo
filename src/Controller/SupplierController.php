<?php

namespace App\Controller;

use App\Entity\Supplier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SupplierController extends Controller
{
    /**
     * @Route("/supplier", name="supplier")
     */
    public function index(): Response
    {
        return $this->render('supplier/index.html.twig', [
            'controller_name' => 'SupplierController',
        ]);
    }
    
    /**
     * @Route("/supplier/local", name="get_supplier_local")
     */
    public function getSupplierLocal()
    {
        // Get Entity Manager
        $em = $this
            ->getDoctrine()
            ->getManager();
        
        // Get Repository
        $supplierRepo = $em->getRepository(Supplier::class);
        
        // Call function
        $localSuppliers = $supplierRepo->getSuppliersLocal();
        
        // Return result to View
        return $this->render('supplier/index.html.twig', [
            'suppliers' => $localSuppliers
        ]);
    }
    
    /**
     * @Route("/supplier/importers", name="get_supplier_importers")
     */
    public function getSupplierImporters()
    {
        // Get Entity Manager
        $em = $this
            ->getDoctrine()
            ->getManager();
        
        // Get Repository
        $supplierRepo = $em->getRepository(Supplier::class);
        
        // Call function
        $importerSuppliers = $supplierRepo->getSuppliersImporter();
        
        // Return result to View
        return $this->render('supplier/index.html.twig', [
            'suppliers' => $importerSuppliers
        ]);
    }
}
