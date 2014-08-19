<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Table Editor</title>
	<link rel="stylesheet" type="text/css" href="../../richtextarea.css">
	<script language="JavaScript">
	<!--

	// get the opener information from the query string
	window.rta = window.opener.RichTextarea.getInstance('<?= $_GET['rta_id'] ?>');
	window.region = '<?= $_GET['rta_region'] ?>';

	var activeCell = null;	// global variable to store currently selected table cell
	var tableWidth = 2;		// global for table width
	var tableHeight = 2;	// global for table height
	var tableHTML = "";		// global for dynamic HTML

	function getRealX(cell)
	// returns the x coordinate of the upper left corner of cell
	{
		var idAtt = cell.attributes.getNamedItem('id').nodeValue;
		return parseInt(idAtt.substring(1, idAtt.indexOf('-')));
	}

	function getRealY(cell)
	// returns the y coordinate of the upper left corner of cell
	{
		var idAtt = cell.attributes.getNamedItem('id').nodeValue;
		return parseInt(idAtt.substring(idAtt.indexOf('-') + 1, idAtt.length));
	}

	function getCell(x, y)
	// returns cell at coordinate (x,y)
	{
		var idstring = 'c' + x + '-' + y;
		return document.getElementById(idstring);
	}

	function getCellContaining(x, y)
	// returns the cell containing coordinate (x,y)
	{
		if(getCell(x, y) != null) return getCell(x, y);
		else
		{
			// find correct cell
			var found = false;
			var searchY = y;
			while (found == false && -1 < searchY && searchY < tableHeight)
			{
				var searchRow = getRowByIndex(searchY);
				for (var ctr=0; ctr<searchRow.childNodes.length; ctr++)
				{
					if (searchRow.childNodes[ctr].attributes != null)
					{
						var xMin = getRealX(searchRow.childNodes[ctr]);
						var cSpan = 1;
						if (searchRow.childNodes[ctr].attributes.getNamedItem('colspan') != null) cSpan = parseInt(searchRow.childNodes[ctr].attributes.getNamedItem('colspan').nodeValue);
						var xMax = cSpan + xMin - 1;
						if  ((xMin <= x) && (x <= xMax))
						{
							// x span of cell is within the correct range
							var rSpan = 1;
							if (searchRow.childNodes[ctr].attributes.getNamedItem('rowspan') != null) rSpan = parseInt(searchRow.childNodes[ctr].attributes.getNamedItem('rowspan').nodeValue);
							if ((searchY <= y) && (y <= (rSpan + searchY -1)))
							{
								return searchRow.childNodes[ctr];
							}
						}
					}
				}
				searchY--;
			}
		}
		// cell lies outside table bounds
		return null;
	}

	function getRowByIndex(index)
	{
		// returns row at index
		var idstring = 'r'+index;
		return document.getElementById(idstring);
	}

	function getCellRow(cell)
	{
		// returns row containing cell
		return document.getElementById(('r' + getRealY(cell)));
	}

	function getNextCellOnRow(cell, y)
	// returns the cell to the right of cell on row y.  returns null if no cell found
	{
		var cspan = 1;
		// column span calculations
		if (cell.attributes.getNamedItem('colspan') != null) cspan = parseInt(cell.attributes.getNamedItem('colspan').nodeValue);
		var targetx = getRealX(cell) + cspan;
		var testCell = getCellContaining(targetx, y);
		var x = 0;
		while (testCell != null)
		{
			if (getRealY(testCell) == y) return testCell;
			x++;
			testCell = getCellContaining(targetx + x, y);
		}
		return null;
	}

	function setOptions(cell)
	// enables/disables buttons and text fields based on interface status
	{
		// disable shrink columns if there's only 1 column in the table
		if (tableWidth == 1) document.getElementById('decCols').disabled = true;
		else document.getElementById('decCols').disabled = false;
		document.getElementById('incCols').disabled = false;
		// disable shrink rows if there's only 1 row in the table
		if (tableHeight == 1) document.getElementById('decRows').disabled = true;
		else document.getElementById('decRows').disabled = false;
		document.getElementById('incRows').disabled = false;
		if (cell != null)
		{
			var cspan = 1;
			var rspan = 1;
			// column span calculations
			if (cell.attributes.getNamedItem('colspan') != null) cspan = parseInt(cell.attributes.getNamedItem('colspan').nodeValue);
			// row span calculations
			if (cell.attributes.getNamedItem('rowspan') != null) rspan = parseInt(cell.attributes.getNamedItem('rowspan').nodeValue);
			// colspan decrease
			if (cspan > 1) document.getElementById('decColspan').disabled = false;
			else document.getElementById('decColspan').disabled = true;
			// colspan increase
			if (getRealX(cell) + cspan >= tableWidth) document.getElementById('incColspan').disabled = true;
			else document.getElementById('incColspan').disabled = false;
			for (var x=0; x<rspan; x++) // check for expanded cells to right
			{
				testCell = getCellContaining(getRealX(cell) + cspan, getRealY(cell) + x);
				if (testCell != null)
				{
					if (testCell.attributes.getNamedItem('colspan') != null && (parseInt(testCell.attributes.getNamedItem('colspan').nodeValue) > 1)) document.getElementById('incColspan').disabled = true;
					if (testCell.attributes.getNamedItem('rowspan') != null && (parseInt(testCell.attributes.getNamedItem('rowspan').nodeValue) > 1)) document.getElementById('incColspan').disabled = true;
				}
			}
			// rowspan decrease
			if (rspan > 1) document.getElementById('decRowspan').disabled = false;
			else document.getElementById('decRowspan').disabled = true;
			// rowspan increase
			if (getRealY(cell) + rspan >= tableHeight) document.getElementById('incRowspan').disabled = true;
			else document.getElementById('incRowspan').disabled = false;
			for (var x=0; x<cspan; x++) // check for expanded cells below
			{
				testCell = getCellContaining(getRealX(cell) + x,getRealY(cell) + rspan);
				if (testCell != null)
				{
					if (testCell.attributes.getNamedItem('colspan') != null && (parseInt(testCell.attributes.getNamedItem('colspan').nodeValue) > 1)) document.getElementById('incRowspan').disabled = true;
					if (testCell.attributes.getNamedItem('rowspan') != null && (parseInt(testCell.attributes.getNamedItem('rowspan').nodeValue) > 1)) document.getElementById('incRowspan').disabled = true;
				}
			}
			document.getElementById('cellData').value = cell.childNodes[0].innerHTML;
			if(cell.attributes.getNamedItem('width') != null) document.getElementById('colwidth').value = cell.attributes.getNamedItem('width').value;
			else document.getElementById('colwidth').value = '';
			if(cell.attributes.getNamedItem('align') != null) document.getElementById('align').value = cell.attributes.getNamedItem('align').value;
			else document.getElementById('align').value = 'none';
			if(cell.attributes.getNamedItem('valign') != null) document.getElementById('valign').value = cell.attributes.getNamedItem('valign').value;
			else document.getElementById('valign').value = 'none';
			// other cell modification options
			document.getElementById('align').disabled = false;
			document.getElementById('valign').disabled = false;
			document.getElementById('cellData').disabled = false;
		}

	}

	function setCellProperties(changeItem)
	{
		var value = document.getElementById(changeItem).value;
		if (changeItem == 'align')
		{
			if (value == 'none') activeCell.removeAttribute('align');
			else activeCell.setAttribute('align',value);
		}
		else if (changeItem == 'valign')
		{
			if (value == 'none') activeCell.removeAttribute('valign');
			else
			{
				activeCell.setAttribute('valign', value);
				activeCell.style.verticalAlign = value;
			}
		}
		else if (changeItem == 'cellData')
		{
			activeCell.innerHTML = value;
		}
		else if (changeItem == 'colwidth')
		{
			if (value == '0' || value == "0%" || value == "0px") activeCell.removeAttribute('width');
			else activeCell.setAttribute('width',value);
		}
	}

	function setTableProperties(changeItem)
	{
		var value = document.getElementById(changeItem).value;
		var tableref = document.getElementById('tabletomod');
		if (changeItem == 'tableBorder')
		{
			tableref.setAttribute('border',value);
		}
		else if (changeItem == 'tableCellPadding')
		{
			tableref.setAttribute('cellpadding',value);
		}
		else if (changeItem == 'tableCellSpacing')
		{
			tableref.setAttribute('cellspacing',value);
		}
		else if (changeItem == 'twidth')
		{
			tableref.setAttribute('width',value);
		}
	}

	function setActiveCell(e)
	{
		if (!e) e = window.event;
		cellTest = (e.srcElement) ? e.srcElement : e.target;
		if (cellTest.nodeName == "TD")
		{
			if (activeCell != null) activeCell.style.backgroundColor="#ffffff";
			activeCell = cellTest;
			activeCell.style.backgroundColor="#0000ff";
			// set active cell data buttons to reflect selected cell options
			setOptions(activeCell);
		}
	}

	function setEvents()
	{
		var tableToMod = document.getElementById('tabletomod');
		if (tableToMod) tableToMod.onclick = setActiveCell;
	}

	function resizeTable(resizeCols, increaseSize)
	// contains all functionality for adding and removing rows and columns from the table
	{
		if (resizeCols == true)
		{
			if (increaseSize == true)
			{
				for (var x=0; x<tableHeight; x++)
				{
					addcell=document.createElement("TD");
					getRowByIndex(x).appendChild(addcell);
					addcell.appendChild(document.createElement('br'));
					addcell.setAttribute('id','c'+tableWidth+'-'+x);
				}
				tableWidth++;
			}
			else
			{
				for (var x=0; x<tableHeight; x++)
				{
					testNode = getRowByIndex(x).lastChild;
					if(getRealX(testNode) == (tableWidth-1))
					{
						getCellRow(testNode).removeChild(testNode);
					}
					else if(getRealX(testNode)+testNode.attributes.getNamedItem('colspan').nodeValue >= tableWidth)
					{
						//testNode.attributes.getNamedItem('colspan').nodeValue--;
						testNode.colSpan--;
					}
				}
				tableWidth--;
			}
		}
		else
		{
			if (increaseSize == true)
			{
				addrow=document.createElement("TR");
				document.getElementById('modtbody').appendChild(addrow);
				for(var x=0;x<tableWidth;x++)
				{
					addcell=document.createElement("TD");
					addrow.appendChild(addcell);
					addrow.setAttribute('id','r'+tableHeight);
					addcell.appendChild(document.createElement('br'));
					addcell.setAttribute('id','c'+x+'-'+tableHeight);
				}
				tableHeight++;
			}
			else
			{
				for(var x=0;x<tableWidth;x++)
				{
					cellToMod = getCellContaining(0,tableHeight-1);
					if(cellToMod != null)
					{
						if(cellToMod.attributes.getNamedItem('rowSpan') != null) rSpan = cellToMod.attributes.getNamedItem('rowSpan').nodeValue;
						else rSpan = 1;
						if(rSpan > 1)
						{
							//cellToMod.attributes.getNamedItem('rowSpan').nodeValue--;
							cellToMod.rowSpan--;
						}
						else
						{
							getRowByIndex(tableHeight-1).removeChild(cellToMod);
						}
					}
				}
				document.getElementById('modtbody').removeChild(getRowByIndex(tableHeight-1));
				tableHeight--;
			}
		}
		setOptions(activeCell);
	}

	function resizeActiveCell(resizeCols, increaseSize)
	// contains all functionality for increasing and decreasing colspan and rowspan
	{
		var cspan = 1;
		var rspan = 1;
		// column span calculations
		if(activeCell.attributes.getNamedItem('colspan') != null) cspan=parseInt(activeCell.attributes.getNamedItem('colspan').nodeValue);
		// row span calculations
		if(activeCell.attributes.getNamedItem('rowspan') != null) rspan=parseInt(activeCell.attributes.getNamedItem('rowspan').nodeValue);
		if (resizeCols == true)
		{
			if (increaseSize == true)
			{
				for (x=0; x<rspan; x++)
				{
					getRowByIndex(getRealY(activeCell)+x).removeChild(getCell(getRealX(activeCell)+cspan,getRealY(activeCell)+x));
				}
				activeCell.colSpan++;
				//activeCell.setAttribute('colspan', cspan+1);
			}
			else
			{
				for (var x = 0; x < rspan; x++)
				{
					addcell=document.createElement("TD");
					nextcell = getNextCellOnRow(activeCell,getRealY(activeCell)+x);
					if (nextcell != null)
					{
						getRowByIndex(getRealY(activeCell)+x).insertBefore(addcell,nextcell);
					}
					else
					{
						getRowByIndex(getRealY(activeCell)+x).appendChild(addcell);
					}
					addcell.appendChild(document.createElement('br'));
					addcell.setAttribute('id','c'+(getRealX(activeCell)+cspan-1)+'-'+(getRealY(activeCell)+x));
				}
				activeCell.colSpan--;
				//activeCell.setAttribute('colspan', cspan-1);
			}
		}
		else
		{
			if (increaseSize == true)
			{
				modRow=getRowByIndex(getRealY(activeCell)+rspan);
				for (x=0; x<cspan; x++)
				{
					modRow.removeChild(getCell(getRealX(activeCell)+x,getRealY(activeCell)+rspan));
				}
				//activeCell.setAttribute('rowspan', rspan+1);
				activeCell.rowSpan++;
			}
			else
			{
				nextcell = getNextCellOnRow(activeCell,getRealY(activeCell)+rspan-1);
				for (var x=0; x<cspan; x++)
				{
					addcell=document.createElement("TD");
					if(nextcell != null)
					{
						getRowByIndex(getRealY(activeCell)+rspan-1).insertBefore(addcell,nextcell);
					}
					else
					{
						getRowByIndex(getRealY(activeCell)+rspan-1).appendChild(addcell);
						nextcell = addcell;
					}
					addcell.appendChild(document.createElement('br'));
					addcell.setAttribute('id','c'+(getRealX(activeCell)+x)+'-'+(getRealY(activeCell)+rspan-1));
				}
				activeCell.rowSpan--;
				//activeCell.setAttribute('rowspan', rspan-1);
			}
		}
		setOptions(activeCell);
	}

	function addIndent(indent)
	{
		for(x=0;x<indent;x++)
		{
			document.writeln(' ');
		}
	}

	function walkElement(element, indent)
	{
		var elementNodes;
		var x;

		if(element.hasChildNodes())
		{
			tableHTML += '<' + element.nodeName;
			if(element.nodeName == 'TD')
			{
				if(element.attributes.getNamedItem('width') != null)
				{
					tableHTML += ' width="' + element.attributes.getNamedItem('width').nodeValue + '"';
				}
				if(element.attributes.getNamedItem('rowspan') != null)
				{
					tableHTML += ' rowspan="' + element.attributes.getNamedItem('rowspan').nodeValue + '"';
				}
				if(element.attributes.getNamedItem('colspan') != null)
				{
					tableHTML += ' colspan="' + element.attributes.getNamedItem('colspan').nodeValue + '"';
				}
				if(element.attributes.getNamedItem('align') != null)
				{
					tableHTML += ' align="' + element.attributes.getNamedItem('align').nodeValue + '"';
				}
				if(element.attributes.getNamedItem('valign') != null)
				{
					tableHTML += ' valign="' + element.attributes.getNamedItem('valign').nodeValue + '"';
				}
			}
			else if(element.nodeName == 'TABLE')
			{
				tableHTML += ' width="' + element.attributes.getNamedItem('width').nodeValue + '"';
				tableHTML += ' border="' + element.attributes.getNamedItem('border').nodeValue + '"';
				tableHTML += ' cellpadding="' + element.attributes.getNamedItem('cellpadding').nodeValue + '"';
				tableHTML += ' cellspacing="' + element.attributes.getNamedItem('cellspacing').nodeValue + '"';
			}
			tableHTML += '>';
			elementNodes=element.childNodes;

			for(x = 0; x<elementNodes.length; x++)
			{
				walkElement(elementNodes[x],indent+1);
			}
			tableHTML += '</' + element.nodeName + '>'
		}
		else
		{
			if(element.nodeName == '#text')
			{
				tableHTML += element.data;
			}
		}
	}

	function inserttable()
	{
		tableHTML = '';
		var dtable = document.getElementById("tabletomod");
		walkElement(dtable, 0);

		window.rta.handleCommand('insertlink', tableHTML);

		window.close();
		return true;
	}

	var step = 1;
	var stepMax = 2;
	function goNext()
	{
		document.getElementById('step' + step).style.display = 'none';
		step++;
		document.getElementById('step' + step).style.display = 'block';

		if (step > 1) document.getElementById('step_back').disabled = false;
		if (step == stepMax)
		{
			document.getElementById('step_final').disabled = false;
			document.getElementById('step_next').disabled = true;
		}
	}

	function goBack()
	{
		document.getElementById('step' + step).style.display = 'none';
		step--;
		document.getElementById('step' + step).style.display = 'block';

		if (step == 1) document.getElementById('step_back').disabled = true;
		if (step != stepMax)
		{
			document.getElementById('step_final').disabled = true;
			document.getElementById('step_next').disabled = false;
		}
	}

	//-->
	</script>
