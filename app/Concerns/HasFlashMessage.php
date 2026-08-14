<?php

namespace App\Concerns;

use Inertia\Inertia;

trait HasFlashMessage
{
    protected function successMessage(string $message): void
    {
        Inertia::flash('toast', ['type' => 'success', 'message' => __($message)]);
    }

    protected function warningMessage(string $message): void
    {
        Inertia::flash('toast', ['type' => 'warning', 'message' => __($message)]);
    }

    protected function errorMessage(string $message): void
    {
        Inertia::flash('toast', ['type' => 'error', 'message' => __($message)]);
    }
}
