<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 02/08/2017
 * Time: 15:11
 */

?>
<html>
<head>
    <script src="jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="copy2clipboard.js"></script>
    <title>PDD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<script>
    var from_id;
    var to_id;

    function copyToClipboardPrintedResult() {
        var strings = "";
        $.each($("#answers tr"), function (index, value) {
            var number = $(value).find("td").first().text();
            var string = number + " ";
            $.each($(value).find("td button#print"), function (index1, value1) {
                var button = $(value1);
                var isPressed = button.attr("pressed") === "true"; // false
                var text = button.text();
                string += isPressed ? "(" + text + ")" : text;
            });
            strings += string + '\n';
        });
        var $temp = $("<textarea id=tempCopy readonly>");
        $("body").append($temp);
        $temp.val(strings);
        select_all_and_copy(document.getElementById("tempCopy"));
        $temp.remove();
    }

    function generate() {
        var from = $("#from").val();
        var to = $("#to").val();
        from_id = parseInt(from);
        to_id = parseInt(to);

        $("#generate").html("");
        for (i = from_id; i <= to_id; i++) {
            $("#rows").append("<tr><td>" + i + ")</td><td><input id=\"" + i + "\"></td></tr>")
        }
    }

    $(function () {

        $(document).on('keyup', 'input', function (e) {
            if (e.which == 13 && !event.shiftKey) {
                var input = $(this);
                var id = input.attr("id");

                var moveTo = null;

                if (id === "from")
                    moveTo = "to";
                else if (id === "to") {
                    generate();
                    moveTo = from_id;
                } else {
                    input.val(input.val().replace(/\=/g, '+'));

                    input.val(input.val().replace(/\[/g, '+'));
                    input.val(input.val().replace(/\]/g, '-'));

                    input.val(input.val().replace(/\o/g, '+'));
                    input.val(input.val().replace(/\O/g, '+'));

                    input.val(input.val().replace(/\p/g, '-'));
                    input.val(input.val().replace(/\P/g, '-'));
                    var nextId = parseInt(id) + 1;
                    if ($("#" + nextId).val() === undefined) {
                        print();
                    } else {
                        moveTo = nextId;
                    }
                }

                if (moveTo !== null)
                    $("#" + moveTo).focus();
            }
        });

        function print() {
            for (i = from_id; i <= to_id; i++) {
                var answer = $("#" + i).val();
                var answers = answer.split("");
                var buttons = "";
                $.each(answers, function (index, value) {
                    buttons += '<td><button id=print>' + value + '</button></td>'
                });
                $("#answers").append("<tr><td>" + i + ")</td>" + buttons + "</tr>")
            }
            $("#rows").html("");
            $("button#print").click(function () {
                var button = $(this);
                var isPressed = button.attr("pressed") !== "true"; // false
                button.css("background-color", isPressed ? "#f44336" : "");
                button.attr("pressed", isPressed); // true
            });
            $("#answers").append("<tr><td colspan='1000'><input type=button value='Copy to Clip' onclick='copyToClipboardPrintedResult()'></td></tr>");
            $(window).scrollTop(0);
        }
    });


</script>
<body>
<div id="generate">
    <input id="from" placeholder="from ex: 1" type="number">
    <br>
    <input id="to" placeholder="to ex: 90" type="number">
    <br>
    <button onclick="generate();">Generate</button>
</div>
<table id="rows">
    <tr>
        <td>id</td>
        <td>answer</td>
    </tr>
</table>
<table id="answers">
</table>
</body>
</html>
