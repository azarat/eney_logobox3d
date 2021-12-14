<?php
declare(strict_types=1);

namespace App\Admin\Extensions;

use Encore\Admin\Admin;
use Encore\Admin\Form\Field\MultipleSelect;
use Illuminate\Contracts\Support\Arrayable;

final class MultiselectGroupedField extends MultipleSelect
{
    const PAD_REPEATES = 4;
    const PAD_DELIM = '>';
    protected $view = 'components.groupingmultipleselect';

    public function options($options = [])
    {
        $optionsPrepared = $options instanceof Arrayable ? $options->toArray() : $options;
        $result = [];
        foreach ($optionsPrepared as $group => $data){
            $result[$group] = [];
            if($data instanceof Arrayable){
                $data = $data->toArray();
            }
            foreach ($data as $key => $value){
                $result[$group][$key] = str_repeat(self::PAD_DELIM, self::PAD_REPEATES). $value;
            }
        }

        $this->options = $result;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $configs = array_merge([
            'allowClear'  => true,
            'placeholder' => [
                'id'   => '',
                'text' => $this->label,
            ],
        ], $this->config);

        $configs = json_encode($configs);

        if (empty($this->script)) {
            $this->script = "$(\"{$this->getElementClassSelector()}\").select2($configs);";
        }

        $this->addVariables([
            'options' => $this->options,
            'groups'  => $this->groups,
        ]);

        $this->attribute('data-value', implode(',', (array) $this->value()));

        Admin::script($this->script);

        return view($this->view, $this->variables());
    }
}