<?php

declare(strict_types=1);

namespace Modules\Notifications\Presentation\Http;

use Illuminate\Http\Response;
use Modules\Notifications\Application\Services\NotificationService;

final readonly class NotificationController
{
    public function __construct(
        private NotificationService $notificationService,
    ) {}

    public function hook(string $action, string $reference): Response
    {
        match ($action) {
            'delivered' => $this->notificationService->delivered(reference: $reference),
            default => null,
        };

        return response()->noContent();
    }
}
