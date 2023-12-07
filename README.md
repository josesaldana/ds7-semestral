# Semestral de Desarrollo de Software 7
Aplicaci&oacute;n web en PHP para gestionar viajes de un club n&aacute;utico. Necesita conexi&oacute;n a internet.

## Pasos de Instalaci&oacute;n

### Utilizando Apache Web Server
[Por Hacer]

### Utilizando servidor web integrado en linea de comando de PHP (PHP CLI)
**Pre-requisitos**

- Asegurarse que el ejecutable php.exe est&eacute; en la lista de comandos del sistema (variable PATH). Si la instalaci&oacute;n se ha hecho con XAMPP en `c:\xampp`, este ejecutable deber&iacute;a estar en `c:\xampp\php`. Agregar esta ruta en la variable de sistema PATH. 

- Asegurarse de que la extensi&oacute;n mysqli.so est&eacute; habilitada para PHP en modo l&iacute;nea de comandos (PHP CLI). Se puede activar en `c:\xampp\php\php.ini`. Podr&iacute; estar activado por defecto en XAMPP.

**Pasos**
1. Abrir una consola de linea de comandos (CMD)
2. Ingresar al directorio donde ha sido decompreso la aplicaci&oacute;n utilizando la l&iacute;nea de comando (comando `cd`).
3. Ejecutar el comando: `php -S localhost:8080 -t public/`
4. Acceder a http://localhost:8080 utilizando un navegador web.  La aplicaci&oacute;n deber&iacute;a estar accesible a este punto.

### Contacto
Si hay alg&uacute;n inconveniente, contactarme a jose.saldana2@utp.ac.pa.