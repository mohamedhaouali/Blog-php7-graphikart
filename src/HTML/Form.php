<?php
namespace App\HTML;

class Form
{
    private $data;

    private $errors;

    public function __construct($data, array $errors)
    {
        $this->data = $data;
        $this->errors = $errors;
    }

    public function input (string $key, string $label): string
    {
        $value = $this->getValue($key);

    return <<<HTML
       <div class="form-group">
          <label for="field{$key}">{$label}</label>
          <input type="text" id="field{$key}" class="{$this->getInputClass($key)}" name="{$key}" value="{$value}" required>
          {$this->getErrorFeedback($key)}
        </div>

HTML;

    }

    public function textarea (string $key, string $label) : string
    {
        $value = $this->getValue($key);

        return <<<HTML
       <div class="form-group">
          <label for="field{$key}">{$label}</label>
          <textarea type="text" id="field{$key}" class="{$this->getInputClass($key)}" name="{$key}"  required>{$value}</textarea>
          {$this->getErrorFeedback($key)}
        </div>

HTML;
    }

    public function select(string $key, string $label, array $options = []): string
    {

        $optionsHTML = [];
        $value = $this->getValue($key);

        foreach ($options as $K => $v) {
            $selected = in_array($key,$value) ? " selected" : "";
            $optionsHTML[] = "<option value=\"$key\"$selected>$v</option>" ;
        }

        $optionsHTML = implode('', $optionsHTML);

        return <<<HTML
       <div class="form-group">
          <label for="field{$key}">{$label}</label>
          <select  id="field{$key}" class="{$this->getInputClass($key)}" name="{$key}[]" required multiple>{$optionsHTML}</select>
          {$this->getErrorFeedback($key)}
        </div>

HTML;
    }




    // pour un tableaux
    private function getValue (string $key){
      if (is_array($this->data)){
          return $this->data[$key] ?? null;
      }
      // ucwords pour 2 mots
        $method = 'get' . str_replace(' ','', ucwords(str_replace('_',' ',$key)));
        $value = $this->data->$method();
        // Date time
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');

        }
        return $value;
    }
//validation
    private function getInputClass (string $key): string {
        $inputClass = 'form-control';
        if (isset($this->errors[$key])) {
            $inputClass .=  ' is-invalid';
             }
        return $inputClass;
    }
// Erreur
    private function getErrorFeedback (string $key): string
     {
        if (isset($this->errors[$key])) {
            return '<div class="invalid-feedback">' . implode('<br>', $this->errors[$key]) . '</div>';
        }
        return '';
    }



    /**
     *     <div class="form-group">
    <label for="name">Titre</label>
    <input type="text" class="form-control  <?=isset($errors[\'name\'])? \'is-invalid\' : \'\' ?>" name="name" value="<?= htmlentities($post->getName())  ?>">
    <?php if(isset($errors[\'name\'])): ?>


    <div class="invalid-feedback">
    <?= implode(\'<br>\', $errors[\'name\']) ?>
    </div>
    <?php endif ?>
    </div>
     */



}