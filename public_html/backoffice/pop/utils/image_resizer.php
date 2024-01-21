<?php
header('Cache-Control: no-cache');
header('Content-Type: text/html');


class ImageResizer {
    private $extension;

    public function __construct($fileName) {
        $this->extension = $this->getExtension($fileName);
    }

    private function getExtension($fileName) {
        $extension = explode(".", $fileName);
        return strtolower($extension[count($extension) - 1]);
    }

    public function openResizeAndPrint($fileName, $width = 0, $height = 0, $imageQuality = "100", $loadFromCache = FALSE) {
        $imagePath = $fileName;
        if ($width != 0 && $height != 0) {
            $hashAttributes = $fileName . $width . $height . $imageQuality;
            $fileHash = hash("md5", $hashAttributes);
            $cachePath = "../uploads/cache/" . $fileHash . "." . $this->extension;
            if ($loadFromCache && file_exists($cachePath) && is_file($cachePath)) {
                $image = $this->openImage($cachePath);
                $imagePath = $cachePath;
            }
            else {
                $image = $this->openImage($fileName);
                $image = $this->resizeImage($image, $width, $height);
                if (!is_dir($cachePath) && is_writable(dirname($cachePath))) {
                    $this->saveImage($image, $cachePath);
                }
            }
        }
        else {
            $image = $this->openImage($fileName);
        }

        $this->printImage($image);
        exit;
    }

    private function openImage($fileName) {
        $image = FALSE;
        if (file_exists($fileName) && !is_dir($fileName) && is_readable($fileName)) {
            $type = mime_content_type($fileName);
            if (stripos($type, "jpeg") !== FALSE) {
                $this->extension = "jpeg";
            }
            elseif (stripos($type, "png") !== FALSE) {
                $this->extension = "png";
            }
            elseif (stripos($type, "gif") !== FALSE) {
                $this->extension = "gif";
            }

        }
        switch ($this->extension) {
            case 'jpg':
            case 'jpeg':
                header('Content-Type: image/jpeg');
                $image = @imagecreatefromjpeg($fileName);
                break;
            case 'gif':
                header('Content-Type: image/gif');
                $image = @imagecreatefromgif($fileName);
                if ($image != FALSE) {
                    imagealphablending($image, FALSE);
                    imagesavealpha($image, TRUE);
                }
                break;
            case 'png':
                header('Content-Type: image/png');
                $image = @imagecreatefrompng($fileName);
                if ($image != FALSE) {
                    imagealphablending($image, FALSE);
                    imagesavealpha($image, TRUE);
                }
                break;
        }
        $this->dieOnError($image);
        return $image;
    }

    public function dieOnError($image, $text = 'AVISO:OCORREU UM ERRO AO TENTAR CARREGAR A IMAGEM') {
        if ($image === FALSE) {
            $image = imagecreate(550, 550);
            imagecolorallocate($image, 255, 255, 255);
            $textcolor = imagecolorallocate($image, 0, 0, 255);
            imagettftext($image, 16, 45, 24, 499, $textcolor, "./comicsans.ttf", $text);
            imagettftext($image, 16, 45, 25, 500, $textcolor, "./comicsans.ttf", $text);
            imagettftext($image, 16, 45, 26, 501, $textcolor, "./comicsans.ttf", $text);
            header('Cache-Control: no-cache', TRUE);
            header('Content-Type: image/png', TRUE);
            $this->extension = "png";
            $this->printImage($image);
            exit;
        }
    }

    private function printImage($image, $imageQuality = "100") {
        return $this->processImage($image, NULL, $imageQuality);
    }

