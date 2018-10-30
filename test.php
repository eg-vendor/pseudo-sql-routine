<?php

$lines = file ('test.sql');
echo "test.sql contents:\n";
echo implode ('',$lines);

$args  = [time(),'0e0f4ce8-8fee-11e8-902b-001f16148bc1','sysadmin@no.where'];
echo "Example arguments:\n";
print_r ($args);

$sql   = array ();
$keys  = array ();
$bind  = array ();
$query = '';

// Identify variable keys
foreach ($lines as $line) {
    $l = trim ($line);
    if (!strlen($l)) {
        continue;
    }
    if (preg_match('/^--\s+/',$l,$matches)) {
        preg_match ('/^--\s+hpapi:([^\s]+)/',trim($l),$matches);
        if (array_key_exists(1,$matches)) {
            $count  = 0;
            $params = explode (',',trim($matches[1]));
            foreach ($params as $param) {
                $keys[$param] = $count;
                $count++;
            }
        }
        continue;
    }
    array_push ($sql,$line);
}

foreach ($sql as $line) {
    preg_match ('/<<([A-z]+)>>/',$line,$matches);
    if (array_key_exists(1,$matches)) {
        array_shift ($matches);
        foreach ($matches as $m) {
            $line = preg_replace ('/<<([A-z]+)>>/','?',$line,1);
            array_push ($bind,$args[$keys[$m]]);
        }
    }
    $query .= $line;
}

echo "Query ready for PDO statement binding:\n";
echo $query;

echo "Parameters ready for PDO statement binding:\n";
print_r ($bind);

?>
