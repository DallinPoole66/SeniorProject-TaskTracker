// Establish our variables
var task_list;
var taskDetailsModal;
var stats;
var sort_priority_direction = false;
var hide_completed_tasks = false;
var sort_by_time = false;
var sort_by_date = true;

// Converts the database task_priority into the int values we want.
let prio_to_int = {
    "low": 0
    ,"med": 1
    ,"high": 2
}

// Converts the database task_time into the text we want.
let time_conversion = {
    0:"5 minutes"
    ,1:"15 minutes"
    ,2:"30 minutes"
    ,3:"45 minutes"
    ,4:"1 hour"
    ,5:"1 hour 30 min."
    ,6:"2 hours"
}

// Converts the database task_priority into the BS5 classes we want.
let prio_dictionary = {
    "low":"badge rounded-pill bg-primary"
    ,"med":"badge rounded-pill bg-warning"
    ,"high":"badge rounded-pill bg-danger"
}

// Converts the database task_priority into the text we want.
let prio_Text_dictionary = {
    "low":"Low"
    ,"med":"Med"
    ,"high":"High"
}
const task_lists = new Map();

// Run our requests once the initial view is loaded.
document.addEventListener("DOMContentLoaded", function() {
    send_date();
    //    get_list();
    get_upcoming_tasks();
    get_overdue_tasks();
    get_calendar();
    taskDetailsModal = new bootstrap.Modal(document.getElementById("addTask"), {});
    get_stats();
    
  });
  

if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}

// Sends the local date to fix timezone issues.
function send_date(){
    var d = new Date();
    var locale_date_string = d.toLocaleDateString();

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
//        document.getElementById("this_month").innerHTML = this.responseText;

    }
    };
    xhttp.open("GET", "accounts/accounts.php?date=" + locale_date_string, true);
    xhttp.send();
}


// Requests the view be updated to go back one month.
function select_last_month(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        document.getElementById("this_month").innerHTML = this.responseText;
    }
    };
    xhttp.open("GET", "tasks/tasks.php?m=-1", true);
    xhttp.send();
}

// Requests the view be updated to go forward one month.
function select_next_month(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        document.getElementById("this_month").innerHTML = this.responseText;
    }
    };
    xhttp.open("GET", "tasks/tasks.php?m=1", true);
    xhttp.send();
}


// Requests the details view for the task.
function select_task(task){
    document.getElementById("taskDetails").innerHTML = "";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        document.getElementById("taskDetails").innerHTML = this.responseText; 
    }
    };
    xhttp.open("GET", "tasks/tasks.php?t=" + task, true);
    xhttp.send(); 
}


// Requests the calendar view for this month.
function get_calendar(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        document.getElementById("this_month").innerHTML = this.responseText;
    }
    };
    xhttp.open("GET", "tasks/tasks.php?m=0", true);
    xhttp.send();


}



// Get the overdue tasks and puts them into a TaskList object.
function get_overdue_tasks(){
    fetch("/tasktracker/tasks/tasks.php?od=0")
    .then(function (response) {
        if (response.ok){
            return response.json();
        }
        throw Error("Error in response for overdue tasks list.");
    })
    .then(function (data) {
        console.log(data);
        // Store on the task_lists map using the elementid as a key
        task_lists.set("overduetasks" ,  new TaskList(data, "overduetasks", "Overdue Tasks"));        
        task_lists.get("overduetasks").sort_tasks_by_date();
        drawChart();

    })
    .catch(function (error) {
        console.log("Error: ", error.message);
    })

}

// Get the upcoming tasks and puts them into a TaskList object.
function get_upcoming_tasks(){
    fetch("/tasktracker/tasks/tasks.php?d=0")
    .then(function (response) {
        if (response.ok){
            return response.json();
        }
        throw Error("Error in response for tasks list.");
    })
    .then(function (data) {
        console.log(data);
        // Store on the task_lists map using the elementid as a key
        task_lists.set("todaystasks" ,  new TaskList(data, "todaystasks", "Upcoming Tasks"));        
        task_lists.get("todaystasks").sort_tasks_by_date();
        //sort_tasks_by_date();
    })
    .catch(function (error) {
        console.log("Error: ", error.message);
    })
}

// Get the stats for this month.
function get_stats(){
    fetch("/tasktracker/tasks/tasks.php?stats=0")
    .then(function (response) {
        if (response.ok){
            return response.json();
        }
        throw Error("Error in response for stats list.");
    })
    .then(function (data) {
        console.log(data);
        stats = data;
        console.log("Stats: ", stats);
        drawChart();
    })
    .catch(function (error) {
        console.log("Error: ", error.message);
    })
}

