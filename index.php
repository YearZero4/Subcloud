<?php
function getIPAddress() {
$ip = $_SERVER['REMOTE_ADDR'];
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
$ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
return $ip;
}

$ip = getIPAddress();
$file_path = "glz.txt";
$last_cleanup_file = "last_cleanup.txt";
$upload_log_file = "xxxsajx.txt"; 
$ip_count = 0;

if (file_exists($last_cleanup_file)) {
$last_cleanup = file_get_contents($last_cleanup_file);
$last_cleanup_date = strtotime($last_cleanup);
$current_date = time();
if (($current_date - $last_cleanup_date) >= 86400) {
if (file_exists($upload_log_file)) {
unlink($upload_log_file); 
}
file_put_contents($last_cleanup_file, date("Y-m-d H:i:s"));
}
} else {
file_put_contents($last_cleanup_file, date("Y-m-d H:i:s"));
if (file_exists($upload_log_file)) {
unlink($upload_log_file); 
}
}

if (file_exists($file_path)) {
$lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if ($lines !== false) {
foreach ($lines as $line) {
if ($line === $ip) {
$ip_count++;
}
}
}
}

if ($ip_count >= 20) {
die("Acceso denegado. Su dirección IP ha sido bloqueada.");
}

$file = fopen($file_path, "a");
fwrite($file, "$ip
");
fclose($file);

$maxFileSize = 60 * 1024 * 1024;
$maxFolders = 16;
$maxDirSize = 60 * 1024 * 1024;
$baseDir = __DIR__;
$urlArray = [];

function containsRestrictedWords($filePath, $restrictedFile) {
$restrictedWords = file($restrictedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$fileContent = file_get_contents($filePath);
$lowerFileContent = strtolower($fileContent);

foreach ($restrictedWords as $word) {
if (strpos($lowerFileContent, strtolower(trim($word))) !== false) {
return true;
}
}
if (preg_match('/\d+/', $lowerFileContent)) {
return true;
}
return false;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
if (isset($_FILES['files'])) {
$files = $_FILES['files'];
$randomDirName = sprintf('%07d', mt_rand(0, 999999));
$uploadDir = $baseDir . '/' . $randomDirName . '/';

if (!mkdir($uploadDir, 0755, true)) {
die('Error al crear el directorio.');
}

$currentDirSize = 0;
foreach (glob("$uploadDir/*") as $file) {
$currentDirSize += filesize($file);
}

if ($currentDirSize > $maxDirSize) {
rmdir($uploadDir);
die('El tamaño total de la carpeta excede el límite permitido de 60 MB.');
}

$dirs = array_filter(glob($baseDir . '/*'), 'is_dir');
if (count($dirs) >= $maxFolders) {
usort($dirs, function($a, $b) {
return filemtime($a) - filemtime($b);
});
$oldestDir = array_shift($dirs);
if ($oldestDir !== false) {
array_map('unlink', glob("$oldestDir/*"));
rmdir($oldestDir);
}
}
$totalSizeAfterUpload = $currentDirSize;
foreach ($files['name'] as $key => $name) {
if ($files['error'][$key] !== UPLOAD_ERR_OK) {
echo "Error al subir el archivo: " . htmlspecialchars($name) . "<br>";
continue;
}
if ($files['size'][$key] > $maxFileSize) {
echo "El archivo " . htmlspecialchars($name) . " supera el tamaño máximo permitido de 60 MB.<br>";
continue;
}
if ($totalSizeAfterUpload + $files['size'][$key] > $maxDirSize) {
echo "No se puede subir el archivo " . htmlspecialchars($name) . " porque excede el límite de tamaño del directorio.<br>";
continue;
}

$uploadFile = $uploadDir . basename($name);
if (move_uploaded_file($files['tmp_name'][$key], $uploadFile)) {
$storedNames = file_exists($upload_log_file) ? file($upload_log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
$storedNames = array_map('strtolower', $storedNames);

if (in_array(strtolower($name), $storedNames)) {
unlink($uploadFile);
$xq = "El archivo " . htmlspecialchars($name) . " ya ha sido subido y no se puede volver a subir.<br>";
} else {
file_put_contents($upload_log_file, "$randomDirName/$name
$randomDirName
", FILE_APPEND);
if (in_array(strtolower(pathinfo($name, PATHINFO_EXTENSION)), ['html', 'php', 'js', 'css', 'py']) && containsRestrictedWords($uploadFile, 'restricTED015.txt')) {
unlink($uploadFile);
$xq = "El archivo " . htmlspecialchars($name) . " no se puede subir.<br>";
} else {
$urlArray[] = "http://ghostcode.unaux.com/$randomDirName/" . rawurlencode(basename($name));
$totalSizeAfterUpload += $files['size'][$key];
}
}
} else {
echo 'Error al mover el archivo ' . htmlspecialchars($name) . ' a la carpeta de destino.<br>';
}
}
} else {
die('No se ha subido ningún archivo.');
}
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="./fhjdfhd.ico"/>
<meta charset="UTF-8">
<title>Subcloud</title>
<link rel="stylesheet" href="./uwaiosaj.css">
<script>
function copyToClipboard() {
var textarea = document.getElementById("url-textarea");
textarea.select();
document.execCommand("copy");
alert("URLs copiadas al portapapeles");
}

document.addEventListener('DOMContentLoaded', (event) => {
const dropArea = document.getElementById("drop-area");
const fileInput = document.getElementById("fileElem");
const urlTextArea = document.getElementById("url-textarea");
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
dropArea.addEventListener(eventName, preventDefaults, false);
document.body.addEventListener(eventName, preventDefaults, false);
});
['dragenter', 'dragover'].forEach(eventName => {
dropArea.addEventListener(eventName, highlight, false);
});
['dragleave', 'drop'].forEach(eventName => {
dropArea.addEventListener(eventName, unhighlight, false);
});
dropArea.addEventListener('drop', handleDrop, false);

function preventDefaults(e) {
e.preventDefault();
e.stopPropagation();
}

function highlight() {
dropArea.classList.add("highlight");
}

function unhighlight() {
dropArea.classList.remove("highlight");
}

function handleDrop(e) {
const dt = e.dataTransfer;
const files = dt.files;
fileInput.files = files; 
const urls = Array.from(files).map(file => URL.createObjectURL(file));

const uploadDirName = `${Math.random().toString(36).substring(2, 9)}`;
const generatedURLs = Array.from(files).map(file => `http://ghostcode.unaux.com/${uploadDirName}/${file.name}`);
urlTextArea.value = generatedURLs.join('\n');
}
});
</script>
<style>
#drop-area {
border: 2px dashed #007bff;
border-radius: 20px;
padding: 20px;
text-align: center;
}
#drop-area.highlight {
border-color: #0056b3;
}
</style>
</head>
<body>
<center>
<h1 class="t">Subcloud</h1>
<form action="" method="post" enctype="multipart/form-data" id="upload-form">
<div id="drop-area">
<p>Arrastra y suelta archivos aquí o haz clic para seleccionar archivos.</p>
<input type="file" name="files[]" id="fileElem" multiple style="display:none;" required>
<label for="fileElem" style="cursor: pointer; background-color: rgba(0, 0, 255, 0.3); color: white; padding: 6px; border-radius: 6px;">Selecciona archivos</label>
</div>
<input class="subir" type="submit" value="Subir archivos">
<?php 
if (!empty($xq)) {
echo "<p>$xq";
}

if (!empty($urlArray)): 
?>
<div>
<?php
$acortadas = [];
$apiUrl = 'https://acut0.onrender.com/acortar';

foreach ($urlArray as $url) {
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['url' => $url]));
curl_setopt($ch, CURLOPT_TIMEOUT, 300); 

$response = curl_exec($ch);
if (curl_errno($ch)) {
$errorMsg = 'Error cURL: ' . curl_error($ch);
$acortadas[] = "Error para $url: $errorMsg";
} else {
curl_close($ch);

$responseData = json_decode($response, true);

if (isset($responseData['acortada'])) {
$acortadas[] = $responseData['acortada'];
} else {
$error = $responseData['error'] ?? 'Error desconocido';
$acortadas[] = "Error para $url: $error";
}
}
}
$urlsAcortadasString = implode("\n", $acortadas);
?>
<textarea rows="4" cols="50" readonly id="url-textarea"><?php echo htmlspecialchars($urlsAcortadasString); ?></textarea>
</div>
<button type="button" onclick="copyToClipboard()" class="copiar">Copiar ahora</button>
<?php endif; ?>
</form>
</center>
</body>
</html>
