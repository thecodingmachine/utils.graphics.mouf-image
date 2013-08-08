<?php
namespace Mouf\Utils\Graphics\MoufImage;

interface MoufImageInterface{

	
	/**
	 * Get the GD Image resource after effect has been applied
	 * @return MoufImageResource $image, the GD resource image
	 */
	public function getResource();
	
}