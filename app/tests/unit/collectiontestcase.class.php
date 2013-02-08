<?php
    namespace MyApp\Models;
    use \System\Collections\CollectionBase;
    use System\Collections\DictionaryBase;

    class Collection extends CollectionBase {}
    class Dictionary extends DictionaryBase {}

	class CollectionTestCase extends \System\Testcase\UnitTestCaseBase {

		function testCollectionArrayAccessor() {
			$collection = new Collection();

			$collection->add( 'apple' );
			$collection->add( 'banana' );
			$collection->add( 'cherry' );

			// isset
			$this->assertTrue( isset( $collection[0] ));
			$this->assertTrue( isset( $collection[1] ));
			$this->assertTrue( isset( $collection[2] ));
			$this->assertFalse( isset( $collection[3] ));

			// get
			$this->assertEqual( $collection[0], 'apple' );
			$this->assertEqual( $collection[1], 'banana' );
			$this->assertEqual( $collection[2], 'cherry' );

			$assertException = false;
			try{
				$i = $collection[3];
			}
			catch(\Exception $e) {
				$assertException = true;
			}
			$this->assertTrue( $assertException, 'Expected Exception' );

			// set
			$collection[1] = 'orange';
			$assertException = false;
			try{
				$collection[3] = 'foobar';
			}
			catch(\Exception $e) {
				$assertException = true;
			}
			$this->assertTrue( $assertException, 'Expected Exception' );

			$this->assertEqual( $collection[0], 'apple' );
			$this->assertEqual( $collection[1], 'orange' );
			$this->assertEqual( $collection[2], 'cherry' );
			$this->assertEqual( $collection->count, 3 );

			// unset
			unset( $collection[1] );
			$this->assertEqual( $collection[0], 'apple' );
			$this->assertEqual( $collection[1], 'cherry' );
			$this->assertEqual( $collection->count, 2 );
		}


		function testDictionaryArrayAccessor() {
			$dictionary = new Dictionary();

			$dictionary->add( 'color', 'red' );
			$dictionary->add( 'shape', 'round' );
			$dictionary->add( 'size', 'large' );

			// isset
			$this->assertTrue( isset( $dictionary['color'] ));
			$this->assertTrue( isset( $dictionary['shape'] ));
			$this->assertTrue( isset( $dictionary['size'] ));
			$this->assertFalse( isset( $dictionary['foobar'] ));

			// get
			$this->assertEqual( $dictionary['color'], 'red' );
			$this->assertEqual( $dictionary['shape'], 'round' );
			$this->assertEqual( $dictionary['size'], 'large' );

			$assertException = false;
			try{
				$i = $dictionary['foobar'];
			}
			catch(\Exception $e) {
				$assertException = true;
			}
			$this->assertTrue( $assertException, 'Expected Exception' );

			// set
			$dictionary['color'] = 'green';
			$dictionary['shape'] = 'square';
			$dictionary['size'] = 'small';

			$assertException = false;
			try{
				$dictionary['foobar'] = 'foobar';
			}
			catch(\Exception $e) {
				$assertException = true;
			}
			$this->assertTrue( $assertException, 'Expected Exception' );

			$this->assertEqual( $dictionary['color'], 'green' );
			$this->assertEqual( $dictionary['shape'], 'square' );
			$this->assertEqual( $dictionary['size'], 'small' );
			$this->assertEqual( $dictionary->count, 3 );

			// unset
			unset( $dictionary['shape'] );
			$this->assertEqual( $dictionary['color'], 'green' );
			$this->assertEqual( $dictionary['size'], 'small' );
			$this->assertEqual( $dictionary->count, 2 );

			$assertException = false;
			try{
				$i = $dictionary['shape'];
			}
			catch(\Exception $e) {
				$assertException = true;
			}
			$this->assertTrue( $assertException, 'Expected Exception' );
		}


		function testCollectionIterator() {
			$collection = new Collection();

			$collection->add( 'apple' );
			$collection->add( 'banana' );
			$collection->add( 'cherry' );

			$i=0;
			foreach( $collection as $fruit )
			{
				if($i==0)
				{
					$this->assertEqual( $fruit, 'apple' );
				}
				if($i==1)
				{
					$this->assertEqual( $fruit, 'banana' );
				}
				if($i==2)
				{
					$this->assertEqual( $fruit, 'cherry' );
				}
				$i++;
			}
		}


		function testDictionaryIterator() {
			$dictionary = new Dictionary();

			$dictionary->add( 'color', 'red' );
			$dictionary->add( 'shape', 'round' );
			$dictionary->add( 'size', 'large' );

			$i=0;
			foreach( $dictionary as $key => $value )
			{
				if($i==0)
				{
					$this->assertEqual( $key, 'color' );
					$this->assertEqual( $value, 'red' );
				}
				if($i==1)
				{
					$this->assertEqual( $key, 'shape' );
					$this->assertEqual( $value, 'round' );
				}
				if($i==2)
				{
					$this->assertEqual( $key, 'size' );
					$this->assertEqual( $value, 'large' );
				}
				$i++;
			}
		}

		function testInitializeCollection() {
			$colors = new Collection();
			$colors->add( 'red' );
			$colors->add( 'green' );
			$colors->add( 'blue' );
			$colors->add( 'yellow' );

			$colors2 = new Collection( array( 'blue', 'green', 'gold' ));
			$this->assertEqual( $colors2->itemAt( 0 ), 'blue' );
			$this->assertEqual( $colors2->itemAt( 1 ), 'green' );
			$this->assertEqual( $colors2->itemAt( 2 ), 'gold' );
			$this->assertEqual( $colors2->count, 3 );

			$colors3 = new Collection( $colors );
			$this->assertEqual( $colors3->itemAt( 0 ), 'red' );
			$this->assertEqual( $colors3->itemAt( 1 ), 'green' );
			$this->assertEqual( $colors3->itemAt( 2 ), 'blue' );
			$this->assertEqual( $colors3->itemAt( 3 ), 'yellow' );
			$this->assertEqual( $colors3->count, 4 );
		}

		function testInitializeDictionary() {
			$dictionary = new Dictionary();
			$dictionary->add( 'color', 'red' );
			$dictionary->add( 'shape', 'round' );
			$dictionary->add( 'size', 'large' );

			$dictionary2 = new Dictionary( array( 'color' => 'green', 'shape' => 'rectangle' ));
			$this->assertEqual( $dictionary2->itemAt( 0 ), 'green' );
			$this->assertEqual( $dictionary2->itemAt( 1 ), 'rectangle' );
			$this->assertEqual( $dictionary2->count, 2 );

			$dictionary3 = new Dictionary( $dictionary );
			$this->assertEqual( $dictionary3->itemAt( 0 ), 'red' );
			$this->assertEqual( $dictionary3->itemAt( 1 ), 'round' );
			$this->assertEqual( $dictionary3->itemAt( 2 ), 'large' );
			$this->assertEqual( $dictionary3->count, 3 );
		}


		function testCollections() {
			$colors = new Collection();

			$colors->add( 'red' );
			$colors->add( 'green' );
			$colors->add( 'blue' );
			$colors->add( 'yellow' );

			$this->assertEqual( $colors->count, 4 );
			$this->assertTrue( $colors->contains( 'blue' ));
			$this->assertFalse( $colors->contains( 'black' ));

			$this->assertEqual( $colors->itemAt( 0 ), 'red' );
			$this->assertEqual( $colors->itemAt( 1 ), 'green' );
			$this->assertEqual( $colors->itemAt( 2 ), 'blue' );
			$this->assertEqual( $colors->itemAt( 3 ), 'yellow' );

			$this->assertEqual( $colors->indexOf( 'red' ), 0 );
			$this->assertEqual( $colors->indexOf( 'green' ), 1 );
			$this->assertEqual( $colors->indexOf( 'blue' ), 2 );
			$this->assertEqual( $colors->indexOf( 'yellow' ), 3 );

			$this->assertTrue( $colors->remove( 'green' ));
			$this->assertFalse( $colors->remove( 'black' ));
			$this->assertEqual( $colors->count, 3 );
			$this->assertEqual( $colors->itemAt( 0 ), 'red' );
			$this->assertEqual( $colors->itemAt( 1 ), 'blue' );
			$this->assertEqual( $colors->itemAt( 2 ), 'yellow' );

			$colors->removeAt( 1 );

			$assertException = false;
			try{
				$colors->removeAt( 7 );
			}
			catch(\Exception $e) {
				$assertException = true;
			}
			$this->assertTrue( $assertException, 'Expected Exception' );

			$this->assertEqual( $colors->count, 2 );
			$this->assertEqual( $colors->itemAt( 0 ), 'red' );
			$this->assertEqual( $colors->itemAt( 1 ), 'yellow' );

			$colors->removeAll();
			$this->assertEqual( $colors->count, 0 );
		}

		function testDictionaries() {
			$dictionary = new Dictionary();

			$dictionary->add( 'color', 'red' );
			$dictionary->add( 'shape', 'round' );
			$dictionary->add( 'size', 'large' );

			$this->assertEqual( $dictionary->count, 3 );
			$this->assertTrue( $dictionary->contains( 'color' ));
			$this->assertFalse( $dictionary->contains( 'foobar' ));

			$this->assertEqual( $dictionary->itemAt( 0 ), 'red' );
			$this->assertEqual( $dictionary->itemAt( 1 ), 'round' );
			$this->assertEqual( $dictionary->itemAt( 2 ), 'large' );

			$assertException = false;
			try{
				$dictionary->itemAt( 3 );
			}
			catch(\Exception $e) {
				$assertException = true;
			}
			$this->assertTrue( $assertException, 'Expected Exception' );

			$this->assertEqual( $dictionary->indexOf( 'color' ), 0 );
			$this->assertEqual( $dictionary->indexOf( 'shape' ), 1 );
			$this->assertEqual( $dictionary->indexOf( 'size' ), 2 );
			$this->assertEqual( $dictionary->indexOf( 'width' ), -1 );

			$this->assertTrue( $dictionary->remove( 'shape' ));
			$this->assertFalse( $dictionary->remove( 'width' ));
			$this->assertEqual( $dictionary->count, 2 );
			$this->assertEqual( $dictionary->itemAt( 0 ), 'red' );
			$this->assertEqual( $dictionary->itemAt( 1 ), 'large' );

			$dictionary->removeAt( 1 );

			$assertException = false;
			try{
				$dictionary->removeAt( 7 );
			}
			catch(\Exception $e) {
				$assertException = true;
			}
			$this->assertTrue( $assertException, 'Expected Exception' );

			$this->assertEqual( $dictionary->count, 2 );
			$this->assertEqual( $dictionary->itemAt( 0 ), 'red' );

			$dictionary->removeAll();
			$this->assertEqual( $dictionary->count, 0 );
		}
	}
?>