<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $table = 'robot_sensor_logs';
    protected $fillable = [
        'robot_id',
        'sensor_type',
        'value'
    ];
    protected $primaryKey = 'id';

    public function robot()
    {
        return $this->belongsTo(Robot::class, 'robot_id');
    }

    public static function getLatestByRobot($robotId, $limit = 10)
    {
        return static::where('robot_id', $robotId)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    public static function getLatestByType($robotId, $sensorType, $limit = 10)
    {
        return static::where('robot_id', $robotId)
            ->where('sensor_type', $sensorType)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    public static function getLatestByTypeAndValue($robotId, $sensorType, $value, $limit = 10)
    {
        return static::where('robot_id', $robotId)
            ->where('sensor_type', $sensorType)
            ->where('value', $value)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }
}