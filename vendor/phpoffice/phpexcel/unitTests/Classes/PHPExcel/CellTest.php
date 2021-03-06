<?php


require_once 'testDataFileIterator.php';

class CellTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        if (!defined('PHPEXCEL_ROOT')) {
            define('PHPEXCEL_ROOT', APPLICATION_PATH . '/');
        }
        require_once(PHPEXCEL_ROOT . '\PhpOffice\PhpSpreadsheet\Spreadsheet/Autoloader.php');
	}

    /**
     * @dataProvider providerColumnString
     */
	public function testColumnIndexFromString()
	{
		$args = func_get_args();
		$expectedResult = array_pop($args);
		$result = call_user_func_array(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','columnIndexFromString'),$args);
		$this->assertEquals($expectedResult, $result);
	}

    public function providerColumnString()
    {
    	return new testDataFileIterator('rawTestData/ColumnString.data');
	}

    public function testColumnIndexFromStringTooLong()
	{
		$cellAddress = 'ABCD';
		try {
			$result = call_user_func(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','columnIndexFromString'),$cellAddress);
		} catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
			$this->assertEquals($e->getMessage(), 'Column string index can not be longer than 3 characters');
			return;
		}
		$this->fail('An expected exception has not been raised.');
	}

    public function testColumnIndexFromStringTooShort()
	{
		$cellAddress = '';
		try {
			$result = call_user_func(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','columnIndexFromString'),$cellAddress);
		} catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
			$this->assertEquals($e->getMessage(), 'Column string index can not be empty');
			return;
		}
		$this->fail('An expected exception has not been raised.');
	}

    /**
     * @dataProvider providerColumnIndex
     */
	public function testStringFromColumnIndex()
	{
		$args = func_get_args();
		$expectedResult = array_pop($args);
		$result = call_user_func_array(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','stringFromColumnIndex'),$args);
		$this->assertEquals($expectedResult, $result);
	}

    public function providerColumnIndex()
    {
    	return new testDataFileIterator('rawTestData/ColumnIndex.data');
	}

    /**
     * @dataProvider providerCoordinates
     */
	public function testCoordinateFromString()
	{
		$args = func_get_args();
		$expectedResult = array_pop($args);
		$result = call_user_func_array(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','coordinateFromString'),$args);
		$this->assertEquals($expectedResult, $result);
	}

    public function providerCoordinates()
    {
    	return new testDataFileIterator('rawTestData/CellCoordinates.data');
	}

    public function testCoordinateFromStringWithRangeAddress()
	{
		$cellAddress = 'A1:AI2012';
		try {
			$result = call_user_func(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','coordinateFromString'),$cellAddress);
		} catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
			$this->assertEquals($e->getMessage(), 'Cell coordinate string can not be a range of cells');
			return;
		}
		$this->fail('An expected exception has not been raised.');
	}

    public function testCoordinateFromStringWithEmptyAddress()
	{
		$cellAddress = '';
		try {
			$result = call_user_func(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','coordinateFromString'),$cellAddress);
		} catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
			$this->assertEquals($e->getMessage(), 'Cell coordinate can not be zero-length string');
			return;
		}
		$this->fail('An expected exception has not been raised.');
	}

    public function testCoordinateFromStringWithInvalidAddress()
	{
		$cellAddress = 'AI';
		try {
			$result = call_user_func(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','coordinateFromString'),$cellAddress);
		} catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
			$this->assertEquals($e->getMessage(), 'Invalid cell coordinate '.$cellAddress);
			return;
		}
		$this->fail('An expected exception has not been raised.');
	}

    /**
     * @dataProvider providerAbsoluteCoordinates
     */
	public function testAbsoluteCoordinateFromString()
	{
		$args = func_get_args();
		$expectedResult = array_pop($args);
		$result = call_user_func_array(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','absoluteCoordinate'),$args);
		$this->assertEquals($expectedResult, $result);
	}

    public function providerAbsoluteCoordinates()
    {
    	return new testDataFileIterator('rawTestData/CellAbsoluteCoordinate.data');
	}

    public function testAbsoluteCoordinateFromStringWithRangeAddress()
	{
		$cellAddress = 'A1:AI2012';
		try {
			$result = call_user_func(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','absoluteCoordinate'),$cellAddress);
		} catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
			$this->assertEquals($e->getMessage(), 'Cell coordinate string can not be a range of cells');
			return;
		}
		$this->fail('An expected exception has not been raised.');
	}

    /**
     * @dataProvider providerAbsoluteReferences
     */
	public function testAbsoluteReferenceFromString()
	{
		$args = func_get_args();
		$expectedResult = array_pop($args);
		$result = call_user_func_array(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','absoluteReference'),$args);
		$this->assertEquals($expectedResult, $result);
	}

    public function providerAbsoluteReferences()
    {
    	return new testDataFileIterator('rawTestData/CellAbsoluteReference.data');
	}

    public function testAbsoluteReferenceFromStringWithRangeAddress()
	{
		$cellAddress = 'A1:AI2012';
		try {
			$result = call_user_func(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','absoluteReference'),$cellAddress);
		} catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
			$this->assertEquals($e->getMessage(), 'Cell coordinate string can not be a range of cells');
			return;
		}
		$this->fail('An expected exception has not been raised.');
	}

    /**
     * @dataProvider providerSplitRange
     */
	public function testSplitRange()
	{
		$args = func_get_args();
		$expectedResult = array_pop($args);
		$result = call_user_func_array(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','splitRange'),$args);
		foreach($result as $key => $split) {
			if (!is_array($expectedResult[$key])) {
				$this->assertEquals($expectedResult[$key], $split[0]);
			} else {
				$this->assertEquals($expectedResult[$key], $split);
			}
		}
	}

    public function providerSplitRange()
    {
    	return new testDataFileIterator('rawTestData/CellSplitRange.data');
	}

    /**
     * @dataProvider providerBuildRange
     */
	public function testBuildRange()
	{
		$args = func_get_args();
		$expectedResult = array_pop($args);
		$result = call_user_func_array(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','buildRange'),$args);
		$this->assertEquals($expectedResult, $result);
	}

    public function providerBuildRange()
    {
    	return new testDataFileIterator('rawTestData/CellBuildRange.data');
	}

    public function testBuildRangeInvalid()
	{
		$cellRange = '';
		try {
			$result = call_user_func(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','buildRange'),$cellRange);
		} catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
			$this->assertEquals($e->getMessage(), 'Range does not contain any information');
			return;
		}
		$this->fail('An expected exception has not been raised.');
	}

    /**
     * @dataProvider providerRangeBoundaries
     */
	public function testRangeBoundaries()
	{
		$args = func_get_args();
		$expectedResult = array_pop($args);
		$result = call_user_func_array(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','rangeBoundaries'),$args);
		$this->assertEquals($expectedResult, $result);
	}

    public function providerRangeBoundaries()
    {
    	return new testDataFileIterator('rawTestData/CellRangeBoundaries.data');
	}

    /**
     * @dataProvider providerRangeDimension
     */
	public function testRangeDimension()
	{
		$args = func_get_args();
		$expectedResult = array_pop($args);
		$result = call_user_func_array(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','rangeDimension'),$args);
		$this->assertEquals($expectedResult, $result);
	}

    public function providerRangeDimension()
    {
    	return new testDataFileIterator('rawTestData/CellRangeDimension.data');
	}

    /**
     * @dataProvider providerGetRangeBoundaries
     */
	public function testGetRangeBoundaries()
	{
		$args = func_get_args();
		$expectedResult = array_pop($args);
		$result = call_user_func_array(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','getRangeBoundaries'),$args);
		$this->assertEquals($expectedResult, $result);
	}

    public function providerGetRangeBoundaries()
    {
    	return new testDataFileIterator('rawTestData/CellGetRangeBoundaries.data');
	}

    /**
     * @dataProvider providerExtractAllCellReferencesInRange
     */
	public function testExtractAllCellReferencesInRange()
	{
		$args = func_get_args();
		$expectedResult = array_pop($args);
		$result = call_user_func_array(array('\PhpOffice\PhpSpreadsheet\Cell\Cell','extractAllCellReferencesInRange'),$args);
		$this->assertEquals($expectedResult, $result);
	}

    public function providerExtractAllCellReferencesInRange()
    {
    	return new testDataFileIterator('rawTestData/CellExtractAllCellReferencesInRange.data');
	}

}
