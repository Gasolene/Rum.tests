<?php
    namespace MyApp\Models;
    use \MyApp\App;
    use \GD\GDImage;

	class ImageTestCase extends \System\Testcase\UnitTestCaseBase {

		function test() {
			$img = new GDImage();
			$img2 = new GDImage();

			$img->loadFile( \Rum::config()->fixtures . '/Test Image.jpg' );

			$img->resize( 800, 300 );
			$this->assertTrue( $img->getWidth() === 400 );
			$this->assertTrue( $img->getHeight() === 300 );

			$img2 = $img;
			$img3 = new GDImage( $img2->getStream() );
			$img3->output( __TMP_PATH__ . '/img.jpg', 'image/jpeg' );
			$img4 = new GDImage();
            $img4->loadFile(__TMP_PATH__ . '/img.jpg' );
            $img5 = new GDImage( $img4->getStream() );

            // __SCALE_TO_FIT__
			$img->resize( 800, 600 );
			$this->assertTrue( $img->getWidth() === 400 );
			$this->assertTrue( $img->getHeight() === 300 );

            $img->resize( 8, 30, \GD\GDImageResizeMode::scaleToFit() );
			$this->assertTrue( $img->getWidth() === 8 );
			$this->assertTrue( $img->getHeight() === 6 );

            // __RESIZE__
			$img2->resize( 800, 30, \GD\GDImageResizeMode::resize() );
			$this->assertTrue( $img2->getWidth() === 800 );
			$this->assertTrue( $img2->getHeight() === 30 );

            // __SCALE_TO_WIDTH__
			$img3->resize( 800, 30, \GD\GDImageResizeMode::scaleToWidth() );
			$this->assertTrue( $img3->getWidth() === 800 );
			$this->assertTrue( $img3->getHeight() === 600 );

            // __SCALE_TO_HEIGHT__
			$img4->resize( 800, 30, \GD\GDImageResizeMode::scaleToHeight() );
			$this->assertTrue( $img4->getWidth() === 40 );
			$this->assertTrue( $img4->getHeight() === 30 );

            // __SCALE_TO_CROP__
			$img5->resize( 200, 30, \GD\GDImageResizeMode::scaleToCrop() );
			$this->assertTrue( $img5->getWidth() === 200 );
			$this->assertTrue( $img5->getHeight() === 150 );

			$img5->resize( 10, 15, \GD\GDImageResizeMode::scaleToCrop() );
			//$this->assertTrue( $img5->getWidth() === 20 );
			//$this->assertTrue( $img5->getHeight() === 15 );

            // crop
			$img5->crop( 0, 0, 5, 5 );
			$this->assertTrue( $img5->getWidth() === 5 );
			$this->assertTrue( $img5->getHeight() === 5 );

			unlink( __TMP_PATH__ . '/img.jpg' );
		}
	}
?>