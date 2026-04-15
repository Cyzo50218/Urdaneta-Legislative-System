<?php
header("Content-Type: application/json");

if (!isset($_FILES["attachments"])) {
    echo json_encode(["ok" => false, "message" => "No files uploaded"]);
    exit;
}

$trackingNo = preg_replace("/[^A-Za-z0-9\-]/", "_", $_POST["trackingNo"] ?? "UNKNOWN");
$baseDir = __DIR__ . "/../uploads/attachments/";
$targetDir = $baseDir . $trackingNo . "/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$savedFiles = [];

foreach ($_FILES["attachments"]["tmp_name"] as $i => $tmpName) {
    $name = $_FILES["attachments"]["name"][$i];
    $safeName = preg_replace("/[^A-Za-z0-9_\.-]/", "_", $name);
    $destination = $targetDir . $safeName;

    if (move_uploaded_file($tmpName, $destination)) {
        // Return relative path so JS can preview
        $savedFiles[] = "/uploads/attachments/$trackingNo/$safeName";
    }
}

echo json_encode(["ok" => true, "savedFiles" => $savedFiles]);
