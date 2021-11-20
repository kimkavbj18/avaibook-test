<?php

namespace AvaiBook\Business;


use AvaiBook\App\AppException;
use AvaiBook\Database\Database;

class AccomodationsBusiness extends Business {

    private int $tradeNameLength = 150;
    private array $types = [
        'HOUSE',
        'FLAT',
        'VILLA'
    ];

    /**
     * @param string $userID
     * @param string $accID
     * @param bool $getKey
     * @param bool $parsedata
     * @return array
     */
    public function getAccommodationsByUser( string $userID, string $accID = '', bool $getKey = false, bool $parsedata = true ) : array {

        $accommodations = [];
        $data = ( new Database() )->run()['data'];

        $accommodations_ids = array_filter(
            array_column( $data, 'user_id' ),
            function( &$value, $k ) use ( $userID ) {
                return $value == $userID;
            },
            ARRAY_FILTER_USE_BOTH
        );

        foreach( $accommodations_ids as $accommodation_key => $accommodation_value) {
            if ( $accID != '' && $data[ $accommodation_key ]['accommodation_id'] == $accID) {
                $key = $getKey ? [ 'record_key' => $accommodation_key ] : [];
                $data = $parsedata ? $this->parseData( $data[ $accommodation_key ] ) : $data[ $accommodation_key ] ;
                return array_merge( $data, $key );
            } else {
                $accommodations[] = $this->parseData( $data[ $accommodation_key ] );
            }
        }

        return $accommodations;
    }

    /**
     * @param string $userID
     * @param array $post
     * @return array
     * @throws AppException
     */
    public function newAccommodation( string $userID, array $post ) : array {
        $this->validateAccData( $post );

        $database = new Database();
        $data = $database->run()['data'];

        $user = new UserBusiness();

        $record = [
            'user_name' => $user->getUserName( $userID ),
            'user_id' => $userID,
            'accommodation_id' => count( $data ) + 1,
            'accommodation_name' => $post['trade_name'],
            'accommodation_address' => $post['address'] ?? '',
            'accommodation_city' => $post['city'] ?? '',
            'accommodation_postal_code' => $post['postal_code'] ?? '',
            'accommodation_country' => $post['country'] ?? '',
            'accommodation_type' => $post['type'],
            'distribution' => json_encode( $this->convertDistribution( $post['distribution'] ) ),
            'max_guests' => $post['max_guests'],
            'last_update' => date('Y-m-d H:i:s'),
        ];

        $database->addRecord( $record );

        return $this->parseData( $record );

    }

    /**
     * @param string $userID
     * @param string $accID
     * @param array $put
     * @return array
     * @throws AppException
     */
    public function updateAccommodation ( string $userID, string $accID, array $put ) : array {
        $this->validateAccData( $put );

        $raw_record = $this->getAccommodationsByUser( $userID, $accID, true, false );

        if ( !isset( $raw_record['record_key'] ) ) {
            throw new AppException( "No hay registro para los identificadores: User: $userID y Accomodation: $accID" );
        }

        $key = $raw_record['record_key'];
        unset( $raw_record['record_key'] );

        $columns = implode( ';', array_keys( $raw_record ) );

        $record = [
            'user_name' => $raw_record['user_name'],
            'user_id' => $raw_record['user_id'],
            'accommodation_id' => $raw_record['accommodation_id'],
            'accommodation_name' => $put['trade_name'],
            'accommodation_address' => $put['address'] ?? $raw_record['accommodation_address'],
            'accommodation_city' => $put['city'] ?? $raw_record['accommodation_city'],
            'accommodation_postal_code' => $put['postal_code'] ?? $raw_record['accommodation_postal_code'],
            'accommodation_country' => $put['country'] ?? $raw_record['accommodation_country'],
            'accommodation_type' => $put['type'],
            'distribution' => json_encode( $this->convertDistribution( $put['distribution'] ) ),
            'max_guests' => $put['max_guests'],
            'last_update' => date('Y-m-d H:i:s'),
        ];

        $database = new Database();
        $data = $database->run()['data'];
        $data[ $key ] = $record;

        $database->rewriteCSV( $columns, $data );

        return $this->parseData( $record );
    }

