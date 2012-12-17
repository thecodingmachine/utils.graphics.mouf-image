<?php
namespace Mouf\Utils\Graphics\MoufImage;

class MoufImageResource{

	/**
	 * The php GD resource image
	 * @var resource 
	 */
	public $resource;
	
	/**
	 * The pathe of the file initialy loaded (if the first MoufImageInterface loaded the image from a file)
	 * @var string
	 */
	public $originPath;
	
	
	/**
	 * The array return by getimagesize() as the image has been loaded (agin only if the first MoufImageInterface loaded the image from a file)
	 * @var unknown_type
	 */
	public $originInfo;
	
}
