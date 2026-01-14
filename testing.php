<?php
// 1. Basic Output Test
echo "<h1>✅ Success! PHP is working.</h1>";

// 2. Show Current Date/Time to test processing
echo "<p>Server Date & Time: " . date("Y-m-d H:i:s") . "</p>";

// 3. Check PHP Version
echo "<p>Running PHP Version: " . phpversion() . "</p>";

// 4. Mathematical Logic Test
$a = 5;
$b = 10;
$result = $a + $b;
echo "<p>Math Test: $a + $b = $result </p>";

echo "<hr>";

// 5. Full Configuration Info (Uncomment the line below to see all details)
// phpinfo(); 
?>
