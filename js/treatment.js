/*	window.formRules = new Array(
		new Rule("treatment_name", "treatment name", true, "string|0,50"),
		new Rule("speciality1", "Ortho", false, "integer"),
		new Rule("speciality2", "PED", false, "integer"),
		new Rule("speciality3", "Cardio", false, "integer"),
		new Rule("speciality4", "Neuro", false, "integer"),
		new Rule("instruction", "default instructions", false, "string"),
		new Rule("sets", "sets", false, "string|0,20"),
		new Rule("reps", "reps", false, "string|0,20"),
		new Rule("hold", "hold", false, "string|0,20"),
		//new Rule("is_deleted", "status", false, "string|1,1"),
		//new Rule("is_locked", "locked", false, "string|1,1"),
		new Rule("status", "status", false, "string|1,1"),
		new Rule("pic1", "picture 1 file", false, "string|5,250"),
		new Rule("pic2", "picture 2 file", false, "string|5,250"),
		new Rule("pic3", "picture 3 file", false, "string|5,250"),
		new Rule("video", "video file", false, "string|5,250"));*/
function showCatSelectParam(id)
{
	if(!csw) var csw = window.open('index.php?action=PopupCategoryList&id='+id, 'catSelectWindow', 'width=850, height=650, status=no, toolbar=no, resizable=yes, scrollbars=yes');
	csw.focus();
}
function showCatSelect()
{
	if(!csw) var csw = window.open('index.php?action=PopupCategoryList', 'catSelectWindow', 'width=750, height=580, status=no, toolbar=no, resizable=yes, scrollbars=yes');
	csw.focus();
}

function toggleMediaDisplay(fieldName)
{
	var f = document.getElementById(fieldName);
	if(f)
	{
		if(f.style.display == 'none') f.style.display = 'block';
		else f.style.display = 'none';
	}
}