<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Image;
use App\Form\ImageFormType;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
       $image=new Image();
       $form=$this->createForm(ImageFormType::class, $image);
       $form->handleRequest($request);
       if($form->isSubmitted()&& $form->isValid())
       {
           $file=$form->get('imageName')->getData();
           $fileName=sha1(random_bytes(14)).'.'.$file->guessExtension();
           $file->move($this->getParameter('image-directory'),$fileName);
           $image->setImageName($fileName);
           $em->persist($image);
           $em->flush();
           return new Response("success");
          
       }
       
       return $this->render("default/index.html.twig",["form"=>$form->createView()]);
    }
}
