function openCloseSidebar(x) {
    x.classList.toggle("change");
    var width = document.getElementById("sidebar_Id").clientWidth;
    if (width != 0) {
        width = "0px";
        document.getElementsByClassName("inside")[0].style.padding = "0";
    } else {
        width = "250px";
        document.getElementsByClassName("inside")[0].style.paddingRight = "15%";
    }

    document.getElementById("sidebar_Id").style.width = width;
    document.getElementById("root_Id").style.marginLeft = width;
}

function submitInvoiceStatus(event) {
    document.getElementById("invoiceStatus_go").style.backgroundColor = 'var(--red-theme)';
    var invoice_num = document.getElementById("invoiceStatus_text").value;

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var result = this.responseText;

            alert(result);
            document.getElementById("invoiceStatus_text").value = "";
            // var x = document.createElement("DIV");
            // x.setAttribute("id", "sidebar_Id");
            // x.setAttribute("class", "show");
            // x.innerHTML = result;
            // document.getElementById("root_Id").appendChild(x);
            // setTimeout(function(){ x.className = x.className.replace("show", ""); }, 10000);
        }
    };
    xhttp.open("GET", "php/submitInvoiceStatus_Ajax.php?invoice-num=" + invoice_num, true);
    xhttp.send();
}

function invoiceInfo(Invoice_ID, Invoice_Num, Total_Count, Customer_Name, Company_Name) {
    var to_show = '';
    if (Customer_Name != '') to_show += '<tr><td><label>Customer Name:</label></td><td><label id="Customer_Name">' + Customer_Name + '</label></td></tr>';
    if (Company_Name != '') to_show += '<tr><td><label>Company Name:</label></td><td><label id="Company_Name">' + Company_Name + '</label></td></tr>';
    to_show += '<tr><td><label>Invoice Number:</label></td><td><label id="Invoice_Num">' + Invoice_Num + '</label></td><td style="float:right"><a href="editInvoice.php?InvoiceID=' + Invoice_ID + '"><input type="button" value="Edit"></a></td></tr>';
    to_show += '<tr><td><label>Total Packages:</label></td><td><label id="Total_Count">' + Total_Count + '</label></td></tr>';

    document.getElementById('invoice_info_table').innerHTML = to_show;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var result = JSON.parse(this.responseText);
            var tempLength = result[0].length;
            if (tempLength > 0) {
                to_show = "<tr><th>Product Name</th><th>Count</th><th>Last Updated At</th></tr>";
                var data = [];
                for (let i = 0; i < tempLength; i++) {
                    var productName = result[5][i];
                    var count = result[1][i];
                    var date = result[3][i];

                    data.push([productName, count, date]);
                }
                data_s = data.sort(function(a, b) {
                    var x = a[0].toLowerCase(),
                        y = b[0].toLowerCase();
                    return x < y ? -1 : x > y ? 1 : 0;
                });

                for (let i = 0; i < data_s.length; i++) {
                    to_show += "<tr><td><label>" + data_s[i][0] + "</label></td><td><label>" + data_s[i][1] + "</label></td><td><label>" + data_s[i][2] + "</label></td></tr>";
                }

                document.getElementById("invoice_details_table").innerHTML = to_show;
            }
        }
    };
    xhttp.open("GET", "php/getInvoiceDetailsAjax.php?InvoiceID=" + Invoice_ID, true);
    xhttp.send();

    var x = document.getElementById("invoice_content_div");
    if (x.style.display === "block" && document.getElementById("Invoice_Num").innerHTML == Invoice_Num) x.style.display = "none";
    else x.style.display = "block";
}

function someFunc() {
    document.documentElement.style.setProperty(
        "--green-theme",
        document.getElementById("colorPicker2").value
    );
    document.documentElement.style.setProperty(
        "--purple-theme",
        document.getElementById("colorPicker1").value
    );
}

function dropdownToggle() {
    var dropdown = document.querySelectorAll(".drop-content");
    document.getElementsByClassName("dropdown-btn")[0].classList.toggle("active");
    for (let i = 0; i < dropdown.length; i++) {
        var dropdownContent = dropdown[i];
        if (dropdownContent.style.display === "none") {
            dropdownContent.style.display = "block";
        } else {
            dropdownContent.style.display = "none";
        }
    }
}

