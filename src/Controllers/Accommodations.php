<?php

namespace AvaiBook\Controllers;


use AvaiBook\App\AppException;
use AvaiBook\Business\AccomodationsBusiness;

class Accommodations extends Controllers {

    /**
     * Endpoint para el método get de accommodation
     */
    public function showAll() {
        $this->response
            ->setCode(200)
            ->setData(
                (new AccomodationsBusiness())->getAccommodationsByUser(
                    $this->get['user'],
                    $this->get['accommodations'] ?? '' )
            );
    }

    /**
     * Endpoint para método post de accommodation
     * @throws AppException
     */
    public function newAccommodation() {
        $this->response
            ->setCode(201)
            ->setData(
                (new AccomodationsBusiness())->newAccommodation(
                    $this->get['user'],
                    $this->post
                )
            );
    }

    /**
     * Endpoint para método put de accommodation
     * @throws AppException
     */
    public function updateAccommodation() {
        $this->response
            ->setCode(200)
            ->setData(
                (new AccomodationsBusiness())->updateAccommodation(
                    $this->get['user'],
                    $this->get['accommodations'],
                    $this->put
                )
            );
    }
}
