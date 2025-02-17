<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Prueba Tecnica VinkOs Rodrigo Hernandez Trejo

Se tiene el requerimiento de crear un proceso que integre la información de visitas de un sitio web contenida en archivos planos.

Estos archivos se encuentran en el servidor remoto (8.8.8.8) y se tiene acceso por sftp, contamos con las credenciales y el directorio donde se encuentran es /home/vinkOS/archivosVisitas

Los archivos tienen la extensión txt y la estructura del nombre es “report_”+consecutivo+.”txt”, no se tiene el número exacto de archivos que se generan por día.

El proceso deberá hacer lo siguiente:
Ir todos los días al directorio para buscar los archivos
Validar el layout de los archivos
Validar la información a cargar:
Email correcto
Fechas en formato dd/mm/yyyy HH:mm
Cargar la información en 3 tablas mysql
visitante
email, fechaPrimeraVisita, fechaUltimaVisita, visitasTotales, visitasAnioActual, visitasMesActual
estadística
email,jyv,Badmail,Baja,Fecha envío,Fecha open,Opens,Opens virales,Fecha click,Clicks,Clicks virales,Links,IPs,Navegadores,Plataformas
errores: registros con error

Borrar los archivos cargados en el origen
Hacer un backup de los archivos cargados en un zip y mandarlo al directorio local /home/etl/visitas/bckp
Llevar una bitácora de control de carga de los archivos para poder reportar mensualmente la cantidad de archivos y registros procesados.
Restricciones: 
- No se debe cargar un archivo más de una vez
- El proceso es responsable de la administración de los archivos (borrado en origen y backup en destino)
- El servidor donde se ejecuta el proceso se utilizará como almacenamiento del respaldo.
- En la tabla visitante, solo hay un registro por email, si no existe se agrega, si existe se actualizan los valores de fechaUltimaVisita, visitasTotales, visitasAnioActual, visitasMesActual.
- fechaUltimaVisita: la fecha en formato yyyymmdd de la última visita
- visitasTotales: Conteo del número de visitas desde su primera visita.
- visitasAnioActual: Conteo del número de visitas del año actual.
- visitasMesActual: Conteo del número de visitas del mes actual.

El objetivo del test, es detallar el flujo del proceso a construir, es decir, dónde habrá puntos de control, qué validaciones se utilizarán, cómo se administrarán y notificarán los errores, cómo y en qué orden se llenarán las tablas destino, si hay reglas, cuáles serán y dónde se aplicarán. 

Los archivos de ejemplo se encuentran en la carpeta denominada “txt”.

## Instalacion 

*Para fines del examen se subieron al repositorio los archivos .env y la carpeta storage*

Clonar el proyecto de Git y ejecutar los siguientes comandos

```
composer install
php artisan migrate
```

***Recuerda poner tus credenciales de la base de datos en el archivo .env***

## Proceso

En esta seccion se detalla y se explica el proceso para la resolucion del problema.

### Procesar información
Se creo una clase ubicada en: 
- App\Services\IntegrateService
  
Que se encarga de encapsular los metodos que se utilizaron para procesar los archivos uno por uno.

### Mapeo de datos
En la clase **App\Services\IntegrateService**::mapData($array) se creo un metodo privado que mapea los registros a un arreglo asociativo, asignando como nombre de las claves el nombre del campo en el que se guardara el valor en la base de datos.

### Validaciones
Se creo una clase ubicada en 
- App\Validators\VisitFileValidator

Que contiene los metodos para validar el **Layout** de los archivos y los **registros** que se van a guardar en la base de datos. Estas validaciones fueron:
- Layout: Se valido que las cabeceras del archivo coincidan con las cabeceras requeridas en la prueba.
- Registros: Se valido que el email fuera un email valido y que el formato de las fechas coincidiera con el solicitado.

