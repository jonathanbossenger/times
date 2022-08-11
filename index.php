<?php
$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$limit = isset( $_GET['limit'] ) ? $_GET['limit'] : 10;
for ($i = 1; $i <= $limit; $i++) {
	$numbers[] = $i;
}
$shuffled = $numbers;
shuffle($shuffled);
$lines = array();
$line_number = 0;
foreach ($numbers as $key => $number) {
	foreach ($shuffled as $key => $shuffled_number) {
		$line_number++;
		$lines[] = "<div class='grid-item'><p id='line-{$line_number}' class='line'><span id='number-{$line_number}'>{$shuffled_number}</span> X <span id='shuffled-number-{$line_number}'>{$number}</span> = <input id='answer-{$line_number}' type='text'></p></div>"; // include a text area for the answer
	}
}

$links = array();
for ($i = 1; $i <= 15; $i++) {
	$links[] = "<a href='{$current_url}/?limit={$i}'>{$i}</a>";
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
        .instruction-container {
            display: grid;
            grid-template-columns: auto auto;
            padding: 5px;
        }
        .times-container {
            display: grid;
            grid-template-columns: auto auto auto auto auto;
            background-color: #2196F3;
            padding: 5px;
        }
        .button-container {
            display: grid;
            grid-template-columns: auto;
            background-color: #2196F3;
            padding: 5px;
        }
        .instruction-item {
            padding: 10px;
        }
        .grid-item {
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(0, 0, 0, 0.8);
            padding: 10px;
            text-align: center;
        }

        .button-container button {
            font-size: 100%;
            padding: 0 30%;
        }
    </style>
	<script type="application/javascript">

        let timer = null;
        let seconds = 0;

        window.addEventListener('DOMContentLoaded', (event) => {
            //let inputs = document.getElementsByTagName('input');
            document.getElementById('answer-1').addEventListener("click", function () {
                if (timer == null) {
                    runTimer();
                }
            });
        });

        function calcValues() {
            let pass = true;
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
                    pass = false;
                }
            }

            if (pass) {
                showPass();
            }else {
                showFail();
            }
        }

        function showPass(){
            endTimer()
            let resultElement = document.getElementById('result');
            let timerElement = document.getElementById('timer');
            resultElement.innerText = 'Congrats, you passed in ' + timerElement.innerText;
            timerElement.innerText = '';
        }

        function showFail(){
            let resultElement = document.getElementById('result');
            resultElement.innerText = ' Whoops! Try again.';
        }

        function runTimer() {
            timer = setTimeout(function () {
                outputTimer();
                runTimer();
            }, 1000);
        }

        function endTimer(){
            clearTimeout(timer);
        }

        function convertSecondsToTime(seconds){
            let hours = Math.floor(seconds / 3600);
            let minutes = Math.floor((seconds - (hours * 3600)) / 60);
            let secs = seconds - (hours * 3600) - (minutes * 60);

            if (hours < 10) {hours = "0" + hours;}
            if (minutes < 10) {minutes = "0" + minutes;}
            if (secs < 10) {secs = "0" + secs;}
            return hours + ':' + minutes + ':' + secs;
        }

        function outputTimer(){
            let timerElement = document.getElementById('timer');
            seconds++;
            timerElement.innerText = convertSecondsToTime(seconds);
        }

	</script>
</head>
<body>
    <h1>Randomised times table tester</h1>
    <div class="instruction-container">
        <div class="instruction-item">
            <p>Create randomised lists of times table tests. Enter your answers, click the <strong>Check</strong> button
                to verify your answers.</p>
            <p>Tip: Use the <strong>Tab</strong> key on your keyboard to quickly jump to the next answer box.</p>
            <p>Use the predefined upper limits to create larger times tables</p>
            <p><?php echo implode( ' | ', $links ); ?></p>
            <p>To set a higer upper limit to the randomised list, add a query string to the end of the url.</p>
            <p>The query string format is: <strong>/?limit=x</strong>, where <strong>x</strong> is the desired upper
                limit.</p>
            <p>For example <a href="<?php echo $current_url ?>/?limit=20"><?php echo $current_url ?>/?limit=20</a> will
                output randomised tables up to 12</p>
        </div>
        <div class="instruction-item">
            <span id="timer">00:00:00</span>
            <span id="result"></span>
        </div>
    </div>
    <div class="times-container">
	    <?php echo implode( '', $lines ); ?>
    </div>
    <div class="button-container">
        <div class="grid-item">
            <button onclick="calcValues()">Check</button>
        </div>
    </div>
</body>
</html>