//------------------ Ajax page loading ------------------//

function loadDoc(fileName) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            $("#root_Id").load(fileName);
        }
    };
    xhttp.open("GET", fileName, true);
    xhttp.send();
}

function addRow(idNum) {
    let syntaxToAdd = "";
    let currentElementID = idNum;
    for (let i = 0; i < 4; i++) {
        idNum++;
        syntaxToAdd += "<tr>";
        syntaxToAdd +=
            '<td><input type="text" id="barcode_' +
            idNum +
            '" name="barcode_' +
            idNum +
            '"></td>';
        syntaxToAdd +=
            '<td><input type="text" id="product_name_' +
            idNum +
            '" name="product_name_' +
            idNum +
            '"></td>';
        syntaxToAdd +=
            '<td><input type="text" id="count_' +
            idNum +
            '" name="count_' +
            idNum +
            '"></td>';
        syntaxToAdd +=
            '<td><input type="text" id="price_' +
            idNum +
            '" name="price_' +
            idNum +
            '"></td>';
        syntaxToAdd += "</tr>";
    }
    idNum++;
    syntaxToAdd += "<tr>";
    syntaxToAdd +=
        '<td><input type="text" id="barcode_' +
        idNum +
        '" name="barcode_' +
        idNum +
        '" onchange="addRow(' +
        (currentElementID + 6) +
        ')"></td>';
    syntaxToAdd +=
        '<td><input type="text" id="product_name_' +
        idNum +
        '" name="product_name_' +
        idNum +
        '"></td>';
    syntaxToAdd +=
        '<td><input type="text" id="count_' +
        idNum +
        '" name="count_' +
        idNum +
        '"></td>';
    syntaxToAdd +=
        '<td><input type="text" id="price_' +
        idNum +
        '" name="price_' +
        idNum +
        '"></td>';
    syntaxToAdd += "</tr>";

    document.getElementById("addInside_tr_20").innerHTML += syntaxToAdd;
    //document.getElementById('barcode_'+currentElementID).select();
}

function barcodeOnChange(count) {
    document.getElementById("count_" + count).value = 1;
    var checkIfDuplicate = false;

    var currentElement = document.getElementById("barcode_" + count);
    var previousElement = document.getElementById("barcode_" + (count - 1));
    if (currentElement.value == "") {
        document.getElementById("count_" + count).value = "";
        document.getElementById("product_name_" + count).value = "";
        document.getElementById("price_" + count).value = "";
        quantityDisplay();
        return;
    }
    if (count != 1 && currentElement.value == previousElement.value) {
        var count_element = document.getElementById("count_" + (count - 1));
        var count_element_value = parseInt(count_element.value);
        count_element.value = count_element_value + 1;

        document.getElementById("count_" + count).value = "";

        var price_element = document.getElementById("price_" + (count - 1));
        var prices = price_element.value.split("|| ");
        var qb =
            (parseFloat(prices[0]) / count_element_value) *
            (count_element_value + 1);
        var online =
            (parseFloat(prices[1]) / count_element_value) *
            (count_element_value + 1);
        price_element.value = qb.toFixed(2) + " || " + online.toFixed(2);

        currentElement.value = "";
        currentElement.focus();
        checkIfDuplicate = true;
    }
    if (checkIfDuplicate == false) {
        getProduct(currentElement.value, count);
    }
    quantityDisplay();
}

function getProduct(Barcode_ID, currentElementID) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var data = JSON.parse(this.responseText);

            if (data[3] == "B")
                document.getElementById("product_name_" + currentElementID).value = data[0] + " - 114g";
            else
                document.getElementById("product_name_" + currentElementID).value = data[0];

            var tempValue;

            data[2] && data[8] ? (tempValue = data[2] + " || " + data[8]) : (tempValue = "");
            //tempValue = data[2] + " || " + data[1];
            if (document.getElementById("price_" + currentElementID))
                document.getElementById("price_" + currentElementID).value = tempValue;

            quantityDisplay();
        }
    };
    xhttp.open(
        "GET",
        "../php/invoiceGetProductAjax.php?BarcodeID=" + Barcode_ID,
        true
    );
    xhttp.send();
}

