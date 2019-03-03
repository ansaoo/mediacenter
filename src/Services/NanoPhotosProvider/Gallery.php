<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 11/02/19
 * Time: 22:06
 */

namespace App\Services\NanoPhotosProvider;


use App\Entity\Picture;
use App\Services\NanoPhotosProvider\Core\Encoding;
use App\Services\NanoPhotosProvider\Entity\GalleryData;
use App\Services\NanoPhotosProvider\Entity\Item;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class Gallery
{
    protected $config   = array();
    protected $data;
    protected $albumID;
    protected $album;
    protected $tn_size  = array();
    protected $ctn_urls = array();
    protected $ctn_w    = array();
    protected $ctn_h    = array();
    /**
     * @var Item
     */
    protected $currentItem;
    protected $downloadPath;

    const CONFIG_FILE    = __DIR__.'/Core/provider.cfg';

    public function __construct(RouterInterface $router)
    {
        $this->downloadPath = $router->generate("download") .'/';
        $this->setConfig(self::CONFIG_FILE);
    }

    public function retrieve(Request $request, array $pictures)
    {
        // retrieve the album ID in the URL
        $this->album   = '/';
        $this->albumID = '';
        if ($request->get('albumID')) {
            $this->albumID = rawurldecode($request->get('albumID'));
        }
        if (!$this->albumID == '0' && $this->albumID != '' && $this->albumID != null) {
            $this->album = '/' . $this->CustomDecode($this->albumID) . '/';
        } else {
            $this->albumID = '0';
        }

        // thumbnail responsive sizes
        $this->tn_size['wxs']   = strtolower($this->CheckThumbnailSize( $request->get('wxs') ));
        $this->tn_size['hxs']   = strtolower($this->CheckThumbnailSize( $request->get('hxs') ));
        $this->tn_size['wsm']   = strtolower($this->CheckThumbnailSize( $request->get('wsm') ));
        $this->tn_size['hsm']   = strtolower($this->CheckThumbnailSize( $request->get('hsm') ));
        $this->tn_size['wme']   = strtolower($this->CheckThumbnailSize( $request->get('wme') ));
        $this->tn_size['hme']   = strtolower($this->CheckThumbnailSize( $request->get('hme') ));
        $this->tn_size['wla']   = strtolower($this->CheckThumbnailSize( $request->get('wla') ));
        $this->tn_size['hla']   = strtolower($this->CheckThumbnailSize( $request->get('hla') ));
        $this->tn_size['wxl']   = strtolower($this->CheckThumbnailSize( $request->get('wxl') ));
        $this->tn_size['hxl']   = strtolower($this->CheckThumbnailSize( $request->get('hxl') ));

        $this->data           = new GalleryData();
        $this->data->fullDir  = ($this->config['contentFolder']) . ($this->album);

        $lstImages = array();
        $lstAlbums = array();

        $dh = opendir($this->data->fullDir);

        $min_count = 0;
        // This logic is not yet correct, but will make sure all images are loaded when the parameter is not set
        if ($request->get('perPage')) {
            $max_count = $request->get('perPage');
        } else {
            $max_count = INF;
        }

        $img_count = 0;

        // loop the folder to retrieve images and albums
//        if ($dh != false) {
//            while (false !== ($filename = readdir($dh))) {
//                if (is_file($this->data->fullDir . $filename) ) {
//                    // it's a file
//                    // $img_count++;
//                    if ($img_count >= $min_count && $img_count < $max_count &&
//                        $filename != '.' &&
//                        $filename != '..' &&
//                        $filename != '_thumbnails' &&
//                        preg_match("/\.(" . $this->config['fileExtensions'] . ")*$/i", $filename) &&
//                        strpos($filename, $this->config['ignoreDetector']) == false )
//                    {
//                        $lstImages[] = $this->PrepareData($filename, 'IMAGE');
//                    }
//                }
//                else {
//                    // it's a folder
//                    //$files = glob($this->data->fullDir . $filename."/*.{".str_replace("|",",",$this->config['fileExtensions'])."}", GLOB_BRACE);    // to check if folder contains images - warning - glob is not supported by all platforms
//                    $files = preg_grep('~\.('.$this->config['fileExtensions'].')$~', scandir($this->data->fullDir . $filename));     // to check if folder contains images
//                    if ($filename != '.' &&
//                        $filename != '..' &&
//                        $filename != '_thumbnails' &&
//                        strpos($filename, $this->config['ignoreDetector']) == false &&
//                        true ) //!empty($files) )
//                    {
//                        $album = $this->PrepareData($filename, 'ALBUM');
//                        if ($album != null && !empty($album)) {
//                            $lstAlbums[] = $album;
//                        }
//                    }
//                }
//            }
//            closedir($dh);
//        }

        $lstImages = array_map(function (Picture $picture) {
            return $this->PrepareData($picture->getFilename(), "IMAGE")
                ->toObject();
        }, $pictures);

        // sort data
        // usort($lstAlbums, array('galleryJSON','Compare'));
        // usort($lstImages, array('galleryJSON','Compare'));

        return array('nano_status' => 'ok', 'nano_message' => '', 'album_content' => array_merge($lstAlbums, $lstImages));
    }

    /**
     * CHECK IF THUMBNAIL SIZE IS ALLOWED (if not allowed: send error message and exit)
     *
     * @param string $size
     * @return array|string
     */
    protected function CheckThumbnailSize( $size )
    {
        if( !array_key_exists("allowedSizeValues",$this->config['thumbnails']) || $this->config['thumbnails']['allowedSizeValues'] == "" ) {
            // no size restriction
            return $size;
        }

        $s=explode('|', $this->config['thumbnails']['allowedSizeValues']);
        if( is_array($s) ) {
            foreach($s as $one) {
                $one = trim($one);
                if( $one == $size ) {
                    return $size;
                }
            }
        }

        return array( 'nano_status' => 'error', 'nano_message' => 'requested thumbnail size not allowed: '. $size );
    }


    protected function setConfig($filePath)
    {
        $config = parse_ini_file($filePath, true);

        // general settings
        $this->config['contentFolder']          = $config['config']['contentFolder'];
        $this->config['fileExtensions']         = $config['config']['fileExtensions'];
        $this->config['sortOrder']              = strtoupper($config['config']['sortOrder']);
        $this->config['titleDescSeparator']     = $config['config']['titleDescSeparator'];
        $this->config['albumCoverDetector']     = $config['config']['albumCoverDetector'];
        $this->config['ignoreDetector']         = strtoupper($config['config']['ignoreDetector']);

        // memory usage
        if( $config['memory']['unlimited'] == true ) {
            ini_set('memory_limit', '-1');
        }

        // images
        $this->config['images']['maxSize'] = 0;
        $ms = $config['images']['maxSize'];
        if( ctype_digit(strval($ms)) ){
            $this->config['images']['maxSize'] = $ms;
        }
        $iq = $config['images']['jpegQuality'];
        $this->config['images']['jpegQuality'] = 85; // default jpeg quality
        if( ctype_digit(strval($iq)) ){
            $this->config['images']['jpegQuality'] = $iq;
        }

        // thumbnails
        $tq = $config['thumbnails']['jpegQuality'];
        $this->config['thumbnails']['jpegQuality'] = 85; // default jpeg quality
        if( ctype_digit(strval($tq)) ){
            $this->config['thumbnails']['jpegQuality'] = $tq;
        }

        $tbq = $config['thumbnails']['blurredImageQuality'];
        $this->config['thumbnails']['blurredImageQuality'] = 3; // default blurred image quality
        if( ctype_digit(strval($tbq)) ){
            $this->config['thumbnails']['blurredImageQuality'] = $tbq;
        }

        $asv = trim($config['thumbnails']['allowedSizeValues']);
        if( $asv != '' ) {
            $this->config['thumbnails']['allowedSizeValues']=$asv;
        }



        // security
        $this->config['security']['allowOrigins'] = $config['security']['allowOrigins'];
    }

    /**
     * RETRIEVE THE COVER IMAGE (THUMBNAIL) OF ONE ALBUM (FOLDER)
     *
     * @param string $baseFolder
     * @return string
     */
    protected function GetAlbumCover($baseFolder)
    {

        // look for cover image
        $files = glob($baseFolder . '/' . $this->config['albumCoverDetector'] . '*.*');
        if (count($files) > 0) {
            $i = basename($files[0]);
            if (preg_match("/\.(" . $this->config['fileExtensions'] . ")*$/i", $i)) {
                $this->GetThumbnail2( $baseFolder, $i);
                return $baseFolder . $i;
            }
        }

        // no cover image found --> use the first image for the cover
        $i = $this->GetFirstImageFolder($baseFolder);
        if ($i != '') {
            $this->GetThumbnail2( $baseFolder, $i);
            return $baseFolder . $i;
        }

        return '';
    }

    /**
     * Retrieve the first image of one folder --> ALBUM THUMBNAIL
     *
     * @param string $folder
     * @return string
     */
    protected function GetFirstImageFolder($folder)
    {
        $image = '';

        $dh       = opendir($folder);
        while (false !== ($filename = readdir($dh))) {
            if (is_file($folder . '/' . $filename) && preg_match("/\.(" . $this->config['fileExtensions'] . ")*$/i", $filename)) {
                $image = $filename;
                break;
            }
        }
        closedir($dh);

        if ($image == '') {
            $dh       = opendir($folder );
            while (false !== ($filename = readdir($dh))) {
                if ($filename != '.' && $filename != '..' && !is_file($folder . '/' . $filename)) {
                    $image = $this->GetFirstImageFolder($folder . '/' . $filename);
                    if ($image != '') {
                        $image = $filename . '/' . $image;
                        break;
                    }
                }
            }
            closedir($dh);
        }


        return $image;
    }

    /**
     *
     * @param object $a
     * @param object $b
     * @return int
     */
    protected function Compare($a, $b)
    {
        $al = strtolower($a->title);
        $bl = strtolower($b->title);
        if ($al == $bl) {
            return 0;
        }
        $b = false;
        switch ($this->config['sortOrder']) {
            case 'DESC' :
                if ($al < $bl) {
                    $b = true;
                }
                break;
            case 'ASC':
            default:
                if ($al > $bl) {
                    $b = true;
                }
                break;
        }
        return ($b) ? +1 : -1;
    }

    protected function image_fix_orientation(&$image, &$size, $filename) {
        if (!preg_match('~\.(jpg|JPG|jpeg|JPEG)$~', $filename)) {
            // It's not a JPEG
            return;
        }

        $exif = exif_read_data($filename);
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    if ($image != null)
                        $image = imagerotate($image, 180, 0);
                    break;
                case 6:
                    if ($image != null)
                        $image = imagerotate($image, -90, 0);
                    list($size[0],$size[1]) = array($size[1],$size[0]);
                    break;
                case 8:
                    if ($image != null)
                        $image = imagerotate($image, 90, 0);
                    list($size[0],$size[1]) = array($size[1],$size[0]);
                    break;
            }
        }
    }

    /**
     * RETRIEVE ONE IMAGE'S DISPLAY URL
     *
     * @param string $baseFolder
     * @param string $filename
     * @return string
     */
    protected function GetImageDisplayURL( $baseFolder, $filename )
    {
        if( $this->config['images']['maxSize'] < 100 ) {
            return '';
        }

        if (!file_exists( $baseFolder . '_thumbnails' )) {
            mkdir( $baseFolder . '_thumbnails', 0755, true );
        }


        $lowresFilename = $baseFolder . '_thumbnails/' . $filename;

        if (file_exists($lowresFilename)) {
            if( filemtime($lowresFilename) > filemtime($baseFolder . $filename) ) {
                // original image file is older as the image use for display
                $size = getimagesize($lowresFilename);
                $this->currentItem->setImgWidth($size[0]);
                $this->currentItem->setImgHeight($size[1]);
                return rawurlencode($this->downloadPath . $this->CustomEncode($lowresFilename));
            }
        }

        $size = getimagesize($baseFolder . $filename);

        switch ($size['mime']) {
            case 'image/jpeg':
                $orgImage = imagecreatefromjpeg($baseFolder . $filename);
                break;
            case 'image/gif':
                $orgImage = imagecreatefromgif($baseFolder . $filename);
                break;
            case 'image/png':
                $orgImage = imagecreatefrompng($baseFolder . $filename);
                break;
            default:
                return false;
                break;
        }

        $this->image_fix_orientation($orgImage, $size, $baseFolder . $filename);

        $width  = $size[0];
        $height = $size[1];

        if( $width <= $this->config['images']['maxSize'] && $height <= $this->config['images']['maxSize'] ) {
            // original image is smaller than max size -> return original file
            $this->currentItem->setImgWidth($width);
            $this->currentItem->setImgHeight($height);
            return rawurlencode($this->downloadPath . $this->CustomEncode($baseFolder . $filename));
        }

        $newWidth = $width;
        $newHeight = $height;
        if( $width > $height ) {
            if( $width > $this->config['images']['maxSize'] ) {
                $newWidth = $this->config['images']['maxSize'];
                $newHeight = $this->config['images']['maxSize'] / $width * $height;
            }
        }
        else {
            if( $height > $this->config['images']['maxSize'] ) {
                $newHeight = $this->config['images']['maxSize'];
                $newWidth = $this->config['images']['maxSize'] / $height * $width;
            }
        }

        $display_image = imagecreatetruecolor($newWidth, $newHeight);

        // Resize
        imagecopyresampled($display_image, $orgImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // save to disk
        switch ($size['mime']) {
            case 'image/jpeg':
                imagejpeg($display_image, $lowresFilename, $this->config['images']['jpegQuality'] );
                break;
            case 'image/gif':
                imagegif($display_image, $lowresFilename);
                break;
            case 'image/png':
                imagepng($display_image, $lowresFilename, 1);
                break;
        }

        $this->currentItem->setImgWidth($newWidth);
        $this->currentItem->setImgHeight($newHeight);
        return rawurlencode($this->downloadPath . $this->CustomEncode($lowresFilename));

    }


    /**
     * RETRIEVE ONE IMAGE'S THUMBNAILS
     *
     * @param string $baseFolder
     * @param string $filename
     */
    protected function GetThumbnail2( $baseFolder, $filename )
    {

        $s  = array( 'xs',   'sm',   'me',   'la',   'xl'  );
        $sw = array( 'wxs',  'wsm',  'wme',  'wla',  'wxl' );
        $sh = array( 'hxs',  'hsm',  'hme',  'hla',  'hxl' );
        for( $i = 0; $i < count($s) ; $i++ ) {

            $pi=pathinfo($filename);
            $tn= $pi['filename'] . '_' . $this->tn_size[$sw[$i]] . '_' . $this->tn_size[$sh[$i]] . '.' . $pi['extension'];
            if ( $this->GenerateThumbnail2($baseFolder, $filename, $tn, $this->tn_size[$sw[$i]], $this->tn_size[$sh[$i]], $i ) == true ) {
                $this->currentItem->addTUrl($i, $this->downloadPath . $this->CustomEncode($baseFolder . '_thumbnails/' . $tn));
            }
            else {
                // fallback: original image (no thumbnail)
                $this->currentItem->addTUrl($i, $this->downloadPath . $this->CustomEncode($baseFolder . $filename));
            }
        }
    }

    /**
     * GENERATE A SMALL BASE64 GIF WITH ONE IMAGE'S DOMINANT COLORS
     * @param $img
     * @return string
     */
    protected function GetDominantColorsGIF( $img )
    {
        $size = getimagesize($img);
        switch ($size['mime']) {
            case 'image/jpeg':
                $orgImage = imagecreatefromjpeg($img);
                break;
            case 'image/gif':
                $orgImage = imagecreatefromgif($img);
                break;
            case 'image/png':
                $orgImage = imagecreatefrompng($img);
                break;
            default:
                return '';
                break;
        }

        $this->image_fix_orientation($orgImage, $size, $img);

        $width  = $size[0];
        $height = $size[1];
        $thumb = imagecreate(3, 3);

        imagecopyresampled($thumb, $orgImage, 0, 0, 0, 0, 3, 3, $width, $height);

        ob_start();
        imagegif( $thumb );
        $image_data = ob_get_contents();
        ob_end_clean();

        return base64_encode( $image_data );
    }

    /**
     * RETRIVE ONE IMAGE'S DOMINANT COLOR
     * @param $img
     * @return string
     */
    protected function GetDominantColor( $img )
    {
        $size = getimagesize($img);
        switch ($size['mime']) {
            case 'image/jpeg':
                $orgImage = imagecreatefromjpeg($img);
                break;
            case 'image/gif':
                $orgImage = imagecreatefromgif($img);
                break;
            case 'image/png':
                $orgImage = imagecreatefrompng($img);
                break;
            default:
                return '#000000';
                break;
        }

        $this->image_fix_orientation($orgImage, $size, $img);

        $width  = $size[0];
        $height = $size[1];

        $pixel = imagecreatetruecolor(1, 1);

        imagecopyresampled($pixel, $orgImage, 0, 0, 0, 0, 1, 1, $width, $height);

        $rgb = imagecolorat($pixel, 0, 0);
        $color = imagecolorsforindex($pixel, $rgb);
        $hex=sprintf('#%02x%02x%02x', $color['red'], $color['green'], $color['blue']);

        return $hex;
    }

    /**
     * GENERATE ONE THUMBNAIL
     * @param $baseFolder
     * @param $imagefilename
     * @param $thumbnailFilename
     * @param $thumbWidth
     * @param $thumbHeight
     * @param $s
     * @return bool
     */
    protected function GenerateThumbnail2($baseFolder, $imagefilename, $thumbnailFilename, $thumbWidth, $thumbHeight, $s)
    {
        if (!file_exists( $baseFolder . '_thumbnails' )) {
            mkdir( $baseFolder . '_thumbnails', 0755, true );
        }

        $generateThumbnail = true;
        if (file_exists($baseFolder . '_thumbnails/' . $thumbnailFilename)) {
            if( filemtime($baseFolder . '_thumbnails/' . $thumbnailFilename) > filemtime($baseFolder.$imagefilename) ) {
                // image file is older as the thumbnail file
                $generateThumbnail=false;
            }
        }

        $generateDominantColors = true;
        if( $s != 0 ) {
            $generateDominantColors=false;
        }
        else {
            $generateDominantColors= ! $this->GetDominantColors($baseFolder . $imagefilename, $baseFolder . '_thumbnails/' . $thumbnailFilename . '.data');
        }

        $size = getimagesize($baseFolder . $imagefilename);

        if( $generateThumbnail == true || $generateDominantColors == true ) {
            switch ($size['mime']) {
                case 'image/jpeg':
                    $orgImage = imagecreatefromjpeg($baseFolder . $imagefilename);
                    break;
                case 'image/gif':
                    $orgImage = imagecreatefromgif($baseFolder . $imagefilename);
                    break;
                case 'image/png':
                    $orgImage = imagecreatefrompng($baseFolder . $imagefilename);
                    break;
                default:
                    return false;
                    break;
            }
        }

        $this->image_fix_orientation($orgImage, $size, $baseFolder . $imagefilename);

        $width  = $size[0];
        $height = $size[1];

        $originalAspect = $width / $height;

        if ( $thumbWidth != 'auto' && $thumbHeight != 'auto' ) {
            $thumbAspect    = $thumbWidth / $thumbHeight;
            // IMAGE CROP
            // some inspiration found in donkeyGallery (from Gix075) https://github.com/Gix075/donkeyGallery
            if ($originalAspect >= $thumbAspect) {
                // If image is wider than thumbnail (in aspect ratio sense)
                $newHeight = $thumbHeight;
                $newWidth  = $width / ($height / $thumbHeight);
            } else {
                // If the thumbnail is wider than the image
                $newWidth  = $thumbWidth;
                $newHeight = $height / ($width / $thumbWidth);
            }

            // thumbnail image size
            // $this->currentItem->t_width[$s]=$newWidth;
            // $this->currentItem->t_height[$s]=$newHeight;
            $this->currentItem->addTWidth($s, $thumbWidth);
            $this->currentItem->addTHeight($s, $thumbHeight);

            if( $generateThumbnail == true ) {
                $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
                // Resize and crop
                imagecopyresampled($thumb, $orgImage,
                    0 - ($newWidth - $thumbWidth) / 2,    // dest_x: Center the image horizontally
                    0 - ($newHeight - $thumbHeight) / 2,  // dest-y: Center the image vertically
                    0, 0, // src_x, src_y
                    $newWidth, $newHeight, $width, $height);
            }

        } else {
            // NO IMAGE CROP
            if( $thumbWidth == 'auto' ) {
                $newWidth  = $width / $height * $thumbHeight;
                $newHeight = $thumbHeight;
            }
            else {
                $newHeight = $height / $width * $thumbWidth;
                $newWidth  = $thumbWidth;
            }

            // thumbnail image size
            $this->currentItem->addTWidth($s, $newWidth);
            $this->currentItem->addTHeight($s, $newHeight);

            if( $generateThumbnail == true ) {
                $thumb = imagecreatetruecolor($newWidth, $newHeight);

                // Resize
                imagecopyresampled($thumb, $orgImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            }
        }

        if( $generateThumbnail == true ) {
            switch ($size['mime']) {
                case 'image/jpeg':
                    imagejpeg($thumb, $baseFolder . '/_thumbnails/' . $thumbnailFilename, $this->config['thumbnails']['jpegQuality'] );
                    break;
                case 'image/gif':
                    imagegif($thumb, $baseFolder . '/_thumbnails/' . $thumbnailFilename);
                    break;
                case 'image/png':
                    imagepng($thumb, $baseFolder . '/_thumbnails/' . $thumbnailFilename, 1);
                    break;
            }
        }

        if( $generateDominantColors == true ) {
            // Dominant colorS -> GIF
            $dc3 = imagecreate($this->config['thumbnails']['blurredImageQuality'], $this->config['thumbnails']['blurredImageQuality']);
            imagecopyresampled($dc3, $orgImage, 0, 0, 0, 0, 3, 3, $width, $height);
            ob_start();
            imagegif( $dc3 );
            $image_data = ob_get_contents();
            ob_end_clean();
            $this->currentItem->setDcGIF(base64_encode( $image_data ));

            // Dominant color -> HEX RGB
            $pixel = imagecreatetruecolor(1, 1);
            imagecopyresampled($pixel, $orgImage, 0, 0, 0, 0, 1, 1, $width, $height);
            $rgb = imagecolorat($pixel, 0, 0);
            $color = imagecolorsforindex($pixel, $rgb);
            $hex=sprintf('#%02x%02x%02x', $color['red'], $color['green'], $color['blue']);
            $this->currentItem->setDc($hex);

            // save to cache
            $fdc = fopen($baseFolder . '_thumbnails/' . $thumbnailFilename . '.data', 'w');
            if( $fdc ) {
                fwrite($fdc, 'dc=' . $hex . "\n");
                fwrite($fdc, 'dcGIF=' . base64_encode( $image_data ));
                fclose($fdc);
            }
            else {
                // exit without dominant color
                return false;
            }
        }

        return true;
    }


    /**
     * @param $fileImage
     * @param $fileDominantColors
     * @return bool
     */
    protected function GetDominantColors($fileImage, $fileDominantColors)
    {

        if (file_exists($fileDominantColors)) {
            if( filemtime($fileDominantColors) < filemtime($fileImage) ) {
                // image file is older as the dominant colors file
                return false;
            }

            // read cached data
            $cnt=0;
            $myfile = fopen($fileDominantColors, "r");
            if( $myfile ) {
                while(!feof($myfile)) {
                    $l=fgets($myfile);
                    $s=explode('=', $l);
                    if( is_array($s) ) {
                        $property=trim($s[0]);
                        $value=trim($s[1]);
                        if( $property != '' &&  $value != '' ) {
                            $this->currentItem->setAttribute($property, $value);
                            $cnt++;
                        }
                    }
                }
                fclose($myfile);
            }

            if( $cnt == 2 ) {
                // ok, 2 values found
                return true;
            }
        }

        return false;

    }

    /**
     * Extract title and description from filename
     *
     * @param string $filename
     * @param boolean $isImage
     * @return Item
     */
    protected function GetMetaData($filename, $isImage)
    {
        $f=$filename;

        if ($isImage) {
            $filename = $this->file_ext_strip($filename);
        }

        $oneItem = new Item();
        if (strpos($filename, $this->config['titleDescSeparator']) > 0) {
            // title and description
            $s              = explode($this->config['titleDescSeparator'], $filename);
            $oneItem->setTitle($this->CustomEncode($s[0]));
            if ($isImage) {
                $oneItem->setDescription($this->CustomEncode(preg_replace('/.[^.]*$/', '', $s[1])));
            } else {
                $oneItem->setDescription($this->CustomEncode($s[1]));
            }
        } else {
            // only title
            if ($isImage) {
                $oneItem->setTitle($this->CustomEncode($filename));  //(preg_replace('/.[^.]*$/', '', $filename));
            } else {
                $oneItem->setTitle($this->CustomEncode($filename));
            }
            $oneItem->setDescription('');
        }

        $oneItem->setTitle(str_replace($this->config['albumCoverDetector'], '', $oneItem->getTitle()));   // filter cover detector string

        // the title (=filename) is the ID
        $oneItem->setID($oneItem->getTitle());

        // read meta data from external file (only images)
        if ($isImage) {
            if( file_exists( $this->data->fullDir . '/' . $filename . '.txt' ) ) {
                $myfile = fopen($this->data->fullDir . '/' . $filename . '.txt', "r") or die("Unable to open file!");
                while(!feof($myfile)) {
                    $l=fgets($myfile);
                    $s=explode('=', $l);
                    if( is_array($s) ) {
                        $property=trim($s[0]);
                        $value=trim($s[1]);
                        if( $property != '' &&  $value != '' ) {
                            $oneItem->setAttribute($property, $value);
                        }
                    }
                }
                fclose($myfile);
            }

        }
        return $oneItem;
    }

    /**
     * Returns only the file extension (without the period).
     *
     * @param string $filename
     * @return string
     */
    protected function file_ext($filename)
    {
        if (!preg_match('/./', $filename)) {
            return '';
        }
        return preg_replace('/^.*./', '', $filename);
    }

    /**
     * Returns the file name, less the extension.
     *
     * @param string $filename
     * @return string
     */
    protected function file_ext_strip($filename)
    {
        return preg_replace('/.[^.]*$/', '', $filename);
    }



    /**
     *
     * @param string $s
     * @return string
     */
    protected function CustomEncode($s)
    {
        return Encoding::toUTF8(($s));
        //return \ForceUTF8\Encoding::fixUTF8(($s));
    }

    /**
     *
     * @param string $s
     * @return mixed
     */
    protected function CustomDecode($s)
    {
        return utf8_decode($s);
        // return $s;
    }


    /**
     * Returns the number of items in one disk folder.
     *
     * @param string $d
     * @return integer
     */
    protected function AlbumCountItems( $d )
    {
        $cnt = 0;
        $dh = opendir($d);

        // loop the folder to retrieve images and albums
        if ($dh != false) {
            while (false !== ($filename = readdir($dh))) {

                if (is_file($this->data->fullDir . $filename) ) {
                    // it's a file
                    if ($filename != '.' &&
                        $filename != '..' &&
                        $filename != '_thumbnails' &&
                        preg_match("/\.(" . $this->config['fileExtensions'] . ")*$/i", $filename) &&
                        strpos($filename, $this->config['ignoreDetector']) == false &&
                        strpos($filename, $this->config['albumCoverDetector']) == false )
                    {
                        $cnt++;
                    }
                }
                else {
                    // it's a folder
                    if ($filename != '.' &&
                        $filename != '..' &&
                        $filename != '_thumbnails' &&
                        strpos($filename, $this->config['ignoreDetector']) == false &&
                        !empty($filename) )
                    {
                        $cnt++;
                    }
                }
            }
        }
        else {
            closedir($dh);
        }

        return $cnt;

    }

    protected function PrepareData($filename, $kind)
    {
        // $oneItem = new item();
        $this->currentItem = new Item();
        // if (is_file($this->data->fullDir . $filename) && preg_match("/\.(" . $this->config['fileExtensions'] . ")*$/i", $filename)) {
        if ( $kind == 'IMAGE' ) {
            // ONE IMAGE
            $this->currentItem->setKind("image") ;
            $e = $this->GetMetaData($filename, true);
            $this->currentItem->setTitle($e->getTitle());
            $this->currentItem->setDescription($e->getDescription());
            // $this->currentItem->src             = rawurlencode($this->CustomEncode($this->config['contentFolder'] . $this->album . '/' . $filename));
            $this->currentItem->setOriginalUrl(rawurlencode($this->downloadPath . $this->CustomEncode($this->config['contentFolder'] . $this->album . '/' . $filename)));
            $this->currentItem->setSrc($this->GetImageDisplayURL($this->data->fullDir, $filename));

            if( $this->currentItem->getSrc() == "") {
                $this->currentItem->setSrc($this->currentItem->getOriginalUrl());
                $imgSize = getimagesize($this->data->fullDir . '/' . $filename);
                $this->currentItem->setImgWidth($imgSize[0]);
                $this->currentItem->setImgHeight($imgSize[1]);
            }

            $this->GetThumbnail2($this->data->fullDir, $filename);
            $this->currentItem->setAlbumID(rawurlencode($this->albumID));
            if ($this->albumID == '0' || $this->albumID == '') {
                $this->currentItem->setID(rawurlencode($this->CustomEncode($e->getID())));
            } else {
                $this->currentItem->setID(rawurlencode($this->albumID . $this->CustomEncode('/' . $e->getID())));
            }
            return $this->currentItem;
        }
        else {
            // ONE ALBUM
            $this->currentItem->setKind('album');

            $e = $this->GetMetaData($filename, false);
            $this->currentItem->setTitle($e->getTitle());
            $this->currentItem->setDescription($e->getDescription());

            $this->currentItem->setAlbumID(rawurlencode($this->albumID));
            if ($this->albumID == '0' || $this->albumID == '') {
                $this->currentItem->setID(rawurlencode($this->CustomEncode($filename)));
            } else {
                $this->currentItem->setID(rawurlencode($this->albumID . $this->CustomEncode('/' . $filename)));
            }
            $ac=$this->GetAlbumCover($this->data->fullDir . $filename . '/');
            if ( $ac != '' ) {
                // $path = '';
                // if ($this->albumID == '0') {
                // $path = $filename;
                // } else {
                // $path = $this->album . '/' . $filename;
                // }
                $this->currentItem->setCnt($this->AlbumCountItems( $this->data->fullDir . $filename . '/'));
                return $this->currentItem;
            }
        }
        return null;
    }
}