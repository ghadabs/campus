<?php
namespace App\Data;

class SearchData
{

    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string
     */
    public $q = '';

    /**
     * @var boolean
     */
    public $gratuit = false;

    /**
     * @var Categorie[]
     */
    public $categories=[];

    /**
     * @var Langue[]
     */
    public $langues=[];

    /**
     * @var Niveau[]
     */
    public $niveaux=[];

    
    /**
     * @var Formation[]
     */
    public $formations=[];

    /**
     * @var datetime
     */
    public $dateDeb;

    /**
     * @var datetime
     */
    public $dateFin;

    /**
     * @var string
     */
    public $email;

}