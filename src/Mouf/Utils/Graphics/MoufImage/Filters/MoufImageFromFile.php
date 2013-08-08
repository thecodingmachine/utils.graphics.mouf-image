<?php
namespace Mouf\Utils\Graphics\MoufImage\Filters;
use Mouf\Utils\Graphics\MoufImage\MoufImageResource;

use Mouf\Utils\Graphics\MoufImage\MoufImageInterface;

/**
 * @Component
 * @author Kevin
 *
 */
class MoufImageFromFile implements MoufImageInterface {

	
	/**
	 * The absolute path of the image file
	 * @Property
	 * @var string
	 */
	public $path;
	
	/**
	 * Get the GD Image resource loaded
	 * @return $resource : the MoufImageResource
	 */
	public function getResource(){
		$image_info = getimagesize($this->path);
		
		
		
		$image_type = $image_info[2];
		if( $image_type == IMAGETYPE_JPEG ) {
			$image = imagecreatefromjpeg($this->path);
		} elseif( $image_type == IMAGETYPE_GIF ) {
			$image = imagecreatefromgif($this->path);
		} elseif( $image_type == IMAGETYPE_PNG ) {
			$image = imagecreatefrompng($this->path);
		}
		
		$imageResource = new MoufImageResource();
		$imageResource->resource = $image;
		$imageResource->originPath = $this->path;
		$imageResource->originInfo = $image_info;
		
		return $imageResource;
	}
}