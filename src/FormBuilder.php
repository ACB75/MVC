<?php

namespace App;


class FormBuilder
{
    private $elements = array();

    public function open($uri, $method = null)
    {
        $method = $method ?? 'POST';
        $this->elements[] = "<form method=\"$method\" action=\"$uri\" >";
        return $this;
    }
    public function close()
    {
        $this->elements[] = "</form>";
        return $this;
    }

    public function label($text)
    {
        $this->elements[] = "<label >$text</label>";
        return $this;
    }

    public function input($type, $name, $value = null, $required = false, $placeholder = null, $disabled = false)
    {
        $value = $value ?? '';
        $required = $required ? 'required' : '';
        $disabled = $disabled ? 'disabled' : '';
        
        $this->elements[] = "<input type=\"$type\" class=\"form-control mb-3\" name=\"$name\" id=\"$name\" value=\"$value\" value=\"$placeholder\" $required $disabled/>";
        return $this;
    }

    public function checkbox($type, $name, $value = null, $placeholder = null)
    {
        $value = $value ?? '';
        $this->elements[] = "<input type=\"$type\" class=\" form-check\" name=\"$name\" id=\"$name\" value=\"$value\" value=\"$placeholder\"/>";
        return $this;
    }

    public function textarea($name, $value = null)
    {
        $value = $value ?? '';
        $this->elements[] = "<textarea name=\"$name\" id=\"$name\" >$value</textarea>";
        return $this;
    }
    public function submit($value = null, $name = null)
    {
        $value = $value ? $value : 'Next';
        $name = $name ? $name : 'Submit';
        $this->elements[] = "<button type=\"submit\" id=\"$value\" class=\"btn btn-primary mb-3\" onClick=\"$value\">".$name."</button>";
        return $this;
    }
    public function csrf($value)
    {
        $this->elements[] = "<input type=\"hidden\" name=\"csrf-token\" value=\"$value\">";

        return $this;
    }
    /**
     * Returns string when form is completed
     *
     * @return string
     */
    public function __toString()
    {
        return join("\n", $this->elements);
    }
}