# Challenge: Servicio para gestión de alojamientos vacacionales #

Este challenge consiste en implementar las historias de usuario descritas, creando una API que permita a los propietarios crear alojamientos que posteriormente anunciará en portales como Booking.com, Airbnb...

## Historias de usuario

Yo, como propietario de un alojamiento de alquiler vacacional, necesito enviar a AvaiBook la información de mis alojamientos para poder anunciarlos depués.

  * Necesito poner un nombre comercial al alojamiento, que no supere los 150 caracteres.
  * Es necesario especificar el tipo de alojamiento que es, los portales sólo admiten entre los siguientes tipos: HOUSE, FLAT, VILLA.
  * Tengo que enviar la distribución de las habitaciones, con las siguientes especificaciones:
  	* Todos los  alojamientos deben tener mínimo 1 salón, 1 dormitorio y 1 cama.
  * También necesito enviar el máximo de huéspedes que pueden ocupar el alojamiento, teniendo en cuenta que nunca podrán superar el total de camas. 

Yo, como propietario de un alojamiento necesito obtener los datos de mis alojamientos, teniendo en cuenta que sólo quiero obtener los que se han actualizado en fin de semana (sábado o domingo).
  
### Criterios de aceptación ###

* Este challenge tiene un sistema semi-automático de evaluación, para simplificarlo y que sea lo más corto y fácil posible, te proporcionamos los endpoint de la API REST que son necesarios para superarlo.
* También te proporcionamos un CSV con datos de muestra (data.csv), para que sea más rápido comprobar los métodos GET y puedas usarlo como fuente de datos para no tener que crear una BBDD.
* En los endpoints de creación/update no hace falta persistir los datos, es opcional. La respuestas si con obligatorias.

  * Cada propietario puede tener múltiples alojamientos, los propietarios se diferencian por su ID numérico, si ese ID no es válido, la API debe devolver un error.
  * Un propietario sólo puede obtener los alojamientos asociados a su ID.

## API

### POST /user/{id}/accommodations

Para crear un nuevo alojamiento. Recuerda que no hace falta persistir los datos, es opcional. La respuesta si es obligatoria

**Body** _required_ El alojamiento a crear.

**Content Type** `application/json`

Ejemplo:

```json
  {
    "id": 1 (int),
    "trade_name": "Lujoso apartamento en la playa" (string),
    "type": "FLAT" (string),
    "distribution": {
        "living_rooms": 1 (int),
        "bedrooms": 2 (int),
        "beds": 3 (int)
    },
    "max_guests": 3 (int)
  }
```

Respuestas:

* **201 OK** Cuando se crea correctamente.
* **400 Bad Request** Cuando hay algún error en el formato de la llamada, caebceras esperadas o no cumple alguna restricción.

```json
  {
    "id": 1 (int),
    "trade_name": "Lujoso apartamento en la playa" (string),
    "type": "FLAT" (string),
    "distribution": {
        "living_rooms": 1 (int),
        "bedrooms": 2 (int),
        "beds": 3 (int)
    },
    "max_guests": 3 (int),
    "updated_at": "2021-12-01" (string-date)
  }
```
* * *

### PUT /user/{id}/accommodations/{id}

Para actualizar un alojamiento existente. Recuerda que no hace falta persistir los datos, es opcional. La respuesta si es obligatoria

**Body** _required_ El alojamiento a actualizar.

**Content Type** `application/json`

Ejemplo:

```json
  {
    "id": 1 (int),
    "trade_name": "Lujoso apartamento en la playa" (string),
    "type": "FLAT" (string),
    "distribution": {
        "living_rooms": 1 (int),
        "bedrooms": 2 (int),
        "beds": 3 (int)
    },
    "max_guests": 3 (int)
  }
```

Respuestas:

* **200 OK** Cuando se actualiza correctamente.
* **400 Bad Request** Cuando hay algún error en el formato de la llamada, caebceras esperadas o no cumple alguna restricción.

```json
  {
    "id": 1 (int),
    "trade_name": "Lujoso apartamento en la playa" (string),
    "type": "FLAT" (string),
    "distribution": {
        "living_rooms": 1 (int),
        "bedrooms": 2 (int),
        "beds": 3 (int)
    },
    "max_guests": 3 (int),
    "updated_at": "2021-12-01" (string-date)
  }
```
* * *

### GET /user/{id}/accommodations

Para obtener todos los alojamientos de un propietario que cumplan las restricciones.

Respuestas:

* **200 OK** Cuando se obtiene correctamente.
* **400 Bad Request** Cuando hay algún error en el formato de la llamada, caebceras esperadas o no cumple alguna restricción.

```json
[  {
    "id": 1 (int),
    "trade_name": "Lujoso apartamento en la playa" (string),
    "type": "FLAT" (string),
    "distribution": {
        "living_rooms": 1 (int),
        "bedrooms": 2 (int),
        "beds": 3 (int)
    },
    "max_guests": 3 (int),
    "updated_at": "2021-12-01" (string-date)
  },
  {
    "id": 2 (int),
    "trade_name": "Villa en la montaña" (string),
    "type": "VILLA" (string),
    "distribution": {
        "living_rooms": 3 (int),
        "bedrooms": 8 (int),
        "beds": 12 (int)
    },
    "max_guests": 12 (int),
    "updated_at": "2021-12-01" (string-date)
  },
]
```
* * *

## Requisitos técnicos

* El challenge debe resolverse en PHP, se incluye un docker básico para agilizar su puesta en marcha.
* La persistencia de datos no forma parte del challenge, es opcional por lo que puedes elegir no hacerla, hacerlo en una BBDD, en el CSV (data.csv) que te proporcionamos...
* No es necesaria ninguna interfaz gráfica.
* El código debe ser ejecutable y no mostrar ninguna excepción, asegurate de que los endpoints son correctos, ya que la revisión es semi-automática.
* También hay una segunda batería de pruebas que calcula una puntuación sobre los requisitos, no es imprescindible pasarlas, pero añaden información a los revisores.
* Es importante que el código sea limpio, de calidad y siga buenas prácticas.


## Herramientas de trabajo

Ten en cuenta que si estás en Windows, debes añadir la extensión ".exe" delante de cada comando(docker-compose.exec ... por ejemplo) .
Hemos configurado un docker con todo lo necesario para la prueba. Para arrancar el proyecto sigue los siguientes pasos:

* Instalar tanto [Docker](https://docs.docker.com/engine/install/ubuntu/) como [Docker-compose](https://docs.docker.com/compose/install/).
* Una vez hecho esto, en a la carpeta "docker/" y copia el fichero ".unix.conf" o ".windows.conf" (depende del sistema operativo que uses) y pegalo con el nombre ".env" en esa misma carpeta.
* Creamos la red con "docker network inspect avaibook-network >/dev/null || docker network create avaibook-network"
* Ahora desde la carpeta del proyecto lanzamos "docker-compose --env-file docker/.env up -d --remove-orphans"
* Ya tenemos todo listo, ahora vamos desde el navegador a "http://127.0.7.14" si estamos en Linux o a "http://localhost:87" si estamos en Windows.

(*) Los usuarios experimentados de Linux, podéis hacer uso del "Makefile" subido al proyecto para realizar la instalación.

Puedes modificar el docker como quieras, siempre y cuando conserves tanto las IPs como los puertos de acceso que te proporcionamos

