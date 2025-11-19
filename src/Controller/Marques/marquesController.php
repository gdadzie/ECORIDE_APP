<?php

namespace Controller\Marques;
use PDO;

class marquesController{

    private PDO $conn;

    public function __construct(PDO $conn){
        $this->conn = $conn;
    }

    public function getMarques(){


    }
}
