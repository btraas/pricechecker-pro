function sortResults() {
    var table, rows, switching, i, x, y, shouldSwitch,tmp;
    var option = document.getElementById('resultSorting').value;
    table = document.getElementById("resultTable");
    console.log(option);
    switching = true;
    /*Make a loop that will continue until
     no switching has been done:*/
    while (switching) {

        //start by saying: no switching is done:
        switching = false;
        rows = table.getElementsByTagName("TR");
        /*Loop through all table rows (except the
         first, which contains table headers):*/
        for (i = 1; i < (rows.length - 1); i++) {
            //start by saying there should be no switching:
            shouldSwitch = false;
            /*Get the two elements you want to compare,
             one from current row and one from the next:*/
            x = rows[i].getElementsByTagName("TD")[2];
            y = rows[i + 1].getElementsByTagName("TD")[2];
            //check if the two rows should switch place:
            if(parseInt(option)) {
                tmp = x;
                x = y;
                y= tmp;
            }

            if (parseFloat(x.innerHTML.toString().replace(/[^0-9\.]/g, '')) > parseFloat(y.innerHTML.toString().replace(/[^0-9\.]/g, ''))) {
                //if so, mark as a switch and break the loop:
                shouldSwitch= true;
                break;
            }
        }
        if (shouldSwitch) {
            /*If a switch has been marked, make the switch
             and mark that a switch has been done:*/
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }
}