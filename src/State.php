<?php
namespace dimonka2\platform;

use Illuminate\Support\Facades\Cache;

class State
{
    private static $states = null;
    private const STATES = '_states';

	protected static function getStates()
	{
		if (is_null(static::$states)) static::$states = Cache::rememberForever(static::STATES, function () {
			return \App\Models\State::all();
		});
		return static::$states;
    }

    public static function clearCache()
    {
        static::$states = null;
        Cache::forget(static::STATES);
    }

    public static function getState($id)
    {
        return static::getStates()->where('id', $id)->first();
    }

    public static function getStateKey($id)
    {
        $state = self::getState($id);
        if(!is_object($state)) return null;
        return $state->state_key;
    }

    public static function selectState($key)
    {
        return static::getStates()->where('state_key', $key)->first();
    }

    public static function selectStateId($key)
    {
        $state = self::selectState($key);
        if (!is_object($state)) return null;
        return $state->id;
    }

    public static function getStateList($type = null)
    {
        if(isset($type)) return static::getStates()->where('state_type', $type)->all();
        return static::getStates();
    }

    public static function localizeState($state)
    {
        $stateid = 'states.' . $state['state_key'];
        // try to localize
        $newName = __($stateid);
        if ($newName == $stateid) return $state['name'];
        return $newName;
    }

    public static function selectStateList($type, $sort = true)
    {
        $states = static::getStates()->where('state_type', $type)->all();
		$res = [];
		foreach ($states as $state) {
			$res[$state['id']] = self::localizeState($state);
		}
		if ($sort) {
			asort($res);
		}
		return $res;
    }

	public static function formatState($state, $addIcon = true)
	{
		if (!is_object($state)) $state = static::getState($state);
		if (!is_object($state) ) 	{return "";}
        return ($addIcon ? Helper::formatIcon($state->icon) . " " : "") .
            self::localizeState($state);
    }

    public static function closest_state($input, $collection, &$percent = null) {
        $input = strtolower($input);
        $shortest = -1;
        foreach ($collection as $state) {
            $lev = levenshtein($input, strtolower($state->name));

            if ($lev == 0) {
                $closest = $state;
                $shortest = 0;
                break;
            }

            if ($lev <= $shortest || $shortest < 0) {
                $closest  = $state;
                $shortest = $lev;
            }
        }

        $percent = 1 - levenshtein($input, strtolower($closest->name)) / max(strlen($input), strlen($closest->name));

        return $closest;
    }

    public static function color($state, $default = 'dark')
    {
        if (!is_object($state)) $state = static::getState($state);
        if (!is_object($state) or !isset($state->color) ) 	return $default;
        if ($state->color == 'default') return $default;
        return $state->color;
    }

}
