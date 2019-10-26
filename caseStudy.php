<?php
set_time_limit(120);
require_once 'google-api-php-client-2.4.0/vendor/autoload.php';
require_once 'screenshotmachine.php';



$arrayPages = array("https://ifunded.de/de/","https://www.propertypartner.co/","https://propertymoose.co.uk/","https://www.homegrown.co.uk/","https://www.realtymogul.com/");
$arrayNames = array("iFunded", "PropertyPartner", "PropertyMoose", "HomeGrown", "RealityMogul" );

$customer_key = "c71947";
$secret_phrase = ""; //leave secret phrase empty, if not needed

$machine = new ScreenshotMachine($customer_key, $secret_phrase);
for ($i=0; $i < count($arrayPages) ; $i++) {

$options['url'] = $arrayPages[$i];

// all next parameters are optional, see our website screenshot API guide for more details
$options['dimension'] = "1920x1080";  // or "1366xfull" for full length screenshot
$options['device'] = "desktop";
$options['format'] = "jpg";
$options['cacheLimit'] = "0";
$options['delay'] = "200";
$options['zoom'] = "100";

$api_url = $machine->generate_screenshot_api_url($options);

//or save screenshot as an image
$output_file = 'ID_' . $arrayNames[$i] . '.jpg';
file_put_contents($output_file, file_get_contents($api_url));
echo 'Screenshot saved as ' . $output_file . PHP_EOL . '<br>';
}

putenv('GOOGLE_APPLICATION_CREDENTIALS=credenciales.json');

$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setScopes(['https://www.googleapis.com/auth/drive.file']);

for($i = 0; $i< count($arrayNames);$i++){
try{
    //Instanciamos el servicio
    $service = new Google_Service_Drive($client);

    //ruta al archivo
    $file_path = 'ID_' . $arrayNames[$i] . '.jpg';

    //instancia del archivo
    $file = new Google_Service_Drive_DriveFile();
    //id de la carpeta a la que hemos dado permiso
    $file->setName("ID_" . $arrayNames[$i] . ".jpg");
    $file->setParents(array("1WOM_bCJz0GltL1BJdFAflZGvTEBS9RoB"));
    $file->setDescription("File Uploaded for Data4you");
    echo "<p> Image <strong>" . $file->getName() . "</strong> uploaded to Google Drive</p>";

    $result = $service->files->create(
        $file,
        array(
            'data' => file_get_contents($file_path),
            'mimeType' => 'image/jpg',
            'uploadType' => 'media'
        )
        );

}catch(Google_Service_Exception $gs){
    $m = json_decode($gs->getMessage());
    echo $m->error->message;

}catch(Exception $e){
    echo $e->getMessage();
}
}
