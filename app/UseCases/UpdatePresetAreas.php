<?php
declare(strict_types=1);

namespace App\UseCases;


use App\Area;
use App\ProductAreaPreset;
use Illuminate\Support\Collection;

final class UpdatePresetAreas
{
    public function execute(ProductAreaPreset $preset, Collection $areasIds): bool
    {
        $presetAreas = $preset->areas;
        $deletedAreas = $presetAreas->filter(function(Area $area) use ($areasIds){
            return !$areasIds->first(function (int $id) use($area){
                return $area->id === $id;
            });
        })->map(function(Area $area){
            return $area->id;
        });

        $addedAreas = $areasIds->filter(function(int $id) use($presetAreas){
            return !$presetAreas->first(function(Area $area) use($id){
                return $area->id === $id;
            });
        });

        $this->deleteAreasFromProducts($preset->products, $deletedAreas);
        $this->addAreasToProducts($preset->products, $addedAreas);

        $preset->areas()->sync($areasIds);

        return true;
    }

    private function deleteAreasFromProducts(Collection $products, Collection $areaIds){
        foreach ($products as $product){
            $product->areas()->detach($areaIds);
        }
    }

    private function addAreasToProducts(Collection $products, Collection $areaIds)
    {
        foreach ($products as $product) {
            $product->areas()->attach($areaIds);
        }
    }
}