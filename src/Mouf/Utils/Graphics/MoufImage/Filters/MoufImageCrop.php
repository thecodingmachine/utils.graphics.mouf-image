<?php
namespace Mouf\Utils\Graphics\MoufImage\Filters;
use Mouf\Utils\Graphics\MoufImage\MoufImageInterface;

/**
 * @author Kevin
 *
 */
class MoufImageCrop implements MoufImageInterface {

	
	/**
	 * The Mouf Image that will be resized
	 * @Property
	 * @Compulsory
	 * @var MoufImageInterface
	 */
	public $source;
	
	/**
	 * The resize height in px
	 * @Property
	 * @Compulsory
	 * @var int
	 */
	public $height;
	
	/**
	 * The resize width in px
	 * @Property
	 * @Compulsory
	 * @var int
	 */
	public $width;
	
	
	
	/**
	 * Get the GD Image resource after effect has been applied
	 * @return $image, the GD resource image
	 */
	public function getResource(){
		$moufImageResource = $this->source->getResource();
		
		$imageResource = $moufImageResource->resource;
		$imgInfo = $moufImageResource->originInfo;

		$oHeight = imagesy($imageResource);
		$oWidth = imagesx($imageResource);
		
		$cx = $oWidth / 2;
		$cy = $oHeight / 2;
		$x = $cx - $this->width / 2;
		$y = $cy - $this->height / 2;
		if ($x < 0) $x = 0;
		if ($y < 0) $y = 0;
		
		$dest = imagecreatetruecolor($this->width, $this->height);

        //If image id of type PNG or GIF, preserve Transparency
        if(($imgInfo[2] == 1) || ($imgInfo[2]==3)){
            imagealphablending($dest, false);
            imagesavealpha($dest,true);
        }
		
		// Copy
		imagecopy($dest, $imageResource, 0, 0, $x, $y, $this->width, $this->height);
		
		$moufImageResource->resource = $dest;
		
		return $moufImageResource;
	}
	
}