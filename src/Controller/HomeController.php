<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller{

    /**
     * Function which the page which say hello
     * 
     * @Route("/hello/{prenom}/age/{age}", name="hello")
     * @Route("/hello", name="hello_base")
     * @Route("/hello/{prenom}", name="hello_prenom")
     * 
     * @return void
     */
    public function hello($prenom = "anonyme", $age = 0){
        return $this->render(
            "hello.html.twig", 
            [
                "prenom" => $prenom,
                "age" => $age
            ]
        );
    }

    /**
     * This function allow to show the homepage
     * 
     * @Route("/", name="homepage")
     *
     * @return void
     */
    public function home(){
        $prenoms = ["Samir" => 28,"Yasmine" => 23,"Jocker" => 100];
        $age = 28;
        $title = "Bonjour à tous";
        $name = "Samir";
        return $this->render(
            "home.html.twig",
            [
                "title" => $title,
                "name" => $name,
                "age" => $age,
                "prenoms" => $prenoms
            ]
        );
        
    }
}