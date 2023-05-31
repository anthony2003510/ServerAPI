<?php
  
  class A {
    public $one = 1;
  
    public function show_one() {
        echo $this->one;
    }
}


class B {
    public $two = 2;
  
    public function show_two() {
        echo $this->two;
    }
}


$a = new A;
$s = serialize($a);

$b = new B;
$s = serialize($b)
// store $s somewhere where page2.php can find it.
file_put_contents('store', $s);

?>