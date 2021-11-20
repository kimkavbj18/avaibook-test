<?php

namespace AvaiBook\Controllers;


use AvaiBook\Business\AccomodationsBusiness;

class Accommodations extends Controllers {

    public function showAll() {
        $this->response
            ->setCode(200)
            ->setData(
                (new AccomodationsBusiness())->getAccommodationsByUser(
                    $this->get['user'],
                    $this->get['accommodations'] ?? '' )
            );
    }

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