</head>
<body id="rta_dialog" onload="setEvents()">

<form name="rta_form" id="rta_form" onSubmit="return inserttable();">
<table border="0" cellpadding="4" cellspacing="0" width="100%" height="100%">
<tr valign="top">
	<td width="100%" height="100%" style="padding:8px;">

		<table id="tabletomod" name="tabletomod" border="1" cellpadding="0" cellspacing="0" width="100%" height="100%">
		<tbody id="modtbody">
			<tr id="r0"><td id="c0-0"><br></td><td id="c1-0"><br></td></tr>
			<tr id="r1"><td id="c0-1"><br></td><td id="c1-1"><br></td></tr>
		</tbody>
		</table>

	</td>
	<td>

		<div id="step1">
		<table border="0" cellpadding="2" cellspacing="0" width="200">
		<tr>
			<td colspan="3"><h2>Table Attributes</h2></td>
		</tr>
		<tr>
			<td>Columns:</td>
			<td align="right"><input type="button" id="decCols" value="-" style="width:30px" onclick="javascript:resizeTable(true, false);" /></td>
			<td align="left"><input type="button" id="incCols" value="+" style="width:30px" onclick="javascript:resizeTable(true, true);" /></td>
		</tr>
		<tr>
			<td>Rows:</td>
			<td align="right"><input type="button" id="decRows" value="-" style="width:30px" onclick="javascript:resizeTable(false, false);" /></td>
			<td align="left"><input type="button" id="incRows" value="+" style="width:30px" onclick="javascript:resizeTable(false, true);" /></td>
		</tr>
		<tr>
			<td>Table Width:</td>
			<td colspan="2"><input type="text" id="twidth" size="3" value="100%" onChange="javascript:setTableProperties('twidth');"/></td>
		</tr>
		<tr>
			<td colspan="3"><h2>Cell Attributes</h2></td>
		</tr>
		<tr>
			<td>Column Span:</td>
			<td align="right"><input type="button" id="decColspan" value="-" style="width:30px" onclick="javascript:resizeActiveCell(true, false);" disabled="true" /></td>
			<td align="left"><input type="button" id="incColspan" value="+" style="width:30px" onclick="javascript:resizeActiveCell(true, true);" disabled="true" /></td>
		</tr>
		<tr>
			<td>Row Span:</td>
			<td align="right"><input type="button" id="decRowspan" value="-" style="width:30px" onclick="javascript:resizeActiveCell(false, false);" disabled="true" /></td>
			<td align="left"><input type="button" id="incRowspan" value="+" style="width:30px" onclick="javascript:resizeActiveCell(false, true);" disabled="true" /></td>
		</tr>
		</table>
		</div>

		<div id="step2" style="display:none">
		<table border="0" cellpadding="2" cellspacing="0" width="200">
		<tr>
			<td colspan="2"><h2>Table Attributes</h2></td>
		</tr>
		<tr>
			<td width="1"><label for="tableBorder">Border:</label></td>
			<td width="100%"><input type="text" id="tableBorder" size="2" onChange="javascript:setTableProperties('tableBorder');" /></td>
		</tr>
		<tr>
			<td><label for="tableCellSpacing">Cell Spacing:</label></td>
			<td><input type="text" id="tableCellSpacing" size="2" onChange="javascript:setTableProperties('tableCellSpacing');" /></td>
		</tr>
		<tr>
			<td><label for="tableCellPadding">Cell Padding:</label></td>
			<td><input type="text" id="tableCellPadding" size="2" onChange="javascript:setTableProperties('tableCellPadding');" /></td>
		</tr>
		</table>
		<table border="0" cellpadding="2" cellspacing="0" width="200">
		<tr>
			<td colspan="2"><h2>Cell Attributes</h2></td>
		</tr>
		<tr>
			<td><label for="colwidth">Column Width:</label></td>
			<td><input type="text" id="colwidth" size="2" onChange="javascript:setCellProperties('colwidth');" /></td>
		</tr>
		<tr>
			<td width="1"><label for="align">Alignment:</label></td>
			<td width="100%">
				<select name="align" id="align" onChange="javascript:setCellProperties('align');" disabled="true">
					<option value="none" selected="true">None</option>
					<option value="left">Left</option>
					<option value="center">Center</option>
					<option value="right">Right</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><label for="valign">Vertical Alignment:</label></td>
			<td>
				<select name="valign" id="valign" onChange="javascript:setCellProperties('valign');" disabled="true">
					<option value="none" selected="true">None</option>
					<option value="top">Top</option>
					<option value="middle">Center</option>
					<option value="bottom">Bottom</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">Content:</td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" id="cellData" onChange="javascript:setCellProperties('cellData');" disabled="true" style="width:100%" /></td>
		</tr>
		</table>
		</div>

	</td>
</tr>
<tr>
	<td colspan="2" height="1" id="rta_buttons">

		<hr>
		<input type="button" value="Cancel" onClick="window.close();" />
		<input type="button" value="&lt; Back" id="step_back" onClick="goBack()" disabled="true" />
		<input type="button" value="Next &gt;" id="step_next" onClick="goNext()" />
		<input type="submit" value="Insert Table" id="step_final" disabled="true" />

	</td>
</tr>
</table>
</form>

</body>
</html>
