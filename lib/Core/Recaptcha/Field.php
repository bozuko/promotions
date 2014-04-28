<?php

require_once(dirname(__FILE__).'/_lib/recaptchalib.php');

/**
 * @field.name                    recaptcha
 * @field.label                   Recaptcha
 *
 * @field.style                   no-label
 *
 * @field.extra.public.label      Public Key
 * @field.extra.public.input      text
 * @field.extra.public.default    6Le2gPISAAAAAAuO6LlqtYnz3H7JPoxqJOLumTr2
 *
 * @field.extra.private.label     Private Key
 * @field.extra.private.input     text
 * @field.extra.private.default   6Le2gPISAAAAAKHmixn_HP3bycnWtc5B90RfsDR5
 *
 * @field.extra.theme.label       Theme
 * @field.extra.theme.input       select
 * @field.extra.theme.options     ["red","white","blackglass","clean"]
 */
class Promotions_Core_Recaptcha_Field extends Snap_Wordpress_Form2_Field_Abstract
{
  
  public function init()
  {
    $this->add_validator( new Promotions_Core_Recaptcha_Validator() );
  }
  
  
  public function get_html()
  {
    
    $html = '<script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>';
    $html.= '<script type="text/javascript">
jQuery(function() {
  Recaptcha.create("'.$this->get_config('extra.public').'", document.getElementById("'.$this->get_id().'"), {
    theme: "'.$this->get_config('extra.theme', 'red').'"
  });
});
</script>';
    $html.= '<div id="'.$this->get_id().'"></div>';
    $html.= '<input type="hidden" name="'.$this->get_name().'" value="1" />';
    return $this->apply_filters('html', $html);
  }
}