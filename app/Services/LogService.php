<?php
// app/Services/LogService.php
namespace App\Services;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogService
{
    /**
     * Create a log entry for important actions.
     * $meta should be an array with additional context.
     */
    public static function record(string $action, string $entityType = null, $entityId = null, array $meta = [])
    {
        $actor = Auth::user();

        return Log::create([
            'action' => $action,
            'actor_id' => $actor?->id,
            'entity_type' => $entityType ?? 'system',
            'entity_id' => $entityId,
            'meta' => $meta ?: null,
        ]);
    }
}
