<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AuditLogger
{
    public static function log(string $action, ?string $entityType = null, $entityId = null, ?string $description = null, array $metadata = []): void
    {
        try {
            if (! Schema::hasTable('audit_logs')) {
                return;
            }

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'description' => $description,
                'metadata' => $metadata,
                'ip_address' => request()->ip(),
            ]);
        } catch (QueryException $e) {
            // Ignore logging failures to avoid blocking business actions.
        }
    }
}
