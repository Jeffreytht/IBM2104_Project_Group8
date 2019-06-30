function addStar5()
{
	$("#star5").removeClass("far");
	$("#star5").addClass("fas");
	addStar4();
}

function addStar4()
{
	$("#star4").removeClass("far");
	$("#star4").addClass("fas");
	addStar3();
}

function addStar3()
{
	$("#star3").removeClass("far");
	$("#star3").addClass("fas");
	addStar2();
}

function addStar2()
{
	$("#star2").removeClass("far");
	$("#star2").addClass("fas");
	addStar1();
}

function addStar1()
{
	$("#star1").removeClass("far");
	$("#star1").addClass("fas");
}

function removeStar1()
{
	$("#star1").removeClass("fas");
	$("#star1").addClass("far");
}

function removeStar2()
{
	$("#star2").removeClass("fas");
	$("#star2").addClass("far");
	removeStar1();
}

function removeStar3()
{
	$("#star3").removeClass("fas");
	$("#star3").addClass("far");
	removeStar2();
}

function removeStar4()
{
	$("#star4").removeClass("fas");
	$("#star4").addClass("far");
	removeStar3();
}

function removeStar5()
{
	$("#star5").removeClass("fas");
	$("#star5").addClass("far");
	removeStar4();
}

function click1()
{
	$("#starValue").val("1");
	$("#starForm").submit();
}

function click2()
{
	$("#starValue").val("2");
	$("#starForm").submit();
}


function click3()
{
	$("#starValue").val("3");
	$("#starForm").submit();
}

function click4()
{
	$("#starValue").val("4");
	$("#starForm").submit();
}

function click5()
{
	$("#starValue").val("5");
	$("#starForm").submit();
}

var imageWidth;

$(document).ready(function(){
	$("#star1").mouseover(addStar1);
	$("#star2").mouseover(addStar2);
	$("#star3").mouseover(addStar3);
	$("#star4").mouseover(addStar4);
	$("#star5").mouseover(addStar5);
	$("#star1").mouseleave(removeStar1);
	$("#star2").mouseleave(removeStar2);
	$("#star3").mouseleave(removeStar3);
	$("#star4").mouseleave(removeStar4);
	$("#star5").mouseleave(removeStar5);
	$("#star1").click(click1);
	$("#star2").click(click2);
	$("#star3").click(click3);
	$("#star4").click(click4);
	$("#star5").click(click5);

	imageWidth = $("#loadGallery").width();
	$(".galImage").height(imageWidth);
	
});

