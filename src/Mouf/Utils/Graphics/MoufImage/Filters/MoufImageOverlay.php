<?php
namespace Mouf\Utils\Graphics\MoufImage\Filters;

/**
 * @Component
 * @author Kevin
 *
 */
class MoufImageOverlay implements MoufImageInterface{

	
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

		imagealphablending($overlay,false);
    	imagesavealpha($overlay,true);
    	
		imagecopymerge($moufImageResource->resource, $overlay, 0, 0, 0, 0, imagesx($overlay), imagesy($overlay), 100);
		
		return $moufImageResource;
	}
	
}