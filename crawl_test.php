<?php 
function foo(){ 
    global $f_a;   // <- Notice to this 
    $f_a = 'a'; 
    
    function bar(){ 
        global $f_a; 
        echo '"f_a" in BAR is: ' . $f_a . '<br />';  // work!, var is 'a' 
    } 
    
    bar(); 
    echo '"f_a" in FOO is: ' . $f_a . '<br />'; 
} 

foo();
?>