    /**
     * @param string $tradeName
     * @return bool
     */
    public function validateTradeName( string $tradeName ) : bool {
        return strlen( $tradeName ) <= $this->tradeNameLength;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function validateTypes( string $type ) : bool {
        return in_array( $type, $this->types );
    }

    /**
     * @param int $roomNumber
     * @param int $minRoomNumber
     * @return bool
     */
    public function validateNumber( int $roomNumber, int $minRoomNumber ) : bool {
        return $roomNumber >= $minRoomNumber;
    }

    /**
     * @param array $data
     * @throws AppException
     */
    private function validateAccData( array $data ) : void {

        if ( empty( $data ) ) {
            throw new AppException('No existe data para insertar');
        }

        if ( !isset($data['trade_name'])
            || !isset($data['type'])
            || !isset($data['distribution'])
            || !isset($data['max_guests'])
            || !isset($data['distribution']['living_rooms'])
            || !isset($data['distribution']['bedrooms'])
            || !isset($data['distribution']['beds']) ) {
            throw new AppException('Faltan campos requeridos');
        }

        if ( !$this->validateTradeName( $data['trade_name'] ) ) {
            throw new AppException("El campo trade_name no debe superar los $this->tradeNameLength caracteres");
        }

        if ( !$this->validateTypes( $data['type'] ) ) {
            $types = implode( ',', $this->types );
            throw new AppException("El campo type solo puede tener los valores: $types");
        }

        if ( !$this->validateNumber( $data['distribution']['living_rooms'], 1 ) ) {
            throw new AppException("El campo distribution->living_rooms debe tener un valor mayor a 0");
        }

        if ( !$this->validateNumber( $data['distribution']['bedrooms'], 1 ) ) {
            throw new AppException("El campo distribution->bedrooms debe tener un valor mayor a 0");
        }

        if ( !$this->validateNumber( $data['distribution']['beds'], 1 ) ) {
            throw new AppException("El campo distribution->beds debe tener un valor mayor a 0");
        }

        if ( !$this->validateNumber( $data['distribution']['beds'], $data['max_guests']) ) {
            throw new AppException("El número de visitantes no puede exceder el número de camas");
        }

    }

    /**
     * @param array $data
     * @return array
     */
    private function parseData( array $data ) : array {

        return [
            'id' => $data['user_id'],
            'trade_name' => $data['accommodation_name'],
            'type' => $data ['accommodation_type'],
            'distribution' => $this->parseDistribution( json_decode($data ['distribution'], true) ),
            'max_guests' => $data['max_guests'],
            'update_at' => date('Y-m-d', strtotime($data['last_update']))
        ];
    }

    /**
     * @param array $rooms
     * @return array
     */
    private function parseDistribution( array $rooms ) : array {
        return [
            'living_rooms' => $rooms['living_rooms'],
            'bedrooms' => count($rooms['bed_rooms']),
            'beds' => array_sum(
                array_map(
                    function ( $key ) {
                        return $key['beds'];
                    },
                    $rooms['bed_rooms']
                )
            ),
        ];
    }

    /**
     * @param array $distribution
     * @return array
     */
    private function convertDistribution( array $distribution ) : array {
        $bedrooms = [];
        $beds_x_bedrooms = ( int )( $distribution['beds'] / $distribution['bedrooms'] );
        $missing_beds = $distribution['beds'] % $distribution['bedrooms'];

        for ( $i=0; $i < $distribution['bedrooms']; $i++ ) {
            $bedrooms[] = [
                'beds' => $beds_x_bedrooms,
            ];
        }

        if ( $missing_beds ) {
            $bedrooms[0]['beds'] = $bedrooms[0]['beds'] + $missing_beds;
        }

        return [
            'living_rooms' => $distribution['living_rooms'],
            'bed_rooms' => $bedrooms
        ];
    }

}
