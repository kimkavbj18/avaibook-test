<?php

namespace AvaiBook\Database;


class Database {

    private string $csvfile_path;

    public function __construct() {
        $this->csvfile_path = BASE_PATH . '/../data.csv';
    }

    /**
     * Carga el fichero CSV en un arreglo de datos
     * @return array[]
     */
    public function run() : array {
        $database = array_map( [$this, 'my_str_getcsv'], file( $this->csvfile_path ) );

        array_walk( $database, function (&$a) use ($database) {
            $a = array_combine( $database[0], $a );
        });
        array_shift($database);

        return [ 'data' => $database ];

    }

    /**
     * Agrega un registro al final del fichero CSV
     * @param array $data
     */
    public function addRecord ( array $data ) {
        $csv = fopen( $this->csvfile_path, 'a' );
        fputcsv( $csv, $data, ';' );
        fclose( $csv );
    }

    /**
     * Reescribe el fichero CSV para actualizar un registro
     * @param string $headings
     * @param array $data
     */
    public function rewriteCSV( string $headings, array $data ) {
        $csv = fopen( $this->csvfile_path, 'w' );

        fputcsv( $csv, explode( ';', $headings ), ';' );

        foreach( $data as $row ){
            fputcsv( $csv, $row, ';' );
        }

        fclose($csv);
    }

    /**
     * Convierte el string CSV en un arreglo
     * @param $row
     * @return array
     */
    private function my_str_getcsv( $row ) : array {
        return str_getcsv( $row, ';' );
    }

}
