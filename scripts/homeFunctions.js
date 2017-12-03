function openClose(divName) {
    var currentDiv = document.getElementById(divName);
    
    if (divName == "page1") {
        var div1 = document.getElementById("page2");
        //var div2 = document.getElementById("page3");
        var div3 = document.getElementById("page4");
    } else if (divName == "page2") {
        var div1 = document.getElementById("page1");
        //var div2 = document.getElementById("page3");
        var div3 = document.getElementById("page4");
    /*} else if (divName == "page3") {
        var div1 = document.getElementById("page2");
        var div2 = document.getElementById("page1");
        var div3 = document.getElementById("page4");*/
    } else {
        var div1 = document.getElementById("page2");
        //var div2 = document.getElementById("page3");
        var div3 = document.getElementById("page1");
    }

    if (currentDiv.className == "divClose" ) {
        currentDiv.className = "";
        div1.className = "divClose";
        //div2.className = "divClose";
        div3.className = "divClose";
    } else {
        currentDiv.className = "divClose";
    };
}