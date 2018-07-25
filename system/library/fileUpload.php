<?php
include_once ('SimpleImage.php');

class fileUpload extends SimpleImage {
    private $files;
    private $path;
    private $ratio = array();

    public function __construct() {

    }

    public function setter($files, $path) {
        $this->files = $files;
        $this->path = $path;
    }

    public function setRatio($width, $height = 0) {
        if($height === 0)
            $height = $width;

        $this->ratio = array(
            'width' => $width,
            'height' => $height
        );
    }

    public function process_multiple() {
        foreach ($this->files as $file) {
            $this->upload($file);
        }
    }

    public function upload($file = null) {
        if($file === null) $file = $this->files;
        $filename = basename($file["name"]);
        $target_file = $this->path . $filename;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        $check = getimagesize($file["tmp_name"]);
        if($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            $filename = md5(date('now')) . '.' . $imageFileType;
            $target_file = $this->path . $filename;
        }
        // Check file size
        if ($file["size"] > $this->getMaximumFileUploadSize()) {
            throw new Exception("Sorry, your file is too large.");
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            throw new Exception("Sorry, your file was not uploaded.");
        // if everything is ok, try to upload file
        } else {
            $this->load($file['tmp_name']);
            if(!empty($this->ratio)){
                $this->resizeToHeight($this->ratio['height']);
            }
            if ($this->save($target_file)) {
                return $filename;
            } else {
                throw new Exception("Sorry, there was an error uploading your file.");
            }
        }
    }

    private function getMaximumFileUploadSize() {
        return min($this->convertPHPSizeToBytes(ini_get('post_max_size')), $this->convertPHPSizeToBytes(ini_get('upload_max_filesize')));
    }

    /**
     * This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
     *
     * @param string $sSize
     * @return integer The value in bytes
     */
    private function convertPHPSizeToBytes($sSize) {
        //
        $sSuffix = strtoupper(substr($sSize, -1));
        if (!in_array($sSuffix,array('P','T','G','M','K'))){
            return (int)$sSize;
        }
        $iValue = substr($sSize, 0, -1);
        switch ($sSuffix) {
            case 'P':
                $iValue *= 1024;
            // Fallthrough intended
            case 'T':
                $iValue *= 1024;
            // Fallthrough intended
            case 'G':
                $iValue *= 1024;
            // Fallthrough intended
            case 'M':
                $iValue *= 1024;
            // Fallthrough intended
            case 'K':
                $iValue *= 1024;
                break;
        }
        return (int)$iValue;
    }
}