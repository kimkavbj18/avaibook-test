<?php

namespace AvaiBook\Business;


use AvaiBook\Database\Database;

class UserBusiness extends Business {

    public function getUserName( int $userID ) : string {
        $data = ( new Database() )->run()['data'];
        return $data[ array_search( $userID, array_column( $data, 'user_id' ) ) ]['user_name'];
    }
}