function invoiceSpliter() {
    var current_element = document.getElementById("invoice_name");
    var current_value = current_element.value;

    var invoices = current_value.split("} ");
    if (!invoices[1]) return;
    var temp = invoices[0].split("{");
    var invoiceNumber = temp[1];

    var invoiceName = invoices[1];

    document.getElementById("invoice_num").value = invoiceNumber;
    current_element.value = invoiceName;
}

function quantityDisplay() {
    if (!document.getElementById("count_" + 1)) {
        return;
    }
    var count = 0;
    var qb_price = 0;
    var online_price = 0;
    for (let i = 1; i <= 200; i++) {
        var temp = document.getElementById("count_" + i).value;
        if (temp) count += parseInt(temp) + 0;

        var temp = document.getElementById("price_" + i).value;
        if (temp != "") {
            var temps = temp.split(" || ");
            qb_price += parseFloat(temps[0]) + 0;
            online_price += parseFloat(temps[1]) + 0;
        }
    }
    document.getElementById("total_quantity").value = count;
    document.getElementById("qb_price").value = qb_price.toFixed(2);
    document.getElementById("online_price").value = online_price.toFixed(2);
}

function searchResult(str) {
    str = str.toUpperCase();
    if (str.length == 0) {
        document.getElementById("suggestion").innerHTML = "";
        document.getElementById("suggestion").style.padding = "0";
        document.getElementById("suggestion").style.border = "none";
        // document.getElementById("suggestion").style.border="0px";
        return;
    }
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var to_show = "";
            var results = JSON.parse(this.responseText);
            if (results.length > 0) {
                var start_date = document.getElementById("start_date").value;
                var end_date = document.getElementById("end_date").value;
                for (var i = 0; i < results.length; i++) {
                    var json_result = results[i];
                    to_show +=
                        "<a href='vendor.php?search=" +
                        json_result +
                        "&start_date=" +
                        start_date +
                        "&end_date=" +
                        end_date +
                        "'>" +
                        json_result +
                        "</a><br/>";
                    if (i != results.length - 1) to_show += "<br/>";
                }
            } else to_show = "No Result Found";
            document.getElementById("suggestion").innerHTML = to_show;
            document.getElementById("suggestion").style.padding = "20px";
            document.getElementById("suggestion").style.border = "1px solid var(--green-theme)";
            // document.getElementById("suggestion").style.border="1px solid #A5ACB2";
        }
    };
    xmlhttp.open("GET", "php/productReportSearchAjax.php?search=" + str, true);
    xmlhttp.send();
}

function customerSearch(str) {
    if (str.length == 0) {
        document.getElementById("suggestion").innerHTML = "";
        document.getElementById("suggestion").style.padding = "0";
        document.getElementById("suggestion").style.border = "none";
        // document.getElementById("suggestion").style.border="0px";
        return;
    }
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var to_show = "";
            var results = JSON.parse(this.responseText);
            if (results.length > 0) {
                for (var i = 0; i < results.length; i++) {
                    var escapedResult = results[i].replace(/'/g, "^");
                    to_show +=
                        "<div class='suggestion_i' onclick='insertValueInInvoiceName(\"" +
                        escapedResult +
                        "\")'><label>" +
                        escapedResult +
                        "</label></div>";
                    if (i != results.length - 1) to_show += "<br/>";
                }

                document.getElementById("suggestion").innerHTML = to_show;
                document.getElementById("suggestion").style.padding = "20px";
                document.getElementById("suggestion").style.border =
                    "1px solid var(--green-theme)";
            } else {
                document.getElementById("suggestion").innerHTML = "";
                document.getElementById("suggestion").style.padding = "0";
                document.getElementById("suggestion").style.border = "none";
            }
            // document.getElementById("suggestion").style.border="1px solid #A5ACB2";
        }
    };
    xmlhttp.open("GET", "php/customerSearchAjax.php?search=" + str, true);
    xmlhttp.send();
}

function insertValueInInvoiceName(str) {
    document.getElementById("invoice_name").value = str;
    document.getElementById("suggestion").innerHTML = "";
    document.getElementById("suggestion").style.padding = "0";
    document.getElementById("suggestion").style.border = "none";
    document.getElementById("invoice_num").focus();
}

function someFunction1() {
    var str = document.getElementById("tracking").value;
    window.open("https://www.canadapost.ca/trackweb/en#/details/" + str);
}

