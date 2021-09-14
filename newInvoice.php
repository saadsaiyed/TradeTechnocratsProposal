<?php
include "php/DBConnection.php";
checkIfLoggedIn(2, 3, 1, 4);

if ($_GET["info"]) {
    $err = ucwords($_GET['info']); ?>
    <div id="snackbar"><?= $err ?></div>
    <script>
        var x = document.getElementById("snackbar");
        x.className = "show";
        setTimeout(function() {
            x.className = x.className.replace("show", "");
        }, 10000);
    </script>
<? }
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice | TTParikh</title>
    <link rel="icon" href="images/favicon.gif">

    <!-- CSS File Link -->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/iconStyle.css">

    <!-- JavaScript file links -->
    <script src="js/allJavaScripts.js"></script>
    <script src="js/invoice_validation.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        .item1 {
            grid-column: 1 / 9;
            grid-row: 1 / 3;
        }

        .item1 .page-title {
            color: var(--green-theme);
            font-family: 'Oswald', sans-serif;
            font-size: 25px;
            font-weight: 200;
            transition: ease 0.3s;
            margin-left: 30px;
        }

        .item1 input[type=button] {
            line-height: 4.9px;
            margin-left: -1.7px;
        }

        .item2 {
            grid-column: 1 / 9;
            grid-row: 1 / 5;
        }

        .inside input[type=text] {
            width: 200px;
        }

        .item2 select,
        .item2 option {
            width: 100%;
            height: 30px;
            background-color: var(--purple-theme);
            border: 1px solid var(--green-theme);
            text-align: center;
        }

        .item2 select option {
            background: var(--purple-theme);
        }

        .item2 table {
            width: 100%;
            text-align: center;
            margin-top: 20px;
            font-size: 20px;
        }

        .details table:nth-child(1) {
            margin-top: -20px;
        }

        .details {
            margin-top: -20px;
        }

        .item2 th {
            background-color: var(--green-theme);
            color: var(--purple-theme);
            padding: 15px;
        }

        .item2 a {
            text-decoration: underline;
        }

        #text-area td:nth-child(1) {
            width: 400px;
        }

        #text-area td:nth-child(2) {
            width: 800px;
        }

        #text-area td:nth-child(3) {
            width: 300px;
        }

        #text-area td:nth-child(4) {
            width: 400px;
        }

        #text-area thead th:nth-child(1) {
            width: 400px;
        }

        #text-area thead th:nth-child(2) {
            width: 800px;
        }

        #text-area thead th:nth-child(3) {
            width: 300px;
        }

        #text-area thead th:nth-child(4) {
            width: 400px;
        }

        #text-area thead {
            display: block;
        }

        #text-area tbody {
            height: 440px;
            display: block;
            overflow: auto;
            width: 100%;
        }

        #text-area input {
            width: 100%
        }


        #text-area input {
            border: none;
        }

        #text-area {
            border: 1px solid var(--green-theme);
        }

        #text-area tr:nth-child(2n + 1) {
            background: var(--lightpurple-theme)
        }

        #text-area td:nth-child(4) {
            border-right: none;
        }

        #text-area td {
            border-right: 1px solid var(--green-theme);
        }

        .item2 .inside table * {
            padding-bottom: 20px;
        }

        .suggestion {
            position: absolute;
            background-color: var(--purple-theme);
            font-size: smaller;
            cursor: pointer;
        }

        .suggestion_i:hover {
            color: var(--purple-theme);
            background-color: var(--green-theme);
        }

        .suggestion_i {
            cursor: pointer;
            text-align: center;
            text-justify: center;
            padding: 10px 10px -5px 10px;
        }

        #sorted_div {
            background-color: var(--purple-theme);
            /*position:absolute;*/
            top: 123px;
        }

        #sorted_div tr:nth-child(2n + 1) {
            background: var(--lightpurple-theme)
        }

        /* The Modal (background) */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            padding-top: 300px;
            /* Location of the box */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            background-color: var(--purple-theme);
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 20%;
            height: 180px;
        }

        /* The Close Button */
        .close {
            color: #ffffff;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #aaa;
            text-decoration: none;
            cursor: pointer;
        }

        .not-close {
            display: flex;
            justify-content: space-between;
            padding-right: 20px;
        }

        #sorted_div {}

        #sorted_div td:nth-child(1) {
            width: 200px;
            text-align: right;
            padding-right: 10px;
        }

        #sorted_div td:nth-child(2) {
            width: 200px;
            text-align: left;
            padding-left: 10px;
        }

        #sorted_div th:nth-child(1) {
            width: 200px;
        }

        #sorted_div th:nth-child(2) {
            width: 200px;
        }
    </style>