*Para esta prueba los registros de fecha que son iguales a '-' los tome como un formato invalido, en el caso de que se quisiera tomar estos valores como nulos solo se debe agregar la validacion nullable en los campos que lo requiera*

### Guardado de Datos
*Esta seccion se hizo haciendo el menor numero de consultas posibles a la base de datos para mejorar el tiempo de ejecucion del proceso*

Para esta parte se hizo una capa intermedia entre el proceso y la base de datos, estas capas intermedias se encuentran en: 
- App\Repositories\StatisticsRepository

Esta clase se encarga de insertar un arreglo de registros en la tabla estadisticas, se penso en insertar el arreglo de todos los registros en unsa sola consuta para optimizar el tiempo de ejecucion del proceso

- App\Repositories\VisitorRepository

Esta clase se encarga de insertar los registros en la tabla de visitantes y en el caso de que ya exista el correo actualiza el registro

De igual forma se encapsulo la logica de negocios para las inserciones en la base de datos
- App\Services\StatisticsService

Constiene el metodo para darle formato a las fechas ya que el formato en mysql para los campos datetime es Y-m-d H:m:s, se pudieron guardar las fechas como string, pero en el caso de que se requiera hacer busquedas u operaciones en bd sobre fechas estas se entorpecerian

- App\Services\VisitorRepository

Constiene los metodos para mapear el registro e insertarlo y en el caso de que el registro con el correo ya exista calcular los campos de ultima visita(fecha envio) y visitasTotales, visitas del mes y año actual (Numero de ocurrencias del correo). *De igual forma se actualizo primer visita usando funciones de min y max para ultima visita evitando problemas en el caso de que los archivos no se carguen en el orden correcto*

### Manejo de errores
Para almacenar los errores se uso:
- App\Services\ErrorService

Que contiene los metodos para almacenar los errores en la tabla errores de la bd, esta clase guarda el archivo donde ocurrio el error, el tipo de error (De layout o de registro) y un json con el error, si es un error de registro te especifica la linea o No. de registro que tuvo el error

### Bitacora Mensual

Se penso en una tabla nueva en la base de datos que registra el numero de archivos procesados en el mes, asi como el numero de registros, solo se guarda un registro por mes y este se actualiza si ya existe.

### Backup

Se creo un metodo en:
- App\Services\IntegrateService

Que se encarga de copiar los archivos al destino y borrarlos del origen, en este caso corta todos los archivos que procesa, aunque estos fallen, si se requiriera se puede separar los archivos procesados correctamente de los que fallaron

### Comprimir Backup
Se creo un metodo en:
- App\Services\IntegrateService

Que se encarga de comprimir os archivos que se respaldaron, este zip se guarda con la fecha del dia en la que se hizo el respaldo (Ymd)

### Test
Para hacer pruebas locales se creo un test unitario, que toma los archivos que se incluyeron en la carpeta de drive, este test dse puede correr con: 
```
php artisan test
```

***Asegurate de guardar los archivos que quieres testear en storage/app/vinkOs/archivosVisitas***

### SFTP
Para cargar los archivos desde un servidor por medio de sftp se configuro el filsystem.php para que funcione con sftp, ese requiere las variables de entorno:
- SFTP_HOST=8.8.8.8
- SFTP_USERNAME=  
- SFTP_PASSWORD=  
- SFTP_ROOT=/home

***Asegurate de poner las credencial del servidor para poder acceder por sftp***

### Ejecucion diaria 
Para esta seccion se creo un comando de artisan: 
```
php artisan integrate:visits
```
Este comando va a tomar todos los archivos del servidor en el path solicitado y hara el proceso del integracion de datos de visitas.

De igual forma este comando se registro en Routes/commands.php para que se ejecute cada dia a las 23:59, para que esto funcione de forma local se debe tener corriendo el comando.
```
php artisan schedule:work
```

Para que corra en un servidor se debe hacer un archivo bash que ejecute el comando 
```
php artisan schedule:run
```




