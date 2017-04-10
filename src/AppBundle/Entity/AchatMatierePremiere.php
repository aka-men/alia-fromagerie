<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Achat;

/**
 * AchatMatierePremiere
 *
 * @ORM\Table(name="achat_matiere_premiere")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AchatMatierePremiereRepository")
 */
class AchatMatierePremiere extends Achat
{


    /**
     * @var array
     *
     * @ORM\Column(name="analyses", type="json_array", nullable=true)
     */
    private $analyses;

    /**
     * @var array
     *
     * @ORM\Column(name="production", type="json_array", nullable=true)
     */
    private $production;



    /**
     * Set analyses
     *
     * @param array $analyses
     *
     * @return AchatMatierePremiere
     */
    public function setAnalyses($analyses)
    {
        $this->analyses = $analyses;

        return $this;
    }

    /**
     * Get analyses
     *
     * @return array
     */
    public function getAnalyses()
    {
        return $this->analyses;
    }

    /**
     * Set production
     *
     * @param array $production
     *
     * @return AchatMatierePremiere
     */
    public function setProduction($production)
    {
        $this->production = $production;

        return $this;
    }

    /**
     * Get production
     *
     * @return array
     */
    public function getProduction()
    {
        return $this->production;
    }

}

