function updateTopProductsAmount(val) {
    topProducts(parseInt(val));
}

function getRandomColor() {
    var letters = "0123456789ABCDEF";
    var color = "#";
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

function topProducts(totalAmount = 1450) {
    if (typeof totalAmount != "number") {
        totalAmount = 1450;
    }
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        var to_show = "LOADING ...";
        document.getElementById("topProductsTable").innerHTML = to_show;
        if (this.readyState == 4 && this.status == 200) {
            var result = JSON.parse(this.responseText);

            var labelSet = new Array();
            var dataSet = new Array();
            var colorSet = new Array();

            to_show = "<center><table><thead><tr><th>Product Name</th><th>Sold</th><th>Stock</th></tr><thead><tbody>";
            for (let i = 0; i < result['Product_ID'].length; i++) {
                to_show += "<tr id='topProducts" + i + "'>";
                to_show += "<td>" + result['Product_Name'][i] + "</td><td>" + result['Count'][i] + "</td>" + "</td><td>" + result['Stock'][i] + "</td>";
                to_show += "</tr>";
                if (i < totalAmount) {
                    labelSet.push(result['Product_Name'][i]);
                    dataSet.push(result['Count'][i]);
                    colorSet.push(getRandomColor());
                } else break;
            }
            to_show += "</tbody></table></center>";
            document.getElementById("topProductsTable").innerHTML = to_show;
            //createOutOfStockChart(labelSet, dataSet);
            {
                var ctx = document.getElementById('topProductsChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labelSet,
                        datasets: [{
                            label: 'lbs',
                            data: dataSet,
                            backgroundColor: colorSet
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            }
        }
    };
    xhttp.open("GET", "./php/dashboard_topProductsAjax.php", true);
    xhttp.send();
}

function outOfStock() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        var to_show = "LOADING ...";
        document.getElementById("Extraction_Table").innerHTML = to_show;
        if (this.readyState == 4 && this.status == 200) {
            var result = JSON.parse(this.responseText);

            var labelSet = new Array();
            var dataSet = new Array();
            to_show = "<center><table><thead><tr><th>Zone</th><th>Name</th><th>Stock</th><th>Warehouse</th><th>Total Stock</th></tr><thead><tbody>";
            for (let i = 0; i < result['Product_ID'].length; i++) {
                to_show += "<tr id='outOfStock_" + i + "' onclick='goToProductReport(\"" + result['Barcode_ID'][i] + "\")'>";
                to_show += "<td>" + result['Zone'][i] + "</td><td>" + result['Product_Name'][i] + "</td><td>" + result['Stock'][i] + "</td><td>" + result['Warehouse_Stock'][i] + "</td><td>" + result['Total_Stock'][i] + "</td>";
                to_show += "</tr>";
                if (i <= 20) {
                    labelSet.push(result['Product_Name'][i]);
                    dataSet.push(result['Total_Stock'][i]);
                }
            }
            to_show += "</tbody></table></center>";
            document.getElementById("Extraction_Table").innerHTML = to_show;
            createOutOfStockChart(labelSet, dataSet);
        }
    };
    xhttp.open("GET", "./php/dashboard_outOfStockAjax.php", true);
    xhttp.send();
}

function createOutOfStockChart(labelSet, dataSet) {
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labelSet,
            datasets: [{
                label: 'Stock',
                data: dataSet,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}

function invoiceInfo() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var result = JSON.parse(this.responseText);
            console.log(result);
            document.getElementById("today-order").innerHTML = result['Today_Order'];
            document.getElementById("last-order").innerHTML = "#" + result['Last_Order_Num'];
            document.getElementById("total-this-month").innerHTML = result['Total_This_Month'];
        }
    };
    xhttp.open("GET", "./php/dashboard_invoiceInfoAjax.php", true);
    xhttp.send();
}

function goToProductReport(Barcode_ID) {
    window.location.href = "http://www.ttparikh.club/productReport.php?search=" + Barcode_ID;
}