function changeProductionForm() {
    var taken_from = this.value;
    if (taken_from == 1) {
        document.getElementById("Original-Barcode").innerHTML = "Barcode :";
        document.getElementById("machine").style.display = "";
        document.getElementById("direct").style.display = "none";
        document.getElementById("1lb").style.display = "none";
        document.getElementById("form_alter").style.display = "none";
        document.getElementById("instruction").innerHTML = "";
        document.getElementById("M_Bags").focus();
    } else if (taken_from == 2) {
        document.getElementById("Original-Barcode").innerHTML = "Barcode :";
        document.getElementById("machine").style.display = "none";
        document.getElementById("direct").style.display = "";
        document.getElementById("1lb").style.display = "none";
        document.getElementById("form_alter").style.display = "none";
        document.getElementById("instruction").innerHTML = "";
        document.getElementById("D_Bags").focus();
    } else if (taken_from == 3) {
        document.getElementById("Original-Barcode").innerHTML = "Barcode :";
        document.getElementById("machine").style.display = "none";
        document.getElementById("direct").style.display = "none";
        document.getElementById("1lb").style.display = "";
        document.getElementById("form_alter").style.display = "none";
        document.getElementById("label_bags_cut").innerHTML = "Bags Cut :";
        document.getElementById("instruction").innerHTML =
            "<span>You are converting <b>1 LB</b> to <b>0.25 LB</b></span>";
        document.getElementById("1_Bags_Cut").focus();
    } else if (taken_from == 4) {
        document.getElementById("Original-Barcode").innerHTML = "Barcode :";
        document.getElementById("machine").style.display = "none";
        document.getElementById("direct").style.display = "none";
        document.getElementById("1lb").style.display = "";
        document.getElementById("form_alter").style.display = "none";
        document.getElementById("label_bags_cut").innerHTML = "Bags Mixed :";
        document.getElementById("instruction").innerHTML =
            "<span>You are converting <b>0.25 LB</b> to <b>1 LB</b></span>";
        document.getElementById("1_Bags_Cut").focus();
    } else if (taken_from == 5) {
        document.getElementById("Original-Barcode").innerHTML = "Barcode of Original Product";
        document.getElementById("machine").style.display = "none";
        document.getElementById("direct").style.display = "none";
        document.getElementById("1lb").style.display = "none";
        document.getElementById("form_alter").style.display = "";
        document.getElementById("instruction").innerHTML =
            "<span>If you've taken the bag (eg: 55 lb) then make sure you've added this Product in 'Direct_Production' first. But if you've converted 1lb or 114g by cutting bags, no need to add in 'Direct_Production'.</span>";
        document.getElementById("F_Barcode_ID").focus();
    } else {
        document.getElementById("Original-Barcode").innerHTML = "Barcode :";
        document.getElementById("machine").style.display = "none";
        document.getElementById("direct").style.display = "none";
        document.getElementById("1lb").style.display = "none";
        document.getElementById("form_alter").style.display = "none";
        document.getElementById("instruction").innerHTML = "";
        document.getElementById("B_Bags_Made").focus();
    }
}

function getProduction(barcode) {
    if (barcode != "") {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var str = "<tr><th>Total Quantity</th><th>Date / Time</th><th>Type</th></tr>";
                var data = JSON.parse(this.responseText);
                for (let i = 0; i < data[0].length; i++) {
                    str += "<tr><td>" + data[1][i] + "</td><td>" + data[2][i] + "</td><td>" + data[3][i] + "</td></tr>";
                }
            }
            document.getElementById("Extraction_Table").innerHTML = str;
        };
        xhttp.open("GET", "./php/getProductionAjax.php?BarcodeID=" + barcode, true);
        xhttp.send();
    }
    getProduct(barcode, "1");
}

function totalQuantityProduction() {
    var Bags = document.getElementById("M_Bags").value;
    var Boxes = document.getElementById("M_Boxes").value;
    var Toute = document.getElementById("M_Toute").value;

    if (!Bags) {
        Bags = "0";
    }
    if (!Boxes) {
        Boxes = "0";
    }
    if (!Toute) {
        Toute = "0";
    }
    var total = parseInt(Bags) * parseInt(Boxes);
    total += parseInt(Toute);
    document.getElementById("instruction").innerHTML =
        "[Total Number Of Bags: " + total + " ]";
}

