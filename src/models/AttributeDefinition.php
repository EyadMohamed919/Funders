<?php
namespace App\Models;

class AttributeDefinition
{
    public function __construct(
        private int $attributeID,
        private string $name,
        private string $dataType,
        private ?string $allowedValues = null
    ) {}

    public function getAllowedValuesList(): array
    {
        if (!$this->allowedValues) return [];
        return array_filter(array_map('trim', explode(',', $this->allowedValues)));
    }

    public function isValueAllowed(?string $rawValue): bool
    {
        if (!$rawValue || trim($rawValue) === '') return false;

        $allowed = $this->getAllowedValuesList();
        if ($allowed && !in_array($rawValue, $allowed, true)) return false;

        return match (strtoupper($this->dataType)) {
            'INTEGER' => (bool) preg_match('/^-?\d+$/', $rawValue),
            'DATE'    => (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawValue),
            default   => true
        };
    }

    // Getters & setters
    public function getAttributeID(): int                { return $this->attributeID; }
    public function setAttributeID(int $id): void        { $this->attributeID = $id; }
    public function getName(): string                    { return $this->name; }
    public function setName(string $name): void          { $this->name = $name; }
    public function getDataType(): string                { return $this->dataType; }
    public function setDataType(string $type): void      { $this->dataType = $type; }
    public function getAllowedValues(): ?string           { return $this->allowedValues; }
    public function setAllowedValues(?string $v): void   { $this->allowedValues = $v; }
}