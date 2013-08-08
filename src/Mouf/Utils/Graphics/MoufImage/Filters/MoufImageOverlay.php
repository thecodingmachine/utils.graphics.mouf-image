<?php
namespace Mouf\Utils\Graphics\MoufImage\Filters;

use Mouf\Utils\Graphics\MoufImage\MoufImageInterface;

/**
 * @Component
 * @author Kevin
 *
 */
class MoufImageOverlay implements MoufImageInterface {

	
	/**
	 * The Mouf Image that will recieve the inscrusted one
	 * The index of the array will determin the order of the merge : first image in the array will be at the "bottom" of the heap
	 * 
	 * @Property
	 * @Compulsory
	 * @var MoufImageInterface $source
	 */
	public $source;
	
	/**
	 * The path to the overlay
	 * 
	 * 
	 * @Property
	 * @Compulsory
	 * @var string $overlayPath
	 */
	public $overlayPath;
	
	
	/**
	 * Get the GD Image resource after effect has been applied
	 * @return $image, the GD resource image
	 */
	public function getResource(){
		$moufImageResource = $this->source->getResource();
		
		$overlay = new MoufImageFromFile();
		$overlay->path = $this->overlayPath;
		$overlay = $overlay->getResource()->resource;

		$destSizeX = imagesx($moufImageResource->resource);
		$destSizeY = imagesy($moufImageResource->resource);
		
		$overlaySizeX = imagesx($overlay);
		$overlaySizeY = imagesy($overlay);
		
		$finalWidth = max ($destSizeX, $overlaySizeX);
		$finalHeight = max ($destSizeY, $overlaySizeY);
		
		$originX = (int) (($finalWidth - $destSizeX) / 2);
		$originY = (int) (($finalHeight - $destSizeY) / 2);
		
		
		$support = imagecreatetruecolor($finalWidth, $finalHeight);
		imagesavealpha($support, true);
		imagealphablending($support, false);
		$white = imagecolorallocatealpha($support, 255, 255, 255, 127);
		imagefill($support, 0, 0, $white);
		
		imagecopymerge($support, $moufImageResource->resource, $originX, $originY, 0, 0, $destSizeX, $destSizeY, 100);
		
		imagealphablending($overlay,false);
    	imagesavealpha($overlay,true);	
    	
		imagecopymerge($support, $overlay, 0, 0, 0, 0, imagesx($overlay), imagesy($overlay), 100);
		
		imagepng($support, "c:/Users/Kevin/Desktop/test2.png");
		imagedestroy($support);
		exit;
		
		return $moufImageResource;
	}
	
}