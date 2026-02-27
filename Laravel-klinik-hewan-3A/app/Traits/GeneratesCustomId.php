<?php

namespace App\Traits;

trait GeneratesCustomId
{
    /**
     * Boot function untuk auto-generate ID saat creating
     */
    protected static function bootGeneratesCustomId()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = $model->generateCustomId();
            }
        });
    }

    /**
     * Generate custom ID berdasarkan prefix dan format
     */
    public function generateCustomId(): string
    {
        $prefix = $this->idPrefix ?? 'XXX';
        $length = $this->idLength ?? 3;
        
        // Get the last ID with the same prefix
        $lastRecord = static::where($this->getKeyName(), 'like', $prefix . '%')
            ->orderBy($this->getKeyName(), 'desc')
            ->first();
        
        if (!$lastRecord) {
            // First record, start from 001
            $number = 1;
        } else {
            // Extract number from last ID and increment
            $lastId = $lastRecord->{$this->getKeyName()};
            $lastNumber = (int) substr($lastId, strlen($prefix));
            $number = $lastNumber + 1;
        }
        
        // Format with leading zeros
        return $prefix . str_pad($number, $length, '0', STR_PAD_LEFT);
    }
}
