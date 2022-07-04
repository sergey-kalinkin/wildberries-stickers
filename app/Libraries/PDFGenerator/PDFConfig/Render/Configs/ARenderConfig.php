<?php


namespace App\Libraries\PDFGenerator\PDFConfig\Render\Configs;


use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

abstract class ARenderConfig implements IRenderConfig
{
    private Collection $config;

    /**
     * @throws ValidationException
     */
    public function __construct(array $config=[])
    {
        $data = \Validator::validate(
                            $config,
                            static::rules(),
                            static::messages()
                        );

        $data = collect($data);
        $existed_values = $data->intersectByKeys(static::default())->keys();
        $needed_default = collect(static::default())->except($existed_values);
        $this->config = $data->merge($needed_default);
    }

    public function config() : array
    {
        return $this->config->toArray();
    }

    public function __get(string $name)
    {
        return $this->config->get($name);
    }

    protected function messages() : array
    {
        return [
            //default
        ];
    }

    abstract protected function default() : array;
    abstract protected function rules() : array;
}
