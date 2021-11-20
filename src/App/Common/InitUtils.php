<?php

namespace AvaiBook\App\Common;


use AvaiBook\App\AppException;

class InitUtils {
    public function getIniVars( string $filename ) : array {
        $INIFile = BASE_PATH . "/$filename";

        if(!file_exists( $INIFile )) {
            throw new AppException("No se ha encontrado el archivo $filename");
        }

        return parse_ini_file($INIFile, true);
    }
}
