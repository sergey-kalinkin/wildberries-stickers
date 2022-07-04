<?php


namespace App\Libraries\PDFGenerator\PDFConfig\Data;


use App\Libraries\PDFGenerator\PDFConfig\Render\Configs\DataConfig;
use App\Models\StickerData;
use Illuminate\Support\Collection;

class PrintingData extends ASpreadsheetData
{
    private StickerData $spreadsheetInstance;
    private Collection $spreadsheetRepresentation;
    private array $excepted = [];
    private DataConfig $config;

    public function __construct(StickerData $spreadsheet, DataConfig $config)
    {
        parent::__construct('wildberries');

        //
        $this->spreadsheetInstance = $spreadsheet;
        $this->config = $config;

        //
        $this->spreadsheetRepresentation = self::parseSpreadsheet(
            $data = json_decode($this->spreadsheetInstance->spreadsheet, true)//parse json to array
        );

        self::modifyData();
    }

    public function getProbe(): Collection
    {
        return self::removeExcepted(
            collect([$this->spreadsheetRepresentation->first()])
        );
    }

    public function getData(): Collection
    {
        return self::removeExcepted(
            $this->spreadsheetRepresentation
        );
    }

    protected function parseSpreadsheet(array $data): Collection
    {
        $this->excepted = $data['excluded'] ?? [];

        return parent::parseSpreadsheet($data)
            /*->pluck(
                [
                    'required_non_special',
                    'required_special',
                    'regular',
                ]
            )*/;
    }

    private function removeExcepted(Collection $data): Collection
    {
        if(empty($this->excepted))
            return $data;

        return $data->map(function ($item){
            /** @var Collection $sub_item */
            $sub_item=$item['regular'];
            return $item->merge(
                ['regular' => $sub_item->except($this->excepted)]
            );
        });
    }

    /**
     * @param string[] $excepted_keys
     */
    public function setExcepted(array $excepted_keys)
    {
        $this->excepted = array_merge($this->excepted, $excepted_keys);
    }

    private function modifyData()
    {
        if($this->config->do_merge_color_and_size) {
            $data = $this->spreadsheetRepresentation;

            $data->map(function ($item){
                /** @var Collection $item */
                $item->map(function ($group, $key) {

                    /** @var Collection $group */
                    if(!$group->has(['color', 'size']) || $key === 'keys')
                        return $group;

                    [$color, $size] = [$group->get('color'), $group->get('size')];
                    $group->put('color-size', "{$color} / Разм.: {$size}");
                    $group->forget(['color', 'size']);
                    return $group;
                });
            });

            $this->names->put('color-size', 'Цвет');
            $this->names->forget(['color', 'size']);
        }
    }
}
