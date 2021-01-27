<?php
namespace NeutronStars\TravelAgency;

class FormBuilder
{
    private string $html;

    public function __construct(string $action = '', string $method = 'post', string $autoComplete = 'off', string $encType = 'multipart/form-data')
    {
        $this->html = '<form action="'.$action.'" method="'.$method.'" autocomplete="'.$autoComplete.'" enctype="'.$encType.'">';
    }

    public function input(?string $label, string $id, string $value = '', ?string $error = null, string $type = 'text', string $placeholder = ''): self
    {
        $this->html .= '<div class="form-group">';
        if($label != null) {
            $this->html .= '<label for="'.$id.'">'.$label.'</label>';
        }
        if($error != null) {
            $this->html .= '<span class="form-error">'.$error.'</span>';
        }
        $this->html .= '<input type="'.$type.'" id="'.$id.'" name="'.$id.'" value="'.$value.'" placeholder="'.$placeholder.'">';
        $this->html .= '</div>';
        return $this;
    }

    public function textArea(?string $label, string $id, string $rows = '', string $cols = '', string $value = '', ?string $error = null, string $placeholder = ''): self
    {
        $this->html .= '<div class="form-group">';
        if($label != null) {
            $this->html .= '<label for="'.$id.'">'.$label.'</label>';
        }
        if($error != null) {
            $this->html .= '<span class="form-error">'.$error.'</span>';
        }
        $this->html .= '<textarea name="'.$id.'" id="'.$id.'" placeholder="'.$placeholder.'" rows="'.$rows.'" cols="'.$cols.'">'.$value.'</textarea>';
        $this->html .= '</div>';
        return $this;
    }

    public function select(?string $label, string $id, array $array, string $value = '', ?string $error = null): self
    {
        $this->html .= '<div class="form-group">';

        if($label != null) {
            $this->html .= '<label for="'.$id.'">'.$label.'</label>';
        }
        if($error != null) {
            $this->html .= '<span class="form-error">'.$error.'</span>';
        }

        $this->html .= '<select name="'.$id.'" id="'.$id.'">';
        foreach($array as $k => $v){
            $this->html .= '<option value="'.$k.'"'.($value == $k ? 'selected' : '').'>'.$v.'</option>';
        }
        $this->html .= '</select>';
        $this->html .= '</div>';
        return $this;
    }



    public function __toString(): string
    {
        return $this->html . '</form>';
    }
}
