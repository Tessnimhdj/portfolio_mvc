<?php
namespace app\Test\Controllers;

class TestController
{
    public function indexmessage()
    {
        echo "Hello from TestController index!";
    }

    public function testMessage()
    {
        echo "This is a test message from showMessage()!";
    }
}

