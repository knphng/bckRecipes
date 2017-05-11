<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Form\Type\RecipeType;
use AppBundle\Entity\Recipe;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class RecipeController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/recipes")
     */
    public function getRecipesAction(Request $request)
    {
        $recipes = $this->getDoctrine()
          ->getRepository('AppBundle:Recipe')
          ->findAll();
         /* $formatted = [];

        foreach ($recipes as $recipe) {
                $formatted[] = [
                     'id' => $recipe->getId(),
                     'name' => $recipe->getName(),
                     'ingredients' => $recipe->getIngredients(),
                     'txtRecipe' => $recipe->getTxtRecipe(),
                     'cookingTime' => $recipe->getCookingTime(),
                     'folder' => $recipe->getFolder(),
                ];
            }

          return new JsonResponse($formatted);*/

          return $recipes;
    }

    /**
     * @Rest\View()
     * @Rest\Get("/recipe/details/{id}")
     */
    public function getRecipeDetailsAction($id)
    {
        $recipe = $this->getDoctrine()
            ->getRepository('AppBundle:Recipe')
            ->find($id);

        if (empty($recipe)) {
            return new JsonResponse(['message' => 'Recipe not found'], Response::HTTP_NOT_FOUND);
        }

        /*$formatted[] = [
             'id' => $recipe->getId(),
             'name' => $recipe->getName(),
             'ingredients' => $recipe->getIngredients(),
             'txtRecipe' => $recipe->getTxtRecipe(),
             'cookingTime' => $recipe->getCookingTime(),
             'folder' => $recipe->getFolder(),
        ];

        return new JsonResponse($formatted);*/

        return $recipe;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/recipe/create")
     */
     public function postRecipesAction(Request $request)
     {
         $recipe = new Recipe();
         $form = $this->createForm(RecipeType::class, $recipe);

         $form->submit($request->request->all()); // Validation des données

         if ($form->isValid()) {
             $em = $this->get('doctrine.orm.entity_manager');
             $em->persist($recipe);
             $em->flush();

             return $recipe;
         }else {
             return $form;
         }

        /* $time = $request->get('cookingTime');

         $folder = $this->getDoctrine()
             ->getRepository('AppBundle:Folder')
             ->find($request->get('folder'));

         $recipe->setName($request->get('name'))
            ->setIngredients($request->get('ingredients'))
            ->setTxtRecipe($request->get('txtRecipe'))
            ->setCookingTime(\DateTime::createFromFormat('H:i:s', $time))
            ->setFolder($folder);*/


     }

     /**
    * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
    * @Rest\Delete("/recipe/delete/{id}")
    */
     public function deleteRecipeAction(Request $request)
     {
         $em = $this->get('doctrine.orm.entity_manager');
         $recipe = $em->getRepository('AppBundle:Recipe')->find($request->get('id'));

         if ($recipe) {
             $em->remove($recipe);
             $em->flush();
         }
    }

    /**
     * @Rest\View()
     * @Rest\Put("/recipe/edit/{id}")
     */
    public function updateRecipeAction(Request $request)
    {
        return $this->updateRecipe($request, true);
    }
    /**
     * @Rest\View()
     * @Rest\Patch("/recipe/edit/{id}")
     */
    public function patchRecipeAction(Request $request)
    {
        return $this->updateRecipe($request, false);
    }

    private function updateRecipe(Request $request, $clearMissing)
    {
        $recipe = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Recipe')
                ->find($request->get('id'));

        if (empty($recipe)) {
            return new JsonResponse(['message' => 'Recipe not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(RecipeType::class, $recipe);

        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($recipe);
            $em->flush();
            return $recipe;
        } else {
            return $form;
        }

    }

}