    private function processImage($image, $savePath = NULL, $imageQuality = "100") {
        switch ($this->extension) {
            case 'jpg':
            case 'jpeg':
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($image, $savePath, $imageQuality);
                }
                break;
            case 'gif':
                if (imagetypes() & IMG_GIF) {
                    imagegif($image, $savePath);
                }
                break;
            case 'png':
                // *** Scale quality from 0-100 to 0-9
                $scaleQuality = round(($imageQuality / 100) * 9);
                // *** Invert quality setting as 0 is best, not 9
                $invertScaleQuality = 9 - $scaleQuality;
                if (imagetypes() & IMG_PNG) {
                    imagepng($image, $savePath, $invertScaleQuality);
                }
                break;
            default:
                return FALSE;
                break;
        }
        return TRUE;
    }

    private function resizeImage($image, $newWidth, $newHeight) {
        $oldWidth = imagesx($image);
        $oldHeight = imagesy($image);
        $d = $this->getDimensions($newWidth, $newHeight, $oldWidth, $oldHeight);
        $imageResized = imagecreatetruecolor($d['newWidth'], $d['newHeight']);
        if ($this->extension == "gif" || $this->extension == "png") {
            imagecolortransparent($imageResized, imagecolorallocatealpha($imageResized, 0, 0, 0, 127));
            imagealphablending($imageResized, FALSE);
            imagesavealpha($imageResized, TRUE);
        }
        imagecopyresampled($imageResized, $image, 0, 0, 0, 0, $d['newWidth'], $d['newHeight'], $oldWidth, $oldHeight);
        return $imageResized;
    }

    private function getDimensions($newWidth, $newHeight, $oldWidth, $oldHeight) {
        if ($oldHeight < $oldWidth) {//(landscape)
            $dimensions = [
                'newWidth'  => $newWidth,
                'newHeight' => $newWidth * ($oldHeight / $oldWidth)
            ];
        }
        elseif ($oldHeight > $oldWidth) { //(portrait)
            $dimensions = [
                'newWidth'  => $newHeight * ($oldWidth / $oldHeight),
                'newHeight' => $newHeight
            ];
        }
        else { // Square
            if ($newHeight < $newWidth) {
                $newSize = $newWidth;
            }
            else {
                $newSize = $newHeight;
            }
            $dimensions = [
                'newWidth'  => $newSize,
                'newHeight' => $newSize
            ];
        }
        return $dimensions;
    }

    private function saveImage($image, $savePath, $imageQuality = "100") {
        return $this->processImage($image, $savePath, $imageQuality);
    }
}

if (!isset($_GET["w"], $_GET["h"], $_GET["url"])) {
    $fileName = "/home2/pop/public_html/backoffice/pop/uploads/projeto/default.jpg";
    $width = "0";
    $height = "0";
}
else {
    $fileName = base64_decode($_GET['url']);
    $fileName = str_ireplace(["http://pop.nerdweb.dyndns.org/", "http://nerdweb.popflow.com.br/"], "/home2/pop/public_html/", $fileName);
    $width = $_GET['w'];
    $height = $_GET["h"];
}

$resizeObj = new ImageResizer($fileName);
$resizeObj->openResizeAndPrint($fileName, $width, $height, 100, TRUE);
/*
 *  Javascript pra tentar descobrir o tamanho da imagem em tempo de carregamento
 * window.addEventListener('load', function(){
        var allimages= document.getElementsByTagName('img');
        for (var i=0; i<allimages.length; i++) {
            console.log(allimages[i]);
            if (allimages[i].getAttribute('data-src')) {
                allimages[i].setAttribute('src', allimages[i].getAttribute('data-src'));
            }
        }

        var allbackground = document.getElementsByClassName('tp-bg');
        for (var i=0; i<allbackground.length; i++) {
            if (typeof allbackground[i].clientHeight  !== 'undefined') {
                console.log(allbackground[i]);
                console.log(allbackground[i].clientHeight);
            }
        }
    }, false)window.addEventListener('load', function(){
        var allimages= document.getElementsByTagName('img');
        for (var i=0; i<allimages.length; i++) {
            console.log(allimages[i]);
            if (allimages[i].getAttribute('data-src')) {
                allimages[i].setAttribute('src', allimages[i].getAttribute('data-src'));
            }
        }

        var allbackground = document.getElementsByClassName('tp-bg');
        for (var i=0; i<allbackground.length; i++) {
            if (typeof allbackground[i].clientHeight  !== 'undefined') {
                console.log(allbackground[i]);
                console.log(allbackground[i].clientHeight);
            }
        }
    }, false)
 */
