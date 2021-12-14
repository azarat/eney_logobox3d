<?php
declare(strict_types=1);

namespace App\Admin\Extensions;

use App\ValueObjects\LanguageEnum;
use Encore\Admin\Form;
use Encore\Admin\Form\Field;

final class TranslationField extends Field
{
    public function render()
    {
        $res = '';
        foreach (LanguageEnum::LANGUAGES as $code){
            $text = new Field\Text('translations_'. $code, ['Заголовок '.$code]);
            $text->attribute([
                'name' => 'translations['. $code . ']'
            ])
                ->value($this->getLocalization($code));
            $res.=$text->render();
        }

        return $res;
    }

    public function ignoreForm(Form $form)
    {
        foreach (LanguageEnum::LANGUAGES as $code){
            $form->ignore('translations_'.$code);
        }
    }

    private function getLocalization(string $code): string
    {
        if(isset($this->value[$code])) return $this->value[$code]['name'] ?? '';
        return '';
    }
}