// Request a complte operation on the given task.
function complete_task(task){
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "tasks/tasks.php?complete=" + task, true);
    xhttp.send(); 
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(task + "_square").firstElementChild.setAttribute("class", "badge rounded-pill bg-success");
            get_upcoming_tasks();
            get_overdue_tasks();
            get_stats();
        }
        };
}


// Request a delete operation on the given task
function delete_task(task){
    console.log("Delete Task ID: ", task);
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "/index.php?action=delete&task_id=" + task, true);
    xhttp.send(); 
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(task + "_square").remove();
            get_upcoming_tasks();
            get_overdue_tasks();
            get_stats();
        }
        };
}

// Request the view for the task we want ot edit and insert into the modal.
function edit_task(task){
    console.log("Edit Task ID: ", task);

    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "tasks/tasks.php?edit=" + task, true);
    xhttp.send(); 
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("taskEdit").innerHTML = this.responseText;      

        }
    };

}

// Request the view for the task we want to copy
function copy_task(task){
    console.log("Copy Task ID: ", task);

    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "tasks/tasks.php?copy=" + task, true);
    xhttp.send(); 
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("taskCopy").innerHTML = this.responseText;      

        }
    };

}

// Handle when a calendar square is clicked to open the Add Task Modal with the date already set.
function add_task_date( date ){
    console.log("Add Task To Date: ", date);
    document.getElementById("task_date").setAttribute("value", date);
    taskDetailsModal.show();
}

// Sort the given TaskList by time required
function sort_tasks_by_time(selection){
    task_lists.get(selection).sort_tasks_by_time();
}

// Sort the Given TaskList by date
function sort_tasks_by_date(selection){
    task_lists.get(selection).sort_tasks_by_date();
}

// Sort the given TaskList by priority
function sort_tasks_by_priority(selection){
    task_lists.get(selection).sort_tasks_by_priority();
   
}


// Draw our stats pie chart
function drawChart() {
    // Find our canvas
    var statsCanvas = document.getElementById("statsCanvas");    
    var ctx = statsCanvas.getContext("2d");
    
    ctx.beginPath();

    // Overwrite the whole thing with white.
    ctx.fillStyle = 'white';
    ctx.fillRect(0, 0, statsCanvas.width, statsCanvas.width);
    
    // Shadow Behind the circle
    ctx.shadowColor = '#00000066';
    ctx.shadowBlur = 15;
     
    ctx.lineWidth = 8;
    ctx.strokeStyle = 'silver';
    ctx.arc(statsCanvas.width / 2, statsCanvas.width / 2, 146, 0, 2 * Math.PI);
    ctx.stroke();

    // Disable shadow.
    ctx.shadowBlur = 0;

    // Draw a background circle that's green to show the completed tasks. The other sections
    // are drawn on top.
    drawPieSlice(ctx, statsCanvas.width / 2, statsCanvas.width / 2, 148, 0,Math.PI * 2, '#198754');

    // Low Priority tasks
    var start = 0;
    var end = start + Math.PI * 2 * (stats['low'] / stats['total']);
    drawPieSlice(ctx, statsCanvas.width / 2, statsCanvas.width / 2, 148, start, end, '#0d6efd');

    // Update start and stop radians and draw Medium Priority
    start = end;    
    end = start + Math.PI * 2 * (stats['med'] / stats['total']);   
    drawPieSlice(ctx, statsCanvas.width / 2, statsCanvas.width / 2, 148, start,end, '#ffc107');start = end;    

    // Update start and stop radians and draw High Priority
    start = end;    
    end = start + Math.PI * 2 * (stats['high'] / stats['total']);
    drawPieSlice(ctx, statsCanvas.width / 2, statsCanvas.width / 2, 148, start,end, '#dc3545');

    // Draw a ring around the chart to look cleaner
    ctx.beginPath();
    ctx.lineWidth = 8;
    ctx.strokeStyle = 'silver';
    ctx.arc(statsCanvas.width / 2, statsCanvas.width / 2, 146, 0, 2 * Math.PI);
    ctx.stroke();

  }


// Draws a slice for the pie chart using radian start and end angles.
// Start 0 is at the 3 o'clock position. And it goes Clockwise.
function drawPieSlice(ctx, centerX, centerY, radius, startAngle, endAngle, color ){
    ctx.fillStyle = color;
    ctx.beginPath();
    ctx.moveTo(centerX,centerY);
    ctx.arc(centerX, centerY, radius, startAngle, endAngle);
    ctx.closePath();
    ctx.fill();
}


/*
    Task List
    Handles the organization and displaying of task lists JSON objects.
*/
class TaskList{
    // tasks: JSON of tasks 
    // elementId: string of what element we write into.
    // heading: String of what to call our output list.
    constructor(tasks, elementId, heading){
        this.tasks = tasks;
        this.elementId = elementId;
        this.heading = heading;
        this.sort_by_date = true;
        this.sort_priority_direction = false;
        this.sort_by_time = false;
    }

