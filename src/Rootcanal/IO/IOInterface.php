<?php

namespace Rootcanal\IO;

interface IOInterface
{

    /**
    *  Writes a message to the output.
    *
    * @param string|array $messages
    */
    public function write($messages);
}
