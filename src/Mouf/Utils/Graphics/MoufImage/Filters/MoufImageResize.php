<?php
namespace Mouf\Utils\Graphics\MoufImage\Filters;
use Mouf\Utils\Graphics\MoufImage\MoufImageInterface;

/**
 * @Component
 * @author Kevin
 *
 */
class MoufImageResize implements MoufImageInterface {

	
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
	 * If the image should keep it's original ratio.
	 * If true, the resize will be done so the resulting image fits into the dimensions.
	 * @Property
	 * @Compulsory
	 * @var boolean
	 */
	public $keepRatio = true;
	
	/**
	 * Applies only for keepratio = true, if true, resized image will fit inside the rectangle (both dimensions will be smaller or equal to the rectangle's dimensions).
	 * Else, the image will be resized in order to fitt the biggest ratio (on of the dimension will be the one of the rectangle, the other will be higher).
	 * @Property
	 * @Compulsory
	 * @var boolean
	 */
	public $mustFitInside = true;
	
	/**
	 * If the image may be enlarged. If not, and the image is smaller than the target rectangle, the image won't be resized.
	 * @Property
	 * @Compulsory
	 * @var boolean
	 */
	public $allowEnlarge = false;
	
	/**
	 * Enforce the requested dimensions.
	 * This applies only if $keepRatio == true, and if the requested ratio is not equal to the source ratio.
	 * If $enforceRequestedDimensions == true, the destination image will occupy only a portion of the generated image.
	 * The rest of the image will be a background color that can be set via the $backgroundColor property.
	 * 
	 * @Property
	 * @var bool
	 */
	public $enforceRequestedDimensions = false;
	
	/**
	 * The red part of the background color (if any)
	 * 
	 * @Property
	 * @var int
	 */
	public $backgroundRed = 255;
	
	/**
	 * The green part of the background color (if any)
	 * 
	 * @Property
	 * @var int
	 */
	public $backgroundGreen = 255;
	
	/**
	 * The blue part of the background color (if any)
	 * 
	 * @Property
	 * @var int
	 */
	public $backgroundBlue = 255;
	
	/**
	 * The alpha channel of the background color (if any)
	 * 
	 * @Property
	 * @var int
	 */
	public $backgroundAlpha = 127;
	
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
		
		if (!$this->allowEnlarge && $oHeight < $this->height && $oWidth < $this->width){
			//do Nothing : the image is smaller than the target rectangle, so it should remain unchanged
			$newWidth = $oWidth;
			$newHeight = $oHeight;
			$newImageWidth = $oWidth;
			$newImageHeight = $oHeight;
		}else if ($this->keepRatio){
			//image doesn't fit the target rectangle (at least on dimension is greater), the final ratio is the one that make the resized image fit the target rectangle
			$xRation = (float) $oWidth / $this->width;
			$yRation = (float) $oHeight / $this->height;
			
			$finalRatio = $this->mustFitInside ? max(array($xRation, $yRation)) : min(array($xRation, $yRation));;
			
			$newWidth = $oWidth / $finalRatio;
			$newHeight = $oHeight / $finalRatio;

			if (!$this->enforceRequestedDimensions) {
				$newImageWidth = $newWidth;
				$newImageHeight = $newHeight;
			} else {
				$newImageWidth = $this->width;
				$newImageHeight = $this->height;
			}
		}else{
			//Simply apply a stupid resize, whater the original image's ratio is
			$newWidth = $this->width;
			$newHeight = $this->height;
			$newImageWidth = $newWidth;
			$newImageHeight = $newHeight;
		}
		
		$new_image = imagecreatetruecolor($newImageWidth, $newImageHeight);
		
		//If image id of type PNG or GIF, preserve Transprency
		if(($imgInfo[2] == 1) || ($imgInfo[2]==3)){
			imagealphablending($new_image, false);
			imagesavealpha($new_image,true);
		}
		$transparent = imagecolorallocatealpha($new_image, $this->backgroundRed, $this->backgroundGreen, $this->backgroundBlue, $this->backgroundAlpha);
		imagefilledrectangle($new_image, 0, 0, $newImageWidth, $newImageHeight, $transparent);
		
		imagecopyresampled($new_image, $imageResource, ($newImageWidth - $newWidth)/2, ($newImageHeight - $newHeight)/2, 0, 0, $newWidth, $newHeight, $oWidth, $oHeight);
		
		$moufImageResource->resource = $new_image;
		
		return $moufImageResource;
	}
	
}