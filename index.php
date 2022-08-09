<?php
$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$limit = isset( $_GET['limit'] ) ? $_GET['limit'] : 10;
for ($i = 1; $i <= $limit; $i++) {
	$numbers[] = $i;
}
$shuffled = $numbers;
shuffle($shuffled);
$lines = '';
$line_number = 0;
foreach ($numbers as $key => $number) {
	foreach ($shuffled as $key => $shuffled_number) {
		$line_number++;
		$lines .=  '<div class="grid-item"><p id="line-'.$line_number.'" class="line"><span id="number-'.$line_number.'">' . $shuffled_number . '</span> X <span id="shuffled-number-'.$line_number.'">' . $number . '</span> = <input id="answer-'.$line_number.'" type="text"></p></div>'; // include a text area for the answer
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Randomised times table tester</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            font-size: 1.0em;
            margin: 0;
            padding: 0;
        }
        h1{
            padding: 0 5px;
        }
        .instructions {
            padding: 0 5px;
        }
        .grid-container {
            display: grid;
            grid-template-columns: auto auto auto auto auto;
            background-color: #2196F3;
            padding: 5px;
        }
        .grid-item {
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(0, 0, 0, 0.8);
            padding: 10px;
            text-align: center;
        }
        .button-container {
            display: grid;
            grid-template-columns: auto;
            background-color: #2196F3;
            padding: 5px;
        }
        .button-container button {
            font-size: 100%;
            padding: 0 30%;
        }
    </style>
	<script type="application/javascript">
        function calcValues() {
            const lines = document.getElementsByClassName('line');
            for (let i = 1; i <= lines.length; i++) {
                let line = document.querySelector('#line-' + i);

                let number = line.querySelector('#number-' + i);
                let shuffled_number = line.querySelector('#shuffled-number-' + i);
                let result = eval(number.innerText + ' * ' + shuffled_number.innerText);

                let answer = line.querySelector('#answer-' + i);
                if (result == answer.value) {
                    answer.style.backgroundColor = 'green';
                } else {
                    answer.style.backgroundColor = 'red';
                }
            }
        }
	</script>
</head>
<body>
    <h1>Randomised times table tester</h1>
    <div class="instructions">
        <p>Create randomised lists of times table tests. Enter your answers, click the <strong>Check</strong> button to verify your answers.</p>
        <p>To control the upper limit of the randomised list, add a query string to the end of the url.</p>
        <p>The query string format is: <strong>/?limit=x</strong>, where <strong>x</strong> is the desired upper limit.</p>
        <p>For example <a href="<?php echo $current_url ?>/?limit=12"><?php echo $current_url ?>/?limit=12</a> will output randomised tables up to 12</p>
        <p>Tip: Use the <strong>Tab</strong> key on your keyboard to quickly jump to the next answer box.</p>
    </div>
    <div class="grid-container">
	    <?php echo $lines; ?>
    </div>
    <div class="button-container">
        <div class="grid-item">
            <button onclick="calcValues()">Check</button>
        </div>
    </div>
</body>
</html>