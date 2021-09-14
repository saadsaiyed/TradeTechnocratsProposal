var topProductsResult = null;
var outOfStockResult = null;

//jQuery waiting for all ajax calls to complete b4 running

var loadOutOfStock = () => {
    // $.when(ajaxCall1(), ajaxCall2()).done(function (ajax1Results, ajax2Results) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var result = JSON.parse(this.responseText);
            console.log(result);
            var textArea = document.getElementById("text-area");
            var to_show = '';
            to_show += `<a id="downloadAnchorElem"></a><tr>`;
            to_show += `<td></td>`;
            to_show += `<td><label for="">Product_ID Code</label></td>`;
            to_show += `<td><label for="">Product Name</label></td>`;
            to_show += `<td><label for="">Zone</label></td>`;
            to_show += `<td><label for="">Average Sell</label></td>`;
            to_show += `<td><label for="">Stock</label></td>`;
            to_show += `<td><label for="">Warehouse Stock</label></td>`;
            to_show += `<td><label for="">Total_Stock</label></td>`;
            to_show += `</tr>`;
            for (let i = 0; i < result['Product_ID'].length; i++) {
                to_show += `<tr id='tr_${i}'>`;
                to_show += `<td><span class='icon-scope' onclick='document.getElementById(\"tr_${i}\").style.display =\"none\";'></td>`;
                to_show += `<td><input type='text' value='${result["Product_ID"][i]}'></td>`;
                to_show += `<td><input type='text' value='${result["Product_Name"][i]}'></td>`;
                to_show += `<td><input type='text' value='${result["Zone"][i]}'></td>`;
                to_show += `<td><input type='text' value='${result["Yearly_Avg"][i]}'></td>`;
                to_show += `<td><input type='text' value='${result["Stock"][i]}'></td>`;
                to_show += `<td><input type='text' value='${result["Warehouse_Stock"][i]}'></td>`;
                to_show += `<td><input type='text' value='${result["Total_Stock"][i]}'></td>`;
                to_show += `</tr>`;
            }
            textArea.innerHTML = to_show;

            var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(result));
            var dlAnchorElem = document.getElementById('downloadAnchorElem');
            dlAnchorElem.setAttribute("href", dataStr);
            dlAnchorElem.setAttribute("download", "scene.json");
            dlAnchorElem.click();
        }
    }

    xhttp.open("GET", "/php/dashboard_outOfStockAjax.php", true);
    xhttp.send();
    // });
}

function ajaxCall1() {
    return $.ajax({
        url: "./php/dashboard_outOfStockAjax.php",
        success: function(result) {
            outOfStockResult = JSON.parse(result);
        }
    });
}

function ajaxCall2() {
    return $.ajax({
        url: `./php/dashboard_topProductsAjax.php?modifiedDate= -365 days`,
        success: function(result) {
            topProductsResult = JSON.parse(result);
        }
    });
}