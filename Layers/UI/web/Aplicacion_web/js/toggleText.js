function toggle() {
	var ele = document.getElementById("toggleText");
	var text = document.getElementsByClassName("displayText");
	if(ele.style.display == "block") {
    		ele.style.display = "none";		
  	}
	else {
		ele.style.display = "block";		
	}
} 