function productSearch(str, count) {
    if (str.length == 0) {
        if (document.getElementById("product_suggestion")) {
            document.getElementById("product_suggestion").innerHTML = "";
            document.getElementById("product_suggestion").style.padding = "0";
            document.getElementById("product_suggestion").style.border = "none";
        }
        var locationElement = document.getElementById("location_" + count);
        if (locationElement) {
            document.getElementById("stock_" + count).value = '';
            document.getElementById("barcode_" + count).value = '';
            document.getElementById("count_" + count).value = '';
            locationElement.value = '';
        }
        return;
    }

    if (window.XMLHttpRequest) var xhttp = new XMLHttpRequest();
    else var xhttp = new ActiveXObject("Microsoft.XMLHTTP");

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var results = JSON.parse(this.responseText);
            var to_show = "";
            if (results[0].length > 0) {
                for (var i = 0; i < results[0].length; i++) {
                    to_show += "<div class='suggestion_i' onclick='insertValueInProduct(\"" + results[1][i] + '",' + count + ")'>" + results[0][i] + "</div>";
                }
                document.getElementById("product_suggestion").innerHTML = to_show;
                document.getElementById("product_suggestion").style.padding = "20px";
                document.getElementById("product_suggestion").style.border = "1px solid var(--green-theme)";
            } else {
                document.getElementById("product_suggestion").innerHTML = "";
                document.getElementById("product_suggestion").style.padding = "0";
                document.getElementById("product_suggestion").style.border = "none";
            }
        }
    };
    xhttp.open("GET", "../php/productSearchAjax.php?search=" + str, true);
    xhttp.send();
}

function insertValueInProduct(barcode, count) {
    document.getElementById("barcode_" + count).value = barcode;
    barcodeOnChange(count);
    document.getElementById("product_suggestion").innerHTML = "";
    document.getElementById("product_suggestion").style.padding = "0";
    document.getElementById("product_suggestion").style.border = "none";
}

function blinkIfGreen(i) {
    var stockElement = document.getElementById("stock_" + i);
    var zoneElement = document.getElementById("zone_" + i);
    var reorderElement = document.getElementById("reorder_" + i);

    var stockValue = parseFloat(stockElement.value);
    var reorderValue = parseFloat(reorderElement.value);
    if (zoneElement.value == "G" && stockValue < reorderValue) {
        stockElement.classList.toggle("blink_me");
        zoneElement.classList.toggle("blink_me");
        reorderElement.classList.toggle("blink_me");
    }
}

function sortNow() {
    var divElement = document.getElementById("sorted_div");
    var str =
        '<input type="button" id="sorted" value="X" style="width:100px;border-radius: 15px 15px 15px 15px;" onclick="closeSort()">';
    str += "<table><tr><th>Product Name</th><th>Count</th></tr>";

    var data = [];
    var productName;
    for (let i = 1; i <= 200; i++) {
        productName = document.getElementById("product_name_" + i).value;
        if (productName != "") {
            var Count = document.getElementById("count_" + i).value;

            data.push([productName, Count]);
        }
    }
    data_s = data.sort(function(a, b) {
        var x = a[0].toLowerCase(),
            y = b[0].toLowerCase();
        return x < y ? -1 : x > y ? 1 : 0;
    });
    for (let i = 0; i < data_s.length; i++) {
        str += "<tr><td>" + data_s[i][0] + "</td><td>" + data_s[i][1] + "</td></tr>";
    }

    str += "</table>";

    divElement.innerHTML = str;
}

function closeSort() {
    document.getElementById("sorted_div").innerHTML = "";
}

function adjust(str, id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var data = JSON.parse(this.responseText);

            if (data[3] == "B")
                document.getElementById(id + "product_name").innerHTML +=
                data[0] + " - 114g";
            else
                document.getElementById(id + "product_name").innerHTML +=
                data[0];

            document.getElementById(id + "Total_Production").value = data[5];
            document.getElementById(id + "Total_Sold").value = data[6];
            document.getElementById(id + "Adjustment").value = data[7];
            document.getElementById(id + "Stock").value = data[8];
        }
    };
    xhttp.open(
        "GET",
        "../php/invoiceGetProductAjax.php?BarcodeID=" + str,
        true
    );
    xhttp.send();
}

