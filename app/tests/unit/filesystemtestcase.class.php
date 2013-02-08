<?php
    namespace MyApp\Models;
    use System\IO\FileSystem;
    use System\IO\File;
    use System\IO\Folder;
    use System\IO\Stream\FileStream;

	class FileSystemTestCase extends \System\Testcase\UnitTestCaseBase {

		function prepare() {
			// cleanup data file
			FileSystem::delete( __TMP_PATH__ . '/*' );

			// create data file
			$fp = fopen( __TMP_PATH__ . '/test', 'w+' );
			fwrite( $fp, "line1\nline2\n" );
			fclose( $fp );
		}

		function cleanup() {
			// cleanup data file
			FileSystem::delete( __TMP_PATH__ . '/*' );
		}

		function testFileSystem() {

			$filestream = new FileStream();
			$filestream->open( __TMP_PATH__ . '/~file' );
            $filestream->close();

			$this->assertTrue( is_file( __TMP_PATH__ . '/~file' ));

			// create folder
			$this->assertTrue( mkdir( __TMP_PATH__ . '/~folder' ));
			$this->assertTrue( is_dir( __TMP_PATH__ . '/~folder' ));

			$filestream = new FileStream();
			$filestream->open( __TMP_PATH__ . '/~folder/~file' );
            $filestream->close();

			// copy file
			$this->assertNull( FileSystem::copy( __TMP_PATH__ . '/~file' ));
			$this->assertNull( FileSystem::copy( __TMP_PATH__ . '/~file' ));
			$this->assertNull( FileSystem::copy( __TMP_PATH__ . '/~file', __TMP_PATH__ . '/~file2' ));
			$this->assertTrue( is_file( __TMP_PATH__ . '/Copy of ~file' ));
			$this->assertTrue( is_file( __TMP_PATH__ . '/Copy (2) of ~file' ));
			$this->assertFalse( is_file( __TMP_PATH__ . '/Copy (3) of ~file' ));
			$this->assertTrue( is_file( __TMP_PATH__ . '/~file2' ));

			// copy folder
			$this->assertNull( FileSystem::copy( __TMP_PATH__ . '/~folder' ));
			$this->assertNull( FileSystem::copy( __TMP_PATH__ . '/~folder' ));
			$this->assertNull( FileSystem::copy( __TMP_PATH__ . '/~folder', __TMP_PATH__ . '/~folder2' ));
			$this->assertTrue( is_dir( __TMP_PATH__ . '/Copy of ~folder' ));
			$this->assertTrue( is_dir( __TMP_PATH__ . '/Copy (2) of ~folder' ));
			$this->assertFalse( is_dir( __TMP_PATH__ . '/Copy (3) of ~folder' ));
			$this->assertTrue( file_exists( __TMP_PATH__ . '/~folder2' ));
			$this->assertTrue( file_exists( __TMP_PATH__ . '/Copy of ~folder/~file' ));
			$this->assertTrue( file_exists( __TMP_PATH__ . '/Copy (2) of ~folder/~file' ));
			$this->assertTrue( file_exists( __TMP_PATH__ . '/~folder2/~file' ));

			// move file
			$this->assertNull( FileSystem::move( __TMP_PATH__ . '/~file', __TMP_PATH__ . '/~movedfile' ));
			$this->assertTrue( is_file( __TMP_PATH__ . '/~movedfile' ));
			$this->assertFalse( is_file( __TMP_PATH__ . '/~file' ));

			// move folder
			$this->assertNull( FileSystem::move( __TMP_PATH__ . '/~folder', __TMP_PATH__ . '/~movedfolder' ));
			$this->assertTrue( is_file( __TMP_PATH__ . '/~movedfolder/~file' ));
			$this->assertTrue( is_dir( __TMP_PATH__ . '/~movedfolder' ));
			$this->assertFalse( is_dir( __TMP_PATH__ . '/~folder' ));

			// rename file
			$this->assertNull( FileSystem::rename( __TMP_PATH__ . '/~movedfile', __TMP_PATH__ . '/~file' ));
			$this->assertTrue( is_file( __TMP_PATH__ . '/~file' ));
			$this->assertFalse( is_file( __TMP_PATH__ . '/~movedfile' ));

			// move folder
			$this->assertNull( FileSystem::move( __TMP_PATH__ . '/~movedfolder', __TMP_PATH__ . '/~folder' ));
			$this->assertTrue( is_dir( __TMP_PATH__ . '/~folder' ));
			$this->assertFalse( is_dir( __TMP_PATH__ . '/~movedfolder' ));

			// get file
			$file = new File( __TMP_PATH__ . '/~file' );
			$this->assertTrue( $file->name === '~file' );
			$this->assertTrue( $file->path === __TMP_PATH__ . '/~file' );
			$this->assertTrue( $file->size === 0 );
			$this->assertNull( $file->rename( __TMP_PATH__ . '/~fileX' ));
			$this->assertNull( $file->copy() );
			$this->assertNull( $file->move( __TMP_PATH__ . '/~fileY' ));

			$this->assertTrue( is_file( __TMP_PATH__ . '/~fileY' ));
			$this->assertFalse( is_file( __TMP_PATH__ . '/~fileX' ));
			$this->assertFalse( is_file( __TMP_PATH__ . '/~file' ));
			$this->assertTrue( is_file( __TMP_PATH__ . '/Copy of ~fileX' ));

			// get folder
			$folder = new Folder( __TMP_PATH__ . '/~folder' );
			$this->assertTrue( $folder->name === '~folder' );
			$this->assertTrue( $folder->path === __TMP_PATH__ . '/~folder' );
			$this->assertTrue( $folder->files[0]->name === '~file' );
			$this->assertTrue( sizeof( $folder->folders === 0 ));

			$this->assertNull( $folder->rename( __TMP_PATH__ . '/~folderX' ));
			$this->assertNull( $folder->copy() );
			$this->assertNull( $folder->move( __TMP_PATH__ . '/~folderY' ));

			$this->assertTrue( is_dir( __TMP_PATH__ . '/~folderY' ));
			$this->assertFalse( is_dir( __TMP_PATH__ . '/~folderX' ));
			$this->assertFalse( is_dir( __TMP_PATH__ . '/~folder' ));
			$this->assertTrue( is_dir( __TMP_PATH__ . '/Copy of ~folderX' ));

			// delete file
			$this->assertNull( $file->delete() );
			$this->assertFalse( file_exists( __TMP_PATH__ . '/~fileY' ));

			// delete folder
			$this->assertNull( $folder->delete() );
			$this->assertFalse( file_exists( __TMP_PATH__ . '/~folderY' ));
			$this->assertFalse( file_exists( __TMP_PATH__ . '/~folderY/~file' ));
		}

		function testFileStream() {
			// create
			$file = new FileStream();
			$file->open( __TMP_PATH__ . '/~blank' );
			$this->assertTrue( $file->writeln( '0' ));
			$this->assertTrue( $file->write( '1' ));
			$this->assertTrue( $file->write( '23456789' ));
			$file->close();
			$this->assertTrue( file_get_contents( __TMP_PATH__ . '/~blank' ) === "0\n123456789" );

			$this->assertTrue( $file->open( __TMP_PATH__ . '/~blank', 'r' ));
			$this->assertTrue( $file->readln()      === "0\n" );
			$this->assertTrue( $file->readln()      === "123456789" );
			$this->assertTrue( $file->getPosition() === 11 );
			$this->assertNull( $file->setBOS() );
			$this->assertTrue( $file->read(5)       === "0\n123" );
			$this->assertTrue( $file->eos()         === FALSE );
			$this->assertTrue( $file->read()        === "456789" );
			$this->assertTrue( $file->eos()         === TRUE );
		}
	}
?>