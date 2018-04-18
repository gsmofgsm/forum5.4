<?php
namespace App;


class Spam
{
    public function detect($body)
    {
        // Detect invalid keywords.

        $this->detectInvalidKeywords($body);
        $this->detectKeyHeldDown($body);

        return false;
    }

    protected function detectInvalidKeywords($body)
    {
        $invalidKeywords = [
            'Yahoo customer support'
        ];

        foreach($invalidKeywords as $keyword){

            if(stripos($body, $keyword) !== false){
                throw new \Exception('Your reply contains spam.');
            }
        }
    }

    protected function detectKeyHeldDown($body)
    {
        if(preg_match('/(.)\\1{4,}', $body)){
            throw new \Exception('Your reply contains spam.');
        }
    }
}