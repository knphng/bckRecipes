<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Form\Type\RecipeType;
use AppBundle\Entity\Folder;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;


class FolderController extends Controller
{

    /**
    * @Rest\View()
    * @Rest\Get("/folders")
    */
    public function getFoldersAction(Request $request)
    {
        $folders = $this->getDoctrine()
          ->getRepository('AppBundle:Folder')
          ->findAll();

          /*foreach ($folders as $folder) {
                  $formatted[] = [
                       'id' => $folder->getId(),
                       'name' => $folder->getName(),
                       'recipes' => $folder->getRecipes(),
                  ];
              }*/

        //return new JsonResponse($formatted);

        return $folders;
    }

    /**
    * @Rest\View()
    * @Rest\Get("/folder/details/{id}")
    */
    public function getFolderDetailsAction($id){
        $folder = $this->getDoctrine()
            ->getRepository('AppBundle:Folder')
            ->find($id);

        $recipes = $this->getDoctrine()
            ->getRepository('AppBundle:Folder')
            ->find($id)
            ->getRecipes();


        if (empty($folder)) {
            return new JsonResponse(['message' => 'Folder not found'], Response::HTTP_NOT_FOUND);
        }
        /*$serializer = $this->get('jms_serializer');
        $jsonContent = $serializer->serialize($folder, 'json');*/

        /*$formatedRecipes = [];

        foreach ($recipes as $recipe) {
                $formatedRecipes[] = [
                     'id' => $recipe->getId(),
                     'name' => $recipe->getName(),
                     'ingredients' => $recipe->getIngredients(),
                     'txtRecipe' => $recipe->getTxtRecipe(),
                     'cookingTime' => $recipe->getCookingTime(),
                     'folder' => $recipe->getFolder(),
                ];
            }
        $formatted[] = [
             'id' => $folder->getId(),
             'name' => $folder->getName(),
             'recipes' => $formatedRecipes,
        ];

        return new JsonResponse($formatted);*/

        return $folder;

    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/folder/create")
     */
     public function postFoldersAction(Request $request)
     {
         $folder = new Folder;
         $form = $this->createForm(FolderType::class, $folder);

        $form->submit($request->request->all()); // Validation des données

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($folder);
            $em->flush();
            return $folder;
        } else {
            return $form;
        }
     }

     /**
    * @Rest\View()
    * @Rest\Put("/folder/edit/{id}")
    */
    public function editFolderAction(Request $request)
       {
           $folder = $this->get('doctrine.orm.entity_manager')
                   ->getRepository('AppBundle:Folder')
                   ->find($request->get('id'));

           if (empty($folder)) {
               return new JsonResponse(['message' => 'Folder not found'], Response::HTTP_NOT_FOUND);
           }

           $form = $this->createForm(FolderType::class, $folder);

           $form->submit($request->request->all());

           if ($form->isValid()) {
               $em = $this->get('doctrine.orm.entity_manager');
               // l'entité vient de la base, donc le merge n'est pas nécessaire.
               // il est utilisé juste par soucis de clarté
               $em->merge($folder);
               $em->flush();
               return $folder;
           } else {
               return $form;
           }
       }

  /**
  * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
  * @Rest\Delete("/folder/delete/{id}")
  */
  public function removeFolderAction(Request $request)
  {
      $em = $this->get('doctrine.orm.entity_manager');
      $folder = $em->getRepository('AppBundle:Folder')->find($request->get('id'));

      if ($folder) {
          $em->remove($folder);
          $em->flush();
      }

  }

}
