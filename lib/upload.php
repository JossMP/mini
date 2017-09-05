<?php
	define("UPLOAD_ERR_EMPTY","UPLOAD_ERR_EMPTY");
	class Upload
	{
		public function __construct( array $extensions = array() )
		{
			$this->extensions = $extensions;
		}

		public function loadFile($name)
		{
			if( isset($_FILES[$name]) )
			{
				if ($_FILES[$name]["error"] != UPLOAD_ERR_OK)
				{
					return false;
				}
				else if( !is_uploaded_file($_FILES[$name]["tmp_name"]) )
				{
					return false;
				}
				else
				{
					return true;
				}
			}
			return false;
		}

		public function saveFile( $name, $destine )
		{
			if( $this->loadFile($name) && $this->validate($name) )
			{
				if( move_uploaded_file($_FILES[$name]['tmp_name'], $destine) )
				{
					return true;
				}
			}
			return false;
		}

		public function validate($name)
		{
			$fileInfo = pathinfo($_FILES[$name]['name']);
			$ext = strtolower($fileInfo['extension']);
			if (!in_array($ext, $this->extensions))
			{
				return false;
			}
			return true;
		}
		public function getExtension($name)
		{
			$fileInfo = pathinfo($_FILES[$name]['name']);
			$ext = strtolower($fileInfo['extension']);
			return $ext;
		}
		public function getFileName($name)
		{
			$fileInfo = pathinfo($_FILES[$name]['name']);
			$ext = $fileInfo['filename'];
			return $ext;
		}
		public function setFilter( array $extensions )
		{
			$this->extensions = $extensions;
		}
		/* Only Images */
		public function loadImage($name)
		{
			if( isset($_FILES[$name]) )
			{
				if ($_FILES[$name]["error"] != UPLOAD_ERR_OK)
				{
					return false;
				}
				else if( !is_uploaded_file($_FILES[$name]["tmp_name"]) )
				{
					return false;
				}
				else
				{
					$this->path = $_FILES[$name]["tmp_name"];
					$info = @getimagesize($this->path);
					if($info)
					{
						$this->width = $info[0];
						$this->height = $info[1];
						$this->type = $info[2];
						switch($this->type)
						{
							case IMAGETYPE_JPEG:
								$this->ext="jpg";
								$this->image = imagecreatefromjpeg($path);
								return true;
							break;
							case IMAGETYPE_GIF:
								$this->ext="gif";
								$this->image = imagecreatefromgif($path);
								return true;
							break;
							case IMAGETYPE_PNG:
								$this->ext="png";
								$this->image = imagecreatefrompng($path);
								return true;
							break;
						}
					}
				}
			}
			return false;
		}
		function saveImage($destine, $quality = 100)
		{
			if( $this->loadFile($this->path) )
			{
				switch($this->type)
				{
					case IMAGETYPE_JPEG:
						$ext="jpg";
						$image = imagecreatefromjpeg($path);
						imagejpeg($image, $destine, $quality);
						return true;
					break;
					case IMAGETYPE_GIF:
						$ext="gif";
						$image = imagecreatefromgif($path);
						imagegif($image, $destine);
						return true;
					break;
					case IMAGETYPE_PNG:
						$ext="png";
						$image = imagecreatefrompng($path);
						$pngquality = floor(($quality-10) / 10);
						imagepng($image, $destine, $pngquality);
						return true;
					break;
				}
			}
			return false;
		}
		function thumb($name, $destine, $value, $prop="width") // Miniatura Proporcional
		{
			$path = $_FILES[$name]["tmp_name"];
			$info = @getimagesize($path);
			if($info)
			{
				$width = $info[0];
				$height = $info[1];
				$type = $info[2];
				switch($prop)
				{
					case 'width':
						$const = $value / $width;
						$new_width = $value;
						$new_height = $value * $const;
						$new_image = imagecreatetruecolor($new_width, $new_height);
						imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
					break;
					case 'height':
						$const = $value / $height;
						$new_width = $value * $const;
						$new_height = $value;
						$new_image = imagecreatetruecolor($new_width, $new_height);
						imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
					break;
				}
				if( isset($new_image) )
				{
					switch( $type )
					{
						case IMAGETYPE_JPEG:
							$image = imagecreatefromjpeg($path);
							imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
							imagejpeg($new_image, $destine, $quality);
							return true;
						break;
						case IMAGETYPE_GIF:
							$ext="gif";
							$image = imagecreatefromgif($path);
							imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
							imagegif($new_image, $destine);
							return true;
						break;
						case IMAGETYPE_PNG:
							$ext="png";
							$image = imagecreatefrompng($path);
							imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
							$pngquality = floor(($quality-10) / 10);
							imagepng($new_image, $destine, $pngquality);
							return true;
						break;
					}
				}
			}
			return false;
		}
		function crop( $name, $destine, $cwidth, $cheight, $pos = 'center' )
		{
			$path = $_FILES[$name]["tmp_name"];
			$info = @getimagesize( $path );
			if( $info )
			{
				$width = $info[0];
				$height = $info[1];
				$type = $info[2];
				if($this->width < $cwidth)
				{
					return false;
				}
				if($this->height < $cheight)
				{
					return false;
				}
				switch($this->type)
				{
					case IMAGETYPE_JPEG:
						$image = imagecreatefromjpeg($path);
					break;
					case IMAGETYPE_GIF:
						$image = imagecreatefromgif($path);
					break;
					case IMAGETYPE_PNG:
						$image = imagecreatefrompng($path);
					break;
				}
			}
			// Si la Imagen Es Mas Pequena
			$new_image = imagecreatetruecolor($cwidth, $cheight);
			switch($pos)
			{
				case 'center':
					imagecopyresampled($new_image, $this->image, 0, 0, abs(($this->width - $cwidth) / 2), abs(($this->height - $cheight) / 2), $cwidth, $cheight, $cwidth, $cheight);
				break;
				case 'left':
					imagecopyresampled($new_image, $this->image, 0, 0, 0, abs(($this->height - $cheight) / 2), $cwidth, $cheight, $cwidth, $cheight);
				break;
				case 'right':
					imagecopyresampled($new_image, $this->image, 0, 0, $this->width - $cwidth, abs(($this->height - $cheight) / 2), $cwidth, $cheight, $cwidth, $cheight);
				break;
				case 'top':
					imagecopyresampled($new_image, $this->image, 0, 0, abs(($this->width - $cwidth) / 2), 0, $cwidth, $cheight, $cwidth, $cheight);
				break;
				case 'bottom':
					imagecopyresampled($new_image, $this->image, 0, 0, abs(($this->width - $cwidth) / 2), $this->height - $cheight, $cwidth, $cheight, $cwidth, $cheight);
				break;
			}
			$this->image = $image;
		}
		function create($cwidth, $cheight, $pos = 'center')
		{
			if($this->width > $cwidth)
			{
				$this->resize($cwidth, 'width');
			}
			if($this->height > $cheight)
			{
				$this->resize($cheight, 'height');
			}
			$image = imagecreatetruecolor($cwidth, $cheight);
			//$fondo = imagecolorallocate ($image, 255, 255, 255);
			//imagefilledrectangle($image, 0, 0, $cwidth, $cheight, $fondo);
			switch($pos)
			{
				case 'center':
					imagecopy($image, $this->image, abs(($this->width-$cwidth)/2), abs(($this->height-$cheight)/2), 0, 0, $this->width, $this->height);
				break;
				case 'left':
					imagecopyresampled($image, $this->image, 0, 0, 0, abs(($this->height - $cheight) / 2), $cwidth, $cheight, $cwidth, $cheight);
				break;
				case 'right':
					imagecopyresampled($image, $this->image, 0, 0, $this->width - $cwidth, abs(($this->height - $cheight) / 2), $cwidth, $cheight, $cwidth, $cheight);
				break;
				case 'top':
					imagecopyresampled($image, $this->image, 0, 0, abs(($this->width - $cwidth) / 2), 0, $cwidth, $cheight, $cwidth, $cheight);
				break;
				case 'bottom':
					imagecopyresampled($image, $this->image, 0, 0, abs(($this->width - $cwidth) / 2), $this->height - $cheight, $cwidth, $cheight, $cwidth, $cheight);
				break;
			}
			$this->image = $image;
			$this->width = imagesx($this->image);
			$this->height = imagesy($this->image);
			//$black = imagecolorallocate( $this->image, 0, 0, 0 );
			//imagecolortransparent( $this->image, $black );
		}
	}
?>
