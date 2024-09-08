<?php

namespace BamboleeDigital\TranslatableResourceKit\Http\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

trait TranslatesAttributes
{
    protected function getMaxRecursionDepth(): int
    {
        return config('translatable-resource-kit.max_recursion_depth', 5);
    }

    public function translatedAttributes(int $depth = 0): array
    {
        if ($depth >= $this->getMaxRecursionDepth()) {
            return [];
        }

        $locale = app()->getLocale();
        $translated = $this->getTranslatedAttributes($locale);
        
        $translated = $this->addTranslatedRelations($translated, $locale, $depth);
        
        return $this->filterHiddenAttributes($translated);
    }

    protected function getTranslatedAttributes(string $locale): array
    {
        return collect($this->getAttributes())
            ->map(function ($value, $key) use ($locale) {
                if (in_array($key, $this->getTranslatableAttributes())) {
                    return $this->getTranslation($key, $locale);
                }
                if (is_array($value)) {
                    return $this->translateArrayAttribute($value, $locale);
                }
                return $value;
            })
            ->all();
    }

    protected function translateArrayAttribute(array $value, string $locale): array
    {
        return collect($value)->map(function ($item) use ($locale) {
            if (is_array($item) && isset($item[$locale])) {
                return $item[$locale];
            }
            if (is_array($item)) {
                return $this->translateArrayAttribute($item, $locale);
            }
            return $item;
        })->all();
    }

    protected function getRelationsToTranslate(): array
    {
        return array_merge($this->with ?? [], $this->appends ?? []);
    }

    protected function addTranslatedRelations(array $attributes, string $locale, int $depth): array
    {
        foreach ($this->getRelationsToTranslate() as $relation) {
            $snakeRelation = Str::snake($relation);
            $camelRelation = Str::camel($relation);

            $relatedData = $this->getRelationValue($relation);
            
            if ($relatedData !== null) {
                $translatedRelation = $this->translateRelation($relatedData, $locale, $depth + 1);
                
                $attributes[$snakeRelation] = $translatedRelation;
                $attributes[$camelRelation] = $translatedRelation;

                // Apply custom key mapping if defined
                if (isset($this->relationToJsonKeyMap[$relation])) {
                    $customKey = $this->relationToJsonKeyMap[$relation];
                    $attributes[$customKey] = $translatedRelation;
                }
            }
        }

        return $attributes;
    }

    protected function translateRelation($relation, string $locale, int $depth)
    {
        if ($relation instanceof Model && method_exists($relation, 'translatedAttributes')) {
            return $relation->translatedAttributes($depth);
        } elseif ($relation instanceof Collection) {
            return $relation->map(fn($item) => $this->translateRelation($item, $locale, $depth))->all();
        } elseif (is_array($relation)) {
            return $this->translateArrayRelation($relation, $locale, $depth);
        }
        return $relation;
    }

    protected function translateArrayRelation(array $relation, string $locale, int $depth): array
    {
        return collect($relation)->map(function ($value) use ($locale, $depth) {
            if (is_array($value)) {
                return $this->translateArrayAttribute($value, $locale);
            }
            if ($value instanceof Model && method_exists($value, 'translatedAttributes')) {
                return $value->translatedAttributes($depth);
            }
            return $value;
        })->all();
    }

    protected function filterHiddenAttributes(array $attributes): array
    {
        return array_diff_key($attributes, array_flip($this->getHidden()));
    }

    public function getRelationValue($key)
    {
        // If the relation is already loaded, return it
        if ($this->relationLoaded($key)) {
            return $this->getRelation($key);
        }

        // If the key is a method on the model, load and return the relation
        if (method_exists($this, $key) && $this->$key() instanceof Relation) {
            return $this->$key()->getResults();
        }

        // If the attribute exists but isn't a relation, return it
        if (array_key_exists($key, $this->attributes)) {
            return $this->getAttribute($key);
        }

        return null;
    }
}