function sugesstionAjax(a, b, input) {
    var val = input.value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            var result = JSON.parse(this.responseText);
            if (result["P_Product_ID"]) {
                for (let j = 0; j < result["P_Product_ID"].length; j++) {
                    b = document.createElement("DIV");

                    var Title = document.createElement("section");
                    /*make the matching letters bold:*/
                    Title.innerHTML = "<strong>" + result["P_Product_Name"][j].substr(0, val.length) + "</strong>";
                    Title.innerHTML += result["P_Product_Name"][j].substr(val.length);

                    var Details = document.createElement("section");
                    Details.innerHTML = "<p>Location: " + result["P_Location"][j] + "</p>";
                    Details.innerHTML += "<p> " + result["P_Zone"][j] + " Zone with Stock: " + result["P_Stock"][j] + "</p>";

                    Title.appendChild(Details);
                    b.appendChild(Title);

                    /*execute a function when someone clicks on the item value (DIV element):*/
                    b.addEventListener("click",
                        function(e) {
                            window.location.href =
                                "./productReport.php?search=" +
                                encodeURI(result["P_Product_Name"][j]);
                        }
                    );
                    a.appendChild(b);
                }
            }
            if (result["Invoice_ID"]) {
                for (let j = 0; j < result["Invoice_ID"].length; j++) {
                    b = document.createElement("DIV");

                    var Title = document.createElement("section");
                    Title.innerHTML = "#<strong>" + result["Invoice_Num"][j].substr(0, val.length) + "</strong>";
                    Title.innerHTML += result["Invoice_Num"][j].substr(val.length);

                    // b.innerHTML = "<strong>" + result["Invoice_Num"][j].substr(0, val.length) + "</strong>";
                    // b.innerHTML += result["Invoice_Num"][j].substr(val.length);

                    var Details = document.createElement("section");
                    Details.innerHTML = "<p>Total Packages: " + result["Total_Count"][j] + "</p>";
                    Details.innerHTML += "<p>Ordered by " + toTitleCase(result["Customer_Info"][j]) + " [" + result["Create_Time"][j] + "]</p>";

                    Title.appendChild(Details);
                    b.appendChild(Title);

                    // b.innerHTML += " | Count: " + result["Total_Count"][j];
                    // b.innerHTML += " | " + toTitleCase(result["Customer_Info"][j]);
                    // b.innerHTML += " | " + result["Create_Time"][j];
                    b.addEventListener("click",
                        function(e) {
                            window.location.href =
                                "./invoiceReport.php?search=" +
                                encodeURI(result["Invoice_Num"][j]);
                        }
                    );
                    a.appendChild(b);
                }
            }
            if (result["Customer_ID"]) {
                for (let j = 0; j < result["Customer_ID"].length; j++) {
                    b = document.createElement("DIV");
                    var str = result["Customer_Info"][j];
                    b.innerHTML = boldMatching(toTitleCase(str), val);
                    b.addEventListener("click",
                        function(e) {
                            window.location.href =
                                "./customerReport.php?search=" +
                                encodeURI(result["Customer_ID"][j]);
                        }
                    );
                    a.appendChild(b);
                }
            }
            if (result["Vendor_ID"]) {
                for (let j = 0; j < result["Vendor_ID"].length; j++) {
                    b = document.createElement("DIV");
                    b.innerHTML = "<strong>" + result["Name"][j].substr(0, val.length) + "</strong>";
                    b.innerHTML += result["Name"][j].substr(val.length);
                    b.addEventListener("click",
                        function(e) {
                            window.location.href =
                                "./vendor.php?search=" +
                                encodeURI(result["Name"][j]);
                        }
                    );
                    a.appendChild(b);
                }
            }
            if (result["Tracking_ID"]) {
                for (let j = 0; j < result["Tracking_ID"].length; j++) {
                    b = document.createElement("DIV");
                    b.innerHTML = "<strong>" + result["Tracking_ID"][j].substr(0, val.length) + "</strong>";
                    b.innerHTML += result["Tracking_ID"][j].substr(val.length);
                    b.innerHTML += " | #" + result["T_Invoice_Num"][j];
                    if (result["Status"][j] == 1)
                        b.innerHTML += " | Sent Off";
                    else
                        b.innerHTML += " | In Warehouse";
                    b.addEventListener("click",
                        function(e) {
                            window.location.href = "./trackingReport.php?search=" + encodeURI(result["Tracking_ID"][j]);
                        }
                    );
                    a.appendChild(b);
                }
            }
        }
    };
    xhttp.open("GET", "./php/dashboard_universalSearchAjax.php?search=" +
        encodeURI(val), true);
    xhttp.send();
}

function boldMatching(str, match) {
    var upperStr = str.toUpperCase();
    var upperMatch = match.toUpperCase();
    var match_position = upperStr.search(upperMatch);
    if (match_position == -1) {
        return str;
    }

    var first_half, start, size;
    start = 0;
    size = match_position;
    first_half = str.substr(start, size);

    start = match_position;
    size = match.length;
    var middle = str.substr(start, size);

    start = match_position + match.length;
    size = str.length;
    var last_half = str.substr(start, size);
    return first_half + middle.bold() + last_half;
}

function toggleNote() {
    var doc = document.getElementById("note-inner");
    if (doc.style.display == "none")
        doc.style.display = "block";
    else
        doc.style.display = "none";
}



