<?php

namespace App;

class FormBuilder
{
    private $elements = array();

    public function open($uri, $method = null)
    {
        $method = $method??'POST';
        $this->elements[] = "<form method=\"$method\" action=\"$uri\">";

        return $this;
    }

    public function close()
    {
        $this->elements[]="</form>";

        return $this;
    }

    public function label($text)
    {
        $this->elements[] = "<label> $text </label>";

        return $this;
    }

    public function input($type, $name, $value = null)
    {
        $value = $value??'';
        $this->elements[] = "<input type=\"$type\" class=\"form-control mb-3\" name=\"$name\" id=\"$name\" value=\"$value\" />";
    
        return $this;
    }

    public function textarea($name, $value = null)
    {
        $value = $value??'Next';
        $this->elements[] = "<textarea name=\"$name\" id=\"$name\"> $value </textarea>";

        return $this;
    }

    public function submit($value = null)
    {
        $value = $value??'Next';
        $this->elements[] = "<button type=\"submit\" class=\"btn btn-primary m-4\">$value</button>";

        return $this;
    }

    public function csrf($value)
    {
        $this->elements[]="<input type=\"hidden\" name=\"csrf-token\" value=\"$value\">";

        return $this;
    }

    /**
     * Return string when form is completed
     * @return string
     */
    public function __toString()
    {
        return join("\n", $this->elements);
    }
}