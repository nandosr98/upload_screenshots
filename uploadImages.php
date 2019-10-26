<?php
require_once 'google-api-php-client-2.4.0/vendor/autoload.php';


//Configurar variable de entorno

putenv('GOOGLE_APPLICATION_CREDENTIALS=credenciales.json');

$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setScopes(['https://www.googleapis.com/auth/drive.file']);
try{
    //Instanciamos el servicio
    $service = new Google_Service_Drive($client);

    //ruta al archivo
    $file_path = 'ejemplo.png';

    //instancia del archivo
    $file = new Google_Service_Drive_DriveFile();
    $file->setName("ejemplo_drive.png");
    //id de la carpeta a la que hemos dado permiso
    $file->setParents(array("1tlh5ay7i0EMR3a5I_TuSD1Ri1xXN0L_t"));
    $file->setDescription("Archivo subido desde PHP");
    

    $result = $service->files->create(
        $file,
        array(
            'data' => file_get_contents($file_path),
            'mimeType' => 'image/png',
            'uploadType' => 'media'
        )
        );
}catch(Google_Service_Exception $gs){
    $m = json_decode($gs->getMessage());
    echo $m->error->message;

}catch(Exception $e){
    echo $e->getMessage();
}