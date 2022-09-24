<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title></title>
</head>

<body>
  <div id="test_div">
    <table style="" id="test_table" cellspacing="0">
      <tr><th style="padding: 2em; border-bottom: 1px solid gray; background-color: darkred; color: white; font-weight: 600;">Header 1</th><th style="padding: 2em; border-bottom: 1px solid gray; background-color: darkred; color: white; font-weight: 600;">Header 2</th></tr>
      <tr><td style="padding: 2em; border-bottom: 1px solid gray;">I'm gonna PRINT this!</td><td style="padding: 2em; border-bottom: 1px solid gray;">Sure You Are</td></tr>
      <tr><td style="padding: 2em; border-bottom: 1px solid gray;">It's a table!</td><td style="padding: 2em; border-bottom: 1px solid gray;">Sure It is</td></tr>
    </table>
  </div>
  <button onclick="printDiv()" id="printBtn">Print</button><button>Random Btn</button>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script>
    function printDiv() {
        var win = window.open();
        var prtn = document.createElement('DIV');
        prtn.innerHTML = "<table style=\"\" id=\"test_table\" cellspacing=\"0\"> <tr><th style=\"padding: 2em; border-bottom: 1px solid gray; background-color: darkred; color: white; font-weight: 600;\">Header 1</th><th style=\"padding: 2em; border-bottom: 1px solid gray; background-color: darkred; color: white; font-weight: 600;\">Header 2</th></tr> <tr><td style=\"padding: 2em; border-bottom: 1px solid gray;\">I\'m gonna PRINT this!</td><td style=\"padding: 2em; border-bottom: 1px solid gray;\">Sure You Are</td></tr><tr><td style=\"padding: 2em; border-bottom: 1px solid gray;\">It\'s a table!</td><td style=\"padding: 2em; border-bottom: 1px solid gray;\">Sure It is</td></tr></table>";
        win.document.open();
        win.document.write('<' + 'html' + '><head></head>' + '<' + 'body' + '>');
       // win.document.head.innerHTML = document.head.innerHTML;
        win.document.appendChild(prtn);
        win.document.write('<' + '/body' + '><' + '/html' + '>');
        win.document.close();
        win.print();
        win.close();
    }
      $('#printBtn').click();
  </script>
</body>

</html>