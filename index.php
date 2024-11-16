<?php

$uploadFolder = 'uploaded-images';
$maxFiles = 50;
$maxSize = 50 * 1024 * 1024; // 50MB

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_FILES['imageFiles']['name'][0])) {
        $errors[] = "Errore: Nessun file selezionato.";
    } elseif (count($_FILES['imageFiles']['name']) > $maxFiles) {
        $errors[] = "Errore: Limite massimo di $maxFiles file raggiunto.";
    } else {
        $totalSize = array_sum($_FILES['imageFiles']['size']);

        if ($totalSize > $maxSize) {
            $errors[] = "Errore: Limite massimo di $maxSize byte raggiunto.";
        } else {
            $subFolder = date('Ymd_His') . '_' . generateRandomString(8);
            $targetFolder = $uploadFolder . '/' . $subFolder;

            if (!file_exists($targetFolder)) {
                mkdir($targetFolder, 0777, true);
            }

            $imageLinks = [];

            foreach ($_FILES['imageFiles']['tmp_name'] as $key => $tmpFilePath) {
                $name = $_FILES['imageFiles']['name'][$key];
                $newFilePath = $targetFolder . '/' . pathinfo($name, PATHINFO_FILENAME) . '.png';

                $imgInfo = getimagesize($tmpFilePath);
                if ($imgInfo === false) {
                    $errors[] = "Errore: Il file '$name' non è un'immagine valida.";
                    continue;
                }

                $img = imagecreatefromwebp($tmpFilePath);
                imagepng($img, $newFilePath);
                imagedestroy($img);

                $imageLinks[] = $newFilePath;
            }

            $expiryTime = time() + 900;
            file_put_contents($targetFolder . '/expiry.txt', $expiryTime);

            cleanupExpiredSubfolders($uploadFolder);

        }
    }
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function cleanupExpiredSubfolders($baseFolder) {
    $currentTimestamp = time();
    $subfolders = glob($baseFolder . '/*', GLOB_ONLYDIR);

    foreach ($subfolders as $subfolder) {
        $expiryFile = $subfolder . '/expiry.txt';

        if (file_exists($expiryFile) && (int)file_get_contents($expiryFile) < $currentTimestamp) {
            deleteDirectory($subfolder);
            #echo "<p>La sottocartella '$subfolder' è stata eliminata poiché il tempo massimo è scaduto.</p>";
        }
    }
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmdir($dir);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="https://icons.getbootstrap.com/assets/icons/images.svg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caricamento e Conversione Immagini</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <style>
        body {
            font-family: Poppins;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        form, .output {
            text-align: center;
        }

        h2 {
            color: #333;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            color: #555;
        }

        input[type="file"] {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px;
            margin: 5px 0 15px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .image-link {
            display: block;
            margin: 10px 0;
            color: #333;
            text-decoration: none;
        }

        .image-link:hover {
            text-decoration: underline;
        }

        .zip-link {
            display: block;
            margin: 20px 0;
            color: #fff;
            background-color: #4caf50;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
        }

        .zip-link:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<form action="" method="post" enctype="multipart/form-data">
    <h2>Caricamento e Conversione Immagini</h2>

    <?php
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p class='error-message'>$error</p>";
        }
    }

    if (isset($imageLinks) && count($imageLinks) > 0) {
        // Mostra i link per le immagini convertite
        foreach ($imageLinks as $link) {
            $fileName = basename($link);
            echo "<a href='$link' class='image-link' download='$fileName'>$fileName</a>";
        }

        // Crea un archivio ZIP e aggiungi le immagini convertite
        $zipFileName = $targetFolder . '/converted_images.zip';
        $zip = new ZipArchive();
        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($imageLinks as $link) {
                $zip->addFile($link, basename($link));
            }
            $zip->close();
            echo "<a href='$zipFileName' class='zip-link' download>Scarica tutte le immagini in un archivio ZIP</a>";
        } else {
            echo "<p>Errore durante la creazione dell'archivio ZIP</p>";
        }
    } 
    // Mostra il form di caricamento se non sono state caricate immagini
    ?>
    <label for="imageFiles">Seleziona immagini da caricare:</label>
    <input type="file" name="imageFiles[]" id="imageFiles" multiple accept="image/*">

    <br>

    <input type="submit" value="Carica Immagini">
    <?php
    ?>
</form>

</body>
</html>
