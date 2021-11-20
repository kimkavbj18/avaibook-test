<?php

namespace AvaiBook\App\Common;


use AvaiBook\App\AppException;

class InitUtils {
    /**
     * Carga un fichero .INI con el nombre $filename
     * y retorna sus valores en un arreglo
     * @param string $filename
     * @return array
     * @throws AppException
     */
    public function getIniVars( string $filename ) : array {
        $INIFile = BASE_PATH . "/$filename";

        if(!file_exists( $INIFile )) {
            throw new AppException("No se ha encontrado el archivo $filename");
        }

        return parse_ini_file($INIFile, true);
    }
}
