<?php
/** \file
 * This is part of the Catalog module -- it manages categories.
 *
 * Version 6 (added getParent(), made the old version getParentId())
 *
 * Copyright 2003-2004 303software.  All Rights Reserved.  This software
 * is protected by U.S. copyright law and international copyright treaty.
 * This file is licensed for your use, but you may not redistribute it.
 * See copyright.txt for details.
 */

/**
 */
require_once('db.class.php');
db::getinstance('../../config.php');
class DbTrees extends  db {
	


	/** Private reference to the database. */
	var $m_db;

	/** Private variables that override category_cfg located in _site.php */
	var $m_dbTable;
	var $m_parentId;
	/**
	 */
	function __construct($dbTable, $parentId)
	{
		//parent::__construct('../../config.php');
		$this->m_dbTable = $dbTable;
		$this->m_parentId = $parentId;
	}

	/**
	 * Returns all the categories in a multi-dimentional array.
	 * Subcategories are stored in an added 'subcategories' value in each categories
	 * array, if there are no subcategories there is not a 'subcategories' value set.
	 */
	function getTree($id, $orderBy = NULL, $where = NULL)
	{
		$ret = array();

		$nodes = $this->getChildNodes($id, $orderBy, $where);
		if (!is_array($nodes)) return false;

		foreach ($nodes as $node)
		{
			$children = $this->getTree($node[$this->primarykey($this->m_dbTable)], $orderBy, $where);
			if (is_array($children)) $node['children'] = $children;

			$ret[] = $node;
		}

		return $ret;
	}

	/**
	 * Returns one level of the category structure in an array.
	 * Passing 0 for $parentId returns top level categories.
	 *
	 * This does not recurse into the "tree" of categories; use getCategoryTree()
	 */
	function getChildNodes($parentId, $orderBy = NULL, $where = NULL)
	{
		if ($where && strtoupper(substr($where, 0, 6)) == 'WHERE ')
			$where = 'AND '.substr($where, 6, strlen($where) - 6);
		elseif ($where && strtoupper(substr($where, 0, 4)) != 'AND ')
			$where = 'AND '.$where;

		if ($orderBy) $orderBy = "ORDER BY $orderBy";
		$sql = "$this->m_parentId = $parentId $where $orderBy";
		
		$nodes = $this->select($this->m_dbTable,"",'*', $sql);

			
		if (!$this->num_rows($nodes))
			 return NULL;
		
		$ret = array();
		while ($row = $this->fetch_array($nodes))
			$ret[] = $row;
		
		return $ret;
	}

}

?>
