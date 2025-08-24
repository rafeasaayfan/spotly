<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

trait ResponseTrait
{
    /**
     * Render to a vue page.
     *
     * @param string $component
     * @param array $props
     * @return RedirectResponse
     */
    public function inertiaRender(string $component, array $props = []): \Inertia\Response
    {
        return Inertia::render($component, $props);
    }

    /**
     * Redirect to a route with a success message.
     *
     * @param string $route
     * @param string $message
     * @param array $params
     * @return RedirectResponse
     */
    public function redirectSuccess(string $route, string $message, array $params = []): RedirectResponse
    {
        return redirect()->route($route, $params)->with([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Redirect to a route with an error message.
     *
     * @param string $route
     * @param string $message
     * @param array $params
     * @return RedirectResponse
     */
    public function redirectError(string $route, string $message, array $params = []): RedirectResponse
    {
        return redirect()->route($route, $params)->with([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Redirect back with a success message.
     *
     * @param string $message
     * @return RedirectResponse
     */
    public function backSuccess(string $message): RedirectResponse
    {
        return redirect()->back()->with([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Redirect back with an error message.
     *
     * @param string $message
     * @return RedirectResponse
     */
    public function backError(string $message): RedirectResponse
    {
        return redirect()->back()->with([
            'success' => false,
            'message' => $message
        ])->withInput();
    }

    /**
     * Return a JSON response for success.
     *
     * @param string $message
     * @param array $data
     * @param int $status
     * @return JsonResponse
     */
    public function jsonSuccess(string $message, array $props = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'props' => $props
        ], $status);
    }

    /**
     * Return a JSON response for error.
     *
     * @param string $message
     * @param array $errors
     * @param int $status
     * @return JsonResponse
     */
    public function jsonError(string $message, array $errors = [], int $status = 422,): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $status);
    }

    /**
     * Return a Log response for error.
     *
     * @param string $errorIn
     * @param \Exception $e
     * @param string $message
     * @return RedirectResponse
     */
    public function logResponse(string $errorIn, \Exception $e, string $message = ''): RedirectResponse
    {
        Log::error(`Error on $errorIn: ` . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
        ]);

        return $this->backError($message);
    }

    /**
     * Return a Log json response for error.
     *
     * @param string $errorIn
     * @param \Exception $e
     * @param string $message
     * @return JsonResponse
     */
    public function logJsonResponse(string $errorIn, \Exception $e, string $message = ''): JsonResponse
    {
        Log::error(`Error on $errorIn: ` . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
        ]);

        return $this->jsonError($message, [config('app.debug') ? $e->getMessage() : null]);
    }
}