function searchResultSettings(str) {
    str = str.toUpperCase();
    if (str.length == 0) {
        document.getElementById("suggestion").innerHTML = "";
        document.getElementById("suggestion").style.padding = "0";
        document.getElementById("suggestion").style.border = "none";
        // document.getElementById("suggestion").style.border="none";
        return;
    }
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var to_show = "";
            var results = JSON.parse(this.responseText);
            if (results.length > 0) {
                //console.log(results);
                for (var i = 0; i < results[0].length; i++) {
                    to_show +=
                        "<li onclick='changeBarcodeValue(\"" +
                        results[1][i] +
                        '", "' +
                        results[2][i] +
                        '", "' +
                        results[0][i] +
                        '", "' +
                        results[3][i] +
                        "\")'>" +
                        results[0][i] +
                        "</li>";
                    if (i != results[0].length - 1) to_show += "<br/>";
                }
            } else to_show = "No Result Found";
            document.getElementById("suggestion").innerHTML = to_show;
            document.getElementById("suggestion").style.padding = "20px";
            document.getElementById("suggestion").style.border =
                "1px solid var(--green-theme)";
            // document.getElementById("suggestion").style.border="1px solid #A5ACB2";
        }
    };
    xmlhttp.open("GET", "php/settingsSearchAjax.php?search=" + str, true);
    xmlhttp.send();
}

function updateQuantity_PS() {
    var Total_Production = document.getElementById("Total_Production");
    var Total_Sold = document.getElementById("Total_Sold");
    var Adjustment = document.getElementById("Adjustment");
    var Stock = document.getElementById("Stock");
    var B_Total_Production = document.getElementById("B_Total_Production");
    var B_Total_Sold = document.getElementById("B_Total_Sold");
    var B_Adjustment = document.getElementById("B_Adjustment");
    var B_Stock = document.getElementById("B_Stock");

    Stock.value =
        parseFloat(Total_Production.value) -
        parseFloat(Total_Sold.value) +
        parseFloat(Adjustment.value);
    if (B_Stock.value)
        B_Stock.value =
        parseFloat(B_Total_Production.value) -
        parseFloat(B_Total_Sold.value) +
        parseFloat(B_Adjustment.value);
}

function updateQuantity() {
    var Total_Production = document.getElementById("Total_Production");
    var Total_Sold = document.getElementById("Total_Sold");
    var Adjustment = document.getElementById("Adjustment");
    var Stock = document.getElementById("Stock");
    var B_Total_Production = document.getElementById("B_Total_Production");
    var B_Total_Sold = document.getElementById("B_Total_Sold");
    var B_Adjustment = document.getElementById("B_Adjustment");
    var B_Stock = document.getElementById("B_Stock");

    Adjustment.value =
        parseFloat(Stock.value) -
        parseFloat(Total_Production.value) +
        parseFloat(Total_Sold.value);
    if (B_Stock.value)
        B_Adjustment.value =
        parseFloat(B_Stock.value) -
        parseFloat(B_Total_Production.value) +
        parseFloat(B_Total_Sold.value);
}

//SEARCH SUGGESTION was added from W3Schools
function autocomplete(inp) {
    /*the autocomplete function takes two arguments,
the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
        var a, //The div box to store the suggestions
            b, //Individual divs for each suggestion
            val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) {
            return false;
        }
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);

        sugesstionAjax(a, b, this);
    });

    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
            /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 38) {
            /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
                /*and simulate a click on the "active" item:*/
                if (x) x[currentFocus].click();
            }
        }
    });

    /*a function to classify an item as "active":*/
    function addActive(x) {
        if (!x) return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = x.length - 1;
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
    }

    /*a function to remove the "active" class from all autocomplete items:*/
    function removeActive(x) {
        for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }

    /*close all autocomplete lists in the document, except the one passed as an argument:*/
    function closeAllLists(elmnt) {
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }

    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function(e) {
        closeAllLists(e.target);
    });
}

// Helper functions;

//FROM: https://stackoverflow.com/questions/196972/convert-string-to-title-case-with-javascript/196991#196991
function toTitleCase(str) {
    return str.replace(
        /\w\S*/g,
        function(txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        }
    );
}