<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recipe
 *
 * @ORM\Table(name="recipe")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RecipeRepository")
 */
class Recipe
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
         * @ORM\ManyToOne(targetEntity="Folder", inversedBy="recipes")
         * @ORM\JoinColumn(name="folder_id", referencedColumnName="id", nullable=true)
         */
    private $folder;
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="txtRecipe", type="string", length=10000, nullable=true)
     */
    private $txtRecipe;

    /**
     * @var string
     *
     * @ORM\Column(name="ingredients", type="string", length=10000, nullable=true)
     */
    private $ingredients;

    /**
     * @var string
     *
     * @ORM\Column(name="cookingTime", type="string", nullable=true)
     */
    private $cookingTime;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Recipe
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set txtRecipe
     *
     * @param string $txtRecipe
     *
     * @return Recipe
     */
    public function setTxtRecipe($txtRecipe)
    {
        $this->txtRecipe = $txtRecipe;

        return $this;
    }

    /**
     * Get txtRecipe
     *
     * @return string
     */
    public function getTxtRecipe()
    {
        return $this->txtRecipe;
    }

    /**
     * Set ingredients
     *
     * @param string $ingredients
     *
     * @return Recipe
     */
    public function setIngredients($ingredients)
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    /**
     * Get ingredients
     *
     * @return string
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * Set cookingTime
     *
     * @param \DateTime $cookingTime
     *
     * @return Recipe
     */
    public function setCookingTime($cookingTime)
    {
        $this->cookingTime = $cookingTime;

        return $this;
    }

    /**
     * Get cookingTime
     *
     * @return \DateTime
     */
    public function getCookingTime()
    {
        return $this->cookingTime;
    }

    /**
     * Set folder
     *
     * @param \AppBundle\Entity\Folder $folder
     *
     * @return Recipe
     */
    public function setFolder(\AppBundle\Entity\Folder $folder = null)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get folder
     *
     * @return \AppBundle\Entity\Folder
     */
    public function getFolder()
    {
        return $this->folder;
    }
}