    // Outputs a table into the elementId with the given heading.
    buildList(){
    
        console.log("Building List For:", this.elementId, this.tasks);        

        // Filter out completed tasks.        
        this.tasks = this.tasks.filter(x => x.task_completed < 1);
            
        
        // Start building the list.
        let dataTable = '<h2>'+this.heading+'</h2><hr>';
        if (this.tasks.length == 0){
            dataTable += '<h3>All Caught Up!</h3>';
        }else{
            

            dataTable += '<table><thead>';
            dataTable +='<tr><th onclick=sort_tasks_by_priority("'+this.elementId+'")>';
            dataTable += '<span id="'+this.elementId +'prio_sort" class="sort">&uarr;</span> Priority</th><th class ="taskname">Name</th><th onclick=sort_tasks_by_time("'+this.elementId +'")><div id="'+this.elementId +'time_sort" class="sort">&uarr;</div> Time</th><th onclick=sort_tasks_by_date("'+this.elementId +'")><div class="sort" id="'+this.elementId +'date_sort">&uarr;</div> Date</th></tr>'; 
            dataTable += '</thead>'; 
            dataTable += '<tbody>'; 
            
            // For each task create a table row.
            this.tasks.forEach(function (element) { 
                console.log(element.task_name); 
                dataTable += '<tr onclick="select_task('+element.task_id + ')" data-bs-toggle="modal" data-bs-target="#taskDetails" >';
                if (element.task_completed){
                    dataTable += `<td class = 'badge rounded-pill bg-success'>Done</td>`;
                }else {
                    dataTable += `<td class = '${prio_dictionary[element.task_priority]}'>${prio_Text_dictionary[element.task_priority]}</td>`;
                }
                dataTable += `<td >${element.task_name}</td>`;
                dataTable += `<td>${time_conversion[element.task_time]}</td>`;
                dataTable += `<td>${element.task_date}</td>`;

                
                dataTable += '</tr>';
            }) 


            dataTable += '</tbody></table>'; 
        }
        console.log("Finished Building List For:", this.elementId, this.tasks);
        // Insert into the element on the page.
        document.getElementById(this.elementId).innerHTML = dataTable;
    }

    // Toggles sorting by priority Ascending / Descending order
    sort_tasks_by_priority(){
        this.sort_priority_direction = !this.sort_priority_direction;
        console.log("Sort by priority:");
    
        // Reverse the direction if needed
        if( this.sort_priority_direction ){
            this.tasks.sort(function(b,a) { return prio_to_int[a.task_priority] - prio_to_int[b.task_priority] });
        } else {
            this.tasks.sort(function(a,b) { return prio_to_int[a.task_priority] - prio_to_int[b.task_priority] });
        }
    
        this.buildList();       
    
        // Update the Arrow elements in the other table headers
        document.getElementById(this.elementId+"date_sort").innerHTML = " ";
        document.getElementById(this.elementId+"time_sort").innerHTML = " ";
        if (this.sort_priority_direction){        
            document.getElementById(this.elementId+"prio_sort").innerHTML = "&darr;";
        }
    }

    // Toggles sorting by date in Ascending / Descending order
    sort_tasks_by_date(){
        this.sort_by_date = !this.sort_by_date;
        console.log("Sort by Date: ", this.sort_by_date);
        
        // Reverse the direction if needed
        if( this.sort_by_date ){
            this.tasks.sort(function(a,b) { return new Date(b.task_date) - new Date(a.task_date) } );
        } else {
            this.tasks.sort(function(a,b) { return new Date(a.task_date) - new Date(b.task_date) } );
        }
        this.buildList();

        // Update the Arrow elements in the other table headers
        document.getElementById(this.elementId+"prio_sort").innerHTML = " ";
        document.getElementById(this.elementId+"time_sort").innerHTML = " ";
        if (this.sort_by_date){        
            document.getElementById(this.elementId+"date_sort").innerHTML = "&darr;";
        }
    }

    // Toggles sorting by time required in Ascending / Descending order
    sort_tasks_by_time(){
        this.sort_by_time = !this.sort_by_time;
        console.log("Sort by time: ", this.sort_by_time);

        // Flip the order if needed
        if( this.sort_by_time ){
            this.tasks.sort(function(a,b) { return parseInt(b.task_time) - parseFloat(a.task_time) } );
        } else {
            this.tasks.sort(function(a,b) { return parseInt(a.task_time) - parseFloat(b.task_time) } );
        }
        this.buildList(tasks);        
        
        // Update the Arrow elements in the other table headers
        document.getElementById(this.elementId+"prio_sort").innerHTML = "";
        document.getElementById(this.elementId+"date_sort").innerHTML = "";
        if (this.sort_by_time){        
            document.getElementById(this.elementId+"time_sort").innerHTML = "&darr;";
        }
    }

}
