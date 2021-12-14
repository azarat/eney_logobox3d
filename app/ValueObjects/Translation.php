<?php
declare(strict_types=1);

namespace App\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;

final class Translation implements Arrayable
{

    private $code;
    private $name;
    private $relatedId;

    public function __construct(string $code,string $name, ?int $relatedId = null)
    {
        $this->setCode($code);
        $this->name = $name;
        $this->relatedId = $relatedId;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    private function setCode($code): void
    {
        if(!in_array($code, LanguageEnum::LANGUAGES)) throw new \Exception('Unknown language code');
        $this->code = $code;
    }

    public function setRelatedId(int $applicationTypeId): Translation
    {
        $this->relatedId= $applicationTypeId;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRelatedId(): int
    {
        return $this->relatedId;
    }

    public function toArray(): array
    {
        return [
            'locale' => $this->code,
            'name' => $this->name,
            'related_id' => $this->relatedId
        ];
    }
}