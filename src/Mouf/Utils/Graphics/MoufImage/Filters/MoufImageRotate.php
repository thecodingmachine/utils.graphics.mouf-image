<?php
namespace Mouf\Utils\Graphics\MoufImage\Filters;
use Mouf\Utils\Graphics\MoufImage\MoufImageInterface;

/**
 * Use to rotate an image
 * @author Marc TEYSSIER
 *
 */
class MoufImageRotate implements MoufImageInterface {

	
	/**
	 * The Mouf Image that will be resized
	 * @Property
	 * @Compulsory
	 * @var MoufImageInterface
	 */
	public $source;
	
	/**
	 * The angle of rotation in degre
	 * @Property
	 * @Compulsory
	 * @var int
	 */
	public $angle;
	
	/**
	 * If is check, the transparency is ignored in rotation
	 * By default it's false
	 * @Property
	 * @var bool
	 */
	public $ignoreTransparency;
	
	/**
	 * Get the GD Image resource after effect has been applied
	 * @return $image, the GD resource image
	 */
	public function getResource(){
		$moufImageResource = $this->source->getResource();
		
		$imageResource = $moufImageResource->resource;

		$imageResource = imagerotate($imageResource, $this->angle, 0, ($this->ignoreTransparency?true:false));

		$oHeight = imagesy($imageResource);
		$oWidth = imagesx($imageResource);
		
		$dest = imagecreatetruecolor($oWidth, $oHeight);
		
		// Copy
		imagecopyresampled($dest, $imageResource, 0, 0, 0, 0, $oWidth, $oHeight, $oWidth, $oHeight);
		
		$moufImageResource->resource = $dest;
		
		return $moufImageResource;
	}
	
}