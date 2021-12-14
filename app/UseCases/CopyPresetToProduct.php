<?php
declare(strict_types=1);

namespace App\UseCases;


use App\Area;
use App\Product;
use App\ProductAreaPreset;
use Illuminate\Support\Collection;

final class CopyPresetToProduct
{
    public function execute(Product $product, ProductAreaPreset $preset):bool
    {
        if(!$this->shouldAttachPreset($product,$preset)) return true;
        if($this->shouldDetachOldPreset($product,$preset)) $this->detachOldPreset($product,$product->preset);
        $this->attachPreset($product,$preset);

        return true;
    }

    public function getUniqueAreas(Product $product, ProductAreaPreset $preset): Collection
    {
        $productAreas = $product->areas;
        $presetAreas = $preset->areas;

        return $presetAreas->merge($productAreas)->map(function(Area $area){
            return $area->id;
        })->unique(function($item){
            return $item;
        });
    }

    private function shouldAttachPreset(Product $product, ProductAreaPreset $preset): bool
    {
        return !is_null($product->preset) && $product->preset->id !== $preset->id;
    }

    private function attachPreset(Product $product, ProductAreaPreset $preset): bool
    {
        $product->areas()->attach($this->getUniqueAreas($product,$preset));

        return true;
    }

    private function shouldDetachOldPreset(Product $product, ProductAreaPreset $preset):bool
    {
        return $this->shouldAttachPreset($product,$preset);
    }

    private function detachOldPreset(Product $product, ProductAreaPreset $preset):bool
    {
        $product->areas()->detach($preset->areas->map(function(Area $area){
            return $area->id;
        }));

        return true;
    }
}