</head>

<body onclick="removeSuggestion()">
    <div>
        <nav class="bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img class="h-8 w-8" src="https://tailwindui.com/img/logos/workflow-mark-indigo-500.svg" alt="Workflow">
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                                <a href="#" class="bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>

                                <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Team</a>

                                <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Projects</a>

                                <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Calendar</a>

                                <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Reports</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile menu, show/hide based on menu state. -->
            <div class="md:hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                    <a href="#" class="bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium">Dashboard</a>

                    <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Team</a>

                    <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Projects</a>

                    <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Calendar</a>

                    <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Reports</a>
                </div>
            </div>
        </nav>

        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    Dashboard
                </h1>
            </div>
        </header>
        <main>
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-200">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Barcode
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Product Name
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Count
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Stock
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Edit</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200" id="tbody_table">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="mt-1 relative rounded-md shadow-sm">
                                                        <input type="text" name="barcode_1" id="barcode_1" class="block w-full sm:text-sm text-gray-500 border-gray-300 rounded-md" placeholder="772696######">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div id="product_name_1" class="text-lg text-gray-900">-</div>
                                                <div id="product_type_1" class="text-sm text-gray-500">-</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="mt-1 relative rounded-md shadow-sm">
                                                        <input type="text" name="count_1" id="count_1" class="block w-full sm:text-sm text-gray-500 border-gray-300 rounded-md" placeholder="1" size="1">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div id="stock_1" class="text-lg text-gray-900">-</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <input id="taken_from_direct" name="taken_from_direct" type="checkbox" class="h-7 w-7 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>


    <script>
        feather.replace();
        window.onbeforeunload = function() {
            return 'Are you sure you want to leave?';
        };
        window.onload = function() {
            var dropdown = document.querySelectorAll(".drop-content");
            for (let i = 0; i < dropdown.length; i++) {
                dropdown[i].style.display = "none";
            }
            document.getElementById("invoiceStatus_go").addEventListener("click", submitInvoiceStatus, false);

            document.getElementById("invoice_name").focus();
            // document.getElementById("myForm").addEventListener("submit", formValidation, false);
            // document.getElementById("myForm").addEventListener("reset", resetForm, false);

            // Form Navigation - START
            (function($) {
                $.fn.formNavigation = function() {
                    $(this).each(function() {
                        $(this).find('input').on('keyup', function(e) {
                            switch (e.which) {
                                case 39:
                                    $(this).closest('td').next().find('input').focus();
                                    break;
                                case 37:
                                    $(this).closest('td').prev().find('input').focus();
                                    break;
                                case 40:
                                    $(this).closest('tr').next().children().eq($(this).closest('td').index()).find('input').focus();
                                    break;
                                case 38:
                                    $(this).closest('tr').prev().children().eq($(this).closest('td').index()).find('input').focus();
                                    break;
                            }
                        });
                    });
                };
            })(jQuery);
            $('#text-area').formNavigation();
            // Form Navigation - END
        };

        function removeSuggestion() {
            if (!$("#product_name_1").is(":focus")) {
                document.getElementById("suggestion").innerHTML = "";
                document.getElementById("suggestion").style.padding = "0px";
                document.getElementById("suggestion").style.border = "none";
            }
        }

        function duplicateInvoiceChecker(str) {
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
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var to_show = "";
                    var results = JSON.parse(this.responseText)
                    if (results.length > 0) {
                        var json_result = "{" + results[0] + "} " + results[1] + " : " + results[2] + " [" + results[3] + "]";
                        to_show = "<div>" + json_result + "</div>";
                    } else
                        to_show = "No Result Found";
                    document.getElementById("suggestion").innerHTML = to_show;
                    document.getElementById("suggestion").style.padding = "20px";
                    document.getElementById("suggestion").style.border = "1px solid var(--green-theme)";
                    // document.getElementById("suggestion").style.border="1px solid #A5ACB2";
                }
            }
            xmlhttp.open("GET", "php/duplicateInvoiceAjax.php?search=" + str, true);
            xmlhttp.send();
        }
    </script>
</body>

</html>