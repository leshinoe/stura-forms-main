<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $userId, string $filename)
    {
        if (! $this->isAuthorizedToView($userId)) {
            abort(404);
        }

        $file = "attachments/{$userId}/{$filename}";

        if (! Storage::exists($file)) {
            abort(404);
        }

        return Response::file(Storage::path($file));
    }

    /**
     * Authorize the incoming request.
     */
    protected function isAuthorizedToView(string $userId): bool
    {
        if (! Auth::check()) {
            return false;
        }

        if (Auth::user()->is_admin) {
            return true;
        }

        return $userId === (string) Auth::id();
    }
}
