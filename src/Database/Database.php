<?php

namespace AvaiBook\Database;


class Database {

    private string $csvfile_path;

    public function __construct() {
        $this->csvfile_path = BASE_PATH . '/../data.csv';
    }

    public function run() : array {
        $database = array_map( [$this, 'my_str_getcsv'], file( $this->csvfile_path ) );

        array_walk( $database, function (&$a) use ($database) {
            $a = array_combine( $database[0], $a );
        });
        array_shift($database);

        return [ 'data' => $database ];

    }

    public function addRecord ( array $data ) {
        $csv = fopen( $this->csvfile_path, 'a' );
        fputcsv( $csv, $data, ';' );
        fclose( $csv );

    }

    public function rewriteCSV( string $headings, array $data ) {
        $csv = fopen( $this->csvfile_path, 'w' );

        fputcsv( $csv, explode( ';', $headings ), ';' );

        foreach( $data as $row ){
            fputcsv( $csv, $row, ';' );
        }

        fclose($csv);
    }

    private function my_str_getcsv( $row ) : array {
        return str_getcsv( $row, ';' );
    }

}
