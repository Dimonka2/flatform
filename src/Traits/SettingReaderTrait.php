<?php
namespace dimonka2\flatform\Traits;

trait SettingReaderTrait
{
    protected static function safeKey($key)
    {
        $key = str_replace('-', '_', $key);
        preg_match('/[_A-Za-z0-9]+/', $key, $matches);
        return $matches[0];
    }

    protected static function readSingleSetting(array &$element, $key)
    {
        $key = trim($key);
        if (isset($element[$key])) {
            $value = $element[$key];
            unset($element[$key]);
            return $value;
        } else {
            $key = self::safeKey($key);
            if (isset($element[$key])) {
                $value = $element[$key];
                unset($element[$key]);
                return $value;
            }
        }
        return null;
    }

    protected function readSettings(array &$element, array $keys)
    {
        foreach($keys as $keyKey => $key){
            // allow to map many possible attributes to one in order to support depricated ones
            if(is_array($key)) {
                foreach ($key as $key2) {
                    $value = self::readSingleSetting($element, $key2);
                    $keyKey = self::safeKey($keyKey);
                    if ($value !== null) $this->$keyKey = $value;
                }
            } else {
                $value = self::readSingleSetting($element, $key);
                $key = self::safeKey($key);
                if ($value !== null) $this->$key = $value;
            }
        }
    }
}
