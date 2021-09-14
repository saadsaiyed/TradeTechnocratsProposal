function formValidation(event) {
    try {
        var elements = event.currentTarget;
        var Barcode = elements[0].value;
        var Taken_From = elements[1].value;

        var errorCheck = false;
        var errorMsg = '';
        console.log("here");
        var data = new Object();

        if (barcodeValidate(Barcode)) {
            document.getElementById('Barcode_ID').style.border = styleBorderGreen;

            var locationRegex = /^[a-zA-Z][0-9]*$/;
            // var lotNumRegex = /(?=[a-zA-Z0-9~@#$^*()_[\/]|\\,.?: -]*$) ^(?!.*[<>'";`%!{}+=])$/;
            var lotNumRegex = /^[a-zA-Z0-9#\$%\^\&*\)\(/+=<>'"`~\]\[;._-]+$/g;
            var botanicalRegex = /[A-Za-z]+ [a-z]+/g;
            var invoiceRegex = /^[A-Za-z ][0-9]*$/;
            var lettersOnlyRegex = /[A-Za-z ]*/;
            var styleBorderRed = '1px solid var(--red-theme)';
            var styleBorderGreen = '1px solid var(--green-theme)';

            if (Taken_From == 1) {
                var M_Bags_Made = elements[3].value;
                var M_Boxes = elements[4].value;
                var M_Toute = elements[5].value;
                var M_Lot_Num = elements[6].value;
                var M_Location = elements[7].value;
                var M_Bot_Name = elements[8].value;
                var M_Country = elements[9].value;
                var M_Emp_Name = elements[10].value;

                if (!allPositive(Array(M_Bags_Made, M_Boxes, M_Toute))) {
                    errorCheck = true;
                    errorMsg += '<p>Incorrect Bags Info. Please write positive numbers in bags details.</p>';
                    document.getElementById('M_Bags').style.border = styleBorderRed;
                    document.getElementById('M_Boxes').style.border = styleBorderRed;
                    document.getElementById('M_Toute').style.border = styleBorderRed;
                } else {
                    document.getElementById('M_Bags').style.border = styleBorderGreen;
                    document.getElementById('M_Boxes').style.border = styleBorderGreen;
                    document.getElementById('M_Toute').style.border = styleBorderGreen;
                }

                if (M_Lot_Num == '' || lotNumRegex.test(M_Lot_Num) == false) {
                    errorCheck = true;
                    errorMsg += '<p>Incorrect Lot Number. Please use only a-zA-Z0-9\#\$\%\^\&\*\)\(\+\=\<\>\'\"\`\~\]\[\;\.\_\- .</p>';
                    document.getElementById('M_Lot_Num').style.border = styleBorderRed;
                } else {
                    document.getElementById('M_Lot_Num').style.border = styleBorderGreen;
                }

                if (locationRegex.test(M_Location) || M_Location != '' || M_Location == 'OIL ROOM' || M_Location == 'TEA BAG') {
                    document.getElementById('M_Location_ID').style.border = styleBorderGreen;
                } else {
                    errorCheck = true;
                    errorMsg += '<p>Incorrect Location. Location must contain atleast one character in the begining and rest must be digits or null.<p>';
                    document.getElementById('M_Location_ID').style.border = styleBorderRed;
                }

                if (!botanicalRegex.test(M_Bot_Name) && M_Bot_Name != '') {
                    errorCheck = true;
                    errorMsg += '<p>Incorrect Botanical Name. Must follow Binomial nomenclature.<p>';
                    document.getElementById('M_Bot_Name').style.border = styleBorderRed;
                } else {
                    document.getElementById('M_Bot_Name').style.border = styleBorderGreen;
                }

                if (M_Country == "" || !lettersOnlyRegex.test(M_Country)) {
                    errorCheck = true;
                    errorMsg += '<p>Incorrect Country. Country is empty or has invalid characters. Only use "letters" and "spaces".<p>';
                    document.getElementById('M_Country_Name').style.border = styleBorderRed;
                } else {
                    document.getElementById('M_Country_Name').style.border = styleBorderGreen;
                }

                if (M_Emp_Name == "" || !lettersOnlyRegex.test(M_Emp_Name)) {
                    errorCheck = true;
                    errorMsg += '<p>Incorrect Emp Name. Name of employer is empty or has invalid characters. Only use "letters" and "spaces".<p>';
                    document.getElementById('M_Emp_Name').style.border = styleBorderRed;
                } else {
                    document.getElementById('M_Emp_Name').style.border = styleBorderGreen;
                }
            } else if (Taken_From == 2) {
                var invoiceRegex = /^[0-9]*$/;
                var D_Bags = elements[11].value;
                var D_Lot_Num = elements[12].value;
                var D_Invoice_Num = elements[13].value;
                var D_Emp_Name = elements[14].value;

                if (!allPositive(Array(D_Bags))) {
                    errorCheck = true;
                    errorMsg += '<p>Incorrect Bags Info. Please write positive numbers in bags details.</p>';
                    document.getElementById('D_Bags').style.border = styleBorderRed;
                } else {
                    document.getElementById('D_Bags').style.border = styleBorderGreen;
                }

                if (!lotNumRegex.test(D_Lot_Num) || D_Lot_Num == '') {
                    errorCheck = true;
                    errorMsg += '<p>Incorrect Lot Number. Please use only a-zA-Z0-9\#\$\%\^\&\*\)\(\+\=\<\>\'\"\`\~\]\[\;\.\_\- .</p>';
                    document.getElementById('D_Lot_Num').style.border = styleBorderRed;
                } else {
                    document.getElementById('D_Lot_Num').style.border = styleBorderGreen;
                }

                if (!invoiceRegex.test(D_Invoice_Num) || !allPositive(Array(D_Invoice_Num)) || D_Invoice_Num == '') {
                    errorCheck = true;
                    errorMsg += '<p>Invoice Number Incorrect. Only positive numbers from.</p>';
                    document.getElementById('invoice_name').style.border = styleBorderRed;
                } else {
                    document.getElementById('invoice_name').style.border = styleBorderGreen;
                }

                if (D_Emp_Name == '' || !lettersOnlyRegex.test(D_Emp_Name)) {
                    errorCheck = true;
                    errorMsg += '<p>Incorrect Emp Name. Name of employer is empty or has invalid characters. Only use "letters" and "spaces".<p>';
                    document.getElementById('D_Emp_Name').style.border = styleBorderRed;
                } else {
                    document.getElementById('D_Emp_Name').style.border = styleBorderGreen;
                }
            } else if (Taken_From == 3 || Taken_From == 4) {
                var C_Bags_Cut = elements[15].value;
                var C_Bags_Made = elements[16].value;
                var C_Lot_Num = elements[17].value;
                var C_Emp_Name = elements[18].value;

                if (!allPositive(Array(C_Bags_Cut, C_Bags_Made))) {
                    errorCheck = true;
                    errorMsg += '<p>Incorrect Bags Info. Please write positive numbers in bags details.</p>';
                    document.getElementById('1_Bags_Cut').style.border = styleBorderRed;
                    document.getElementById('1_Bags_Made').style.border = styleBorderRed;
                } else {
                    document.getElementById('1_Bags_Cut').style.border = styleBorderGreen;
                    document.getElementById('1_Bags_Made').style.border = styleBorderGreen;
                }

                if (!lotNumRegex.test(C_Lot_Num) || C_Lot_Num == '') {
                    errorCheck = true;
                    errorMsg += '<p>Incorrect Lot Number. Please use only a-zA-Z0-9\#\$\%\^\&\*\)\(\+\=\<\>\'\"\`\~\]\[\;\.\_\- .</p>';
                    document.getElementById('1_Lot_Num').style.border = styleBorderRed;
                } else {
                    document.getElementById('1_Lot_Num').style.border = styleBorderGreen;
                }

                if (C_Emp_Name == '' || !lettersOnlyRegex.test(C_Emp_Name)) {
                    errorCheck = true;
                    errorMsg += '<p>Incorrect Emp Name. Name of employer is empty or has invalid characters. Only use "letters" and "spaces".<p>';
                    document.getElementById('1_Emp_Name').style.border = styleBorderRed;
                } else {
                    document.getElementById('1_Emp_Name').style.border = styleBorderGreen;
                }
            } else if (Taken_From == 5) {
                var F_Barcode_To = elements[19].value;
                var F_Bags_Cut = elements[20].value;
                var F_Bags_Made = elements[21].value;
                var F_Emp_Name = elements[22].value;

                if (!barcodeValidate(F_Barcode_To)) {
                    errorCheck = true;
                    errorMsg += '<p>Incorrect Barcode_To. Barcode maybe empty or has less than 12 character. Barcode must be atleast 12 characters long. Only proceed when you see the product name.<p>';
                    document.getElementById('F_Barcode_ID').style.border = styleBorderRed;
                } else {
                    document.getElementById('F_Barcode_ID').style.border = styleBorderGreen;
                }

                if (!allPositive(Array(F_Bags_Cut, F_Bags_Made))) {
                    errorCheck = true;
                    errorMsg += '<p>Incorrect Bags Info. Please write positive numbers in bags details.</p>';
                    document.getElementById('F_Bags_Cut').style.border = styleBorderRed;
                    document.getElementById('F_Bags_Made').style.border = styleBorderRed;
                } else {
                    document.getElementById('F_Bags_Cut').style.border = styleBorderGreen;
                    document.getElementById('F_Bags_Made').style.border = styleBorderGreen;
                }

                if (F_Emp_Name == '' || !lettersOnlyRegex.test(F_Emp_Name)) {
                    errorCheck = true;
                    errorMsg += '<p>Incorrect Emp Name. Name of employer is empty or has invalid characters. Only use "letters" and "spaces".<p>';
                    document.getElementById('F_Emp_Name').style.border = styleBorderRed;
                } else {
                    document.getElementById('F_Emp_Name').style.border = styleBorderGreen;
                }
            }
        } else {
            errorCheck = true;
            errorMsg += '<p>Incorrect Barcode_To. Barcode maybe empty or has less than 12 character. Barcode must be atleast 12 characters long. Only proceed when you see the product name.<p>';
            document.getElementById('Barcode_ID').style.border = styleBorderRed;
        }

        if (errorCheck) {
            document.getElementById('instruction').innerHTML += errorMsg;
            event.preventDefault();
        }
    } catch (err) {
        document.getElementById('instruction').innerHTML += "Something went wrong: " + err;
        event.preventDefault();
    }
}

function resetForm(event) {
    document.getElementById("email_msg").innerHTML = "";
    document.getElementById("pswd_msg").innerHTML = "";
}

function barcodeValidate(str) {
    if (str.length < 10 || str.length > 16)
        return false;
    return true;
}

function allPositive(arr) {
    for (const i of arr)
        if (i < 0 || i == '')
            return false;
    return true;
}