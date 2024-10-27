<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Prueba Tecnica VinkOs

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


- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
