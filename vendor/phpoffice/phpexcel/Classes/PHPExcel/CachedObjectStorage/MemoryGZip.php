<?php
/**
 * \PhpOffice\PhpSpreadsheet\Spreadsheet
 *
 * Copyright (c) 2006 - 2014 \PhpOffice\PhpSpreadsheet\Spreadsheet
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   \PhpOffice\PhpSpreadsheet\Spreadsheet
 * @package    \PhpOffice\PhpSpreadsheet\Spreadsheet_CachedObjectStorage
 * @copyright  Copyright (c) 2006 - 2014 \PhpOffice\PhpSpreadsheet\Spreadsheet (http://www.codeplex.com/\PhpOffice\PhpSpreadsheet\Spreadsheet)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */


/**
 * \PhpOffice\PhpSpreadsheet\Spreadsheet_CachedObjectStorage_MemoryGZip
 *
 * @category   \PhpOffice\PhpSpreadsheet\Spreadsheet
 * @package    \PhpOffice\PhpSpreadsheet\Spreadsheet_CachedObjectStorage
 * @copyright  Copyright (c) 2006 - 2014 \PhpOffice\PhpSpreadsheet\Spreadsheet (http://www.codeplex.com/\PhpOffice\PhpSpreadsheet\Spreadsheet)
 */
class \PhpOffice\PhpSpreadsheet\Spreadsheet_CachedObjectStorage_MemoryGZip extends \PhpOffice\PhpSpreadsheet\Collection\Cells implements \PhpOffice\PhpSpreadsheet\Spreadsheet_CachedObjectStorage_ICache {

    /**
     * Store cell data in cache for the current cell object if it's "dirty",
     *     and the 'nullify' the current cell object
     *
	 * @return	void
     * @throws	\PhpOffice\PhpSpreadsheet\Exception
     */
	protected function _storeData() {
		if ($this->_currentCellIsDirty && !empty($this->_currentObjectID)) {
			$this->_currentObject->detach();

			$this->_cellCache[$this->_currentObjectID] = gzdeflate(serialize($this->_currentObject));
			$this->_currentCellIsDirty = false;
		}
		$this->_currentObjectID = $this->_currentObject = null;
	}	//	function _storeData()


    /**
     * Add or Update a cell in cache identified by coordinate address
     *
     * @param	string			$pCoord		Coordinate address of the cell to update
     * @param	\PhpOffice\PhpSpreadsheet\Cell\Cell	$cell		Cell to update
	 * @return	\PhpOffice\PhpSpreadsheet\Cell\Cell
     * @throws	\PhpOffice\PhpSpreadsheet\Exception
     */
	public function addCacheData($pCoord, \PhpOffice\PhpSpreadsheet\Cell\Cell $cell) {
		if (($pCoord !== $this->_currentObjectID) && ($this->_currentObjectID !== null)) {
			$this->_storeData();
		}

		$this->_currentObjectID = $pCoord;
		$this->_currentObject = $cell;
		$this->_currentCellIsDirty = true;

		return $cell;
	}	//	function addCacheData()


    /**
     * Get cell at a specific coordinate
     *
     * @param 	string 			$pCoord		Coordinate of the cell
     * @throws 	\PhpOffice\PhpSpreadsheet\Exception
     * @return 	\PhpOffice\PhpSpreadsheet\Cell\Cell 	Cell that was found, or null if not found
     */
	public function getCacheData($pCoord) {
		if ($pCoord === $this->_currentObjectID) {
			return $this->_currentObject;
		}
		$this->_storeData();

		//	Check if the entry that has been requested actually exists
		if (!isset($this->_cellCache[$pCoord])) {
			//	Return null if requested entry doesn't exist in cache
			return null;
		}

		//	Set current entry to the requested entry
		$this->_currentObjectID = $pCoord;
		$this->_currentObject = unserialize(gzinflate($this->_cellCache[$pCoord]));
        //    Re-attach this as the cell's parent
        $this->_currentObject->attach($this);

		//	Return requested entry
		return $this->_currentObject;
	}	//	function getCacheData()


	/**
	 * Get a list of all cell addresses currently held in cache
	 *
	 * @return  string[]
	 */
	public function getCellList() {
		if ($this->_currentObjectID !== null) {
			$this->_storeData();
		}

		return parent::getCellList();
	}


	/**
	 * Clear the cell collection and disconnect from our parent
	 *
	 * @return	void
	 */
	public function unsetWorksheetCells() {
		if(!is_null($this->_currentObject)) {
			$this->_currentObject->detach();
			$this->_currentObject = $this->_currentObjectID = null;
		}
		$this->_cellCache = array();

		//	detach ourself from the worksheet, so that it can then delete this object successfully
		$this->_parent = null;
	}	//	function unsetWorksheetCells()

}
