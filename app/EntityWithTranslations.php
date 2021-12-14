<?php
declare(strict_types=1);

namespace App;

use App\ValueObjects\LanguageEnum;
use App\ValueObjects\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @property Collection $translations
 **/
abstract class EntityWithTranslations extends Model
{
    private $translations;
    private $isTranslationsLoaded = false;

    protected $translationTable = null;
    protected $translationForeignKey = null;

    public function setTranslationsAttribute(array $translations)
    {
        $result = [];
        $filtered = array_filter($translations, function($item){
            return !is_null($item) && strlen($item);
        });
        foreach($filtered as $locale => $name)
        {
            $result[] = new Translation($locale, $name ?? '');
        }
        $this->translations = collect($result);
    }

    public function getTranslationsAttribute()
    {
        return $this->getTranslations();
    }

    public function getTranslations(): Collection
    {
        if(!$this->isTranslationsLoaded){
            $this->translations = $this->hydrateTranslations();
            $this->isTranslationsLoaded = true;
        }

        return $this->translations;
    }

    public function save(array $options = [])
    {
        parent::save($options);

        if($this->getTranslations()->isNotEmpty()) {
            $translations =  $this->translations->map(function(Translation $translation){
                $entry = $translation->setRelatedId($this->getPrimaryKeyValue())->toArray();
                $entry[$this->getTranslationTableForeignKey()] = $entry['related_id'];
                unset($entry['related_id']);
                return $entry;
            })
                ->toArray();

            DB::table($this->getTranslationsTableName())
                ->where($this->getTranslationTableForeignKey(),$this->getPrimaryKeyValue())
                ->delete();

            DB::table($this->getTranslationsTableName())->insert($translations);
        }
    }

    public function toArray()
    {
        if($this->getTranslations()->isEmpty()) return parent::toArray();
        $data = array_reduce($this->getTranslations()->toArray(),function($acc, $trans){
            $acc[$trans['locale']] = $trans;
            return $acc;
        });
        return array_merge(parent::toArray(),['localization' => $data]);
    }

    public function getTranslatedName($locale): string
    {
        $translation = $this->getTranslations()->first(function(Translation $translation) use ($locale){
            return $translation->getCode() === $locale;
        });
        return !is_null($translation) ? $translation->getName() : '';
    }

    public function getDefaultTranslatedName(): string
    {
        return $this->getTranslatedName(LanguageEnum::DEFAULT_LANGUAGE);
    }

    protected function hydrateTranslations(): Collection
    {
          return DB::table($this->getTranslationsTableName())
            ->where($this->getTranslationTableForeignKey(), $this->getPrimaryKeyValue())
            ->get()
            ->map(function($row){
                return $this->hydrateTranslationFromTableRow((array)$row);
            });

    }

    private function getTranslationsTableName()
    {
        return !is_null($this->translationTable) ? $this->translationTable : $this->table. '_translations';
    }

    private function getTranslationTableForeignKey()
    {
        return $this->translationForeignKey;
    }

    private function getPrimaryKeyValue()
    {
        return $this->attributes[$this->primaryKey];
    }

    protected function hydrateTranslationFromTableRow(array $row): Translation
    {
        return new Translation($row['locale'], $row['name'], $row[$this->getTranslationTableForeignKey()]);
    }

    public function delete()
    {
        DB::table($this->getTranslationsTableName())
            ->where($this->getTranslationTableForeignKey(), $this->getPrimaryKeyValue())
            ->delete();
        return parent::delete();
    }
}