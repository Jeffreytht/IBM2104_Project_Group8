 function saveCourse(){
	$("#courseAppend").submit();
}

function cancelCourse(element)
{
	element.parentElement.parentElement.remove();
}

function deleteCourse(element)
{
	var proceed = confirm("Delete this course?");
	if(proceed)
	{	
		var parent = element.parentElement
		var input = document.createElement("input");
		input.value = element.parentElement.getElementsByTagName("p")[0].innerHTML;
		input.type="hidden";
		input.name = "courseID";
		parent.appendChild(input);
		saveCourse();
	}
}

$(document).ready(function(){

	$("#addInstitute").click(function(){
		let index = $("#courseDetail tr").last().find("td").first().html();
		index++;

		let tRow = "<tr>";
		tRow += "<td>" + index + "</td>";
		tRow += "<td><select class='form-control' name='courseID'>";
		tRow +=	$("#courseSelection").html();
		tRow += "</select></td>";
		tRow += "<td><input type='number' class='form-control' name='duration' placeholder='Duration'/></td>";
		tRow += "<td><input type='number' class='form-control' name='fee' placeholder='Fee'/></td>";
		tRow += "<td><input type='hidden' name='newCourse'/></td>";
		tRow += "<td><span class='pointer' onclick='saveCourse()'>Save</span> |<span class='pointer' onclick='cancelCourse(this)'>Cancel</span></td>";
		tRow += "</tr>";
		$("#courseDetail").append(tRow);
	});


});