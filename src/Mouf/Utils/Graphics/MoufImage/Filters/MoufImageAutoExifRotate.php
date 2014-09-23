<?php
namespace Mouf\Utils\Graphics\MoufImage\Filters;
use Mouf\Utils\Graphics\MoufImage\MoufImageInterface;

/**
 * If your image has a Exif information (Exchangeable image file format)
 * Numeric camera set many information in picture
 * If there is an orientation in, this class rotate it to the good orientation
 * @author Marc TEYSSIER
 *
 */
class MoufImageAutoExifRotate extends MoufImageRotate{

	
	/**
	 * The Mouf Image that will be resized
	 * @Property
	 * @Compulsory
	 * @var MoufImageInterface
	 */
	public $source;
	
	/**
	 * The angle of rotation
	 * (Not use, it is set automatically)
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
		$data = exif_read_data ($moufImageResource->originPath);

		if(isset($data['Orientation']) && $data['Orientation']) {
			switch($data['Orientation']) {
				case 3:
					$this->angle = 180;
					break;
				case 6:
					$this->angle = -90;
					break;
				case 8:
					$this->angle = 90;
					break;
			}
		}
		
		return parent::getResource();
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