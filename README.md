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
Para esta parte se hizo una capa intermedia entre la proceso y la base de datos, esta capa intermedia se encuentra en 
- App\Repositories

## Instalacion 

*Para fines del examen se subieron al repositorio los archivos .env y la carpeta storage*

Clonar el proyecto de Git y ejecutar los siguientes comandos

```bash
composer install
php artisan migrate


