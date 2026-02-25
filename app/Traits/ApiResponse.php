<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
  /**
   * Success response
   */
  protected function successResponse($data = null, string $message = 'Operation successful', int $code = 200): JsonResponse
  {
    return response()->json([
      'success' => true,
      'message' => $message,
      'data' => $data,
    ], $code);
  }

  /**
   * Error response
   */
  protected function errorResponse(string $message = 'Operation failed', $errors = null, int $code = 400): JsonResponse
  {
    $response = [
      'success' => false,
      'message' => $message,
    ];

    if ($errors) {
      $response['errors'] = $errors;
    }

    return response()->json($response, $code);
  }

  /**
   * Validation error response
   */
  protected function validationErrorResponse($errors, string $message = 'Validation failed'): JsonResponse
  {
    return $this->errorResponse($message, $errors, 422);
  }

  /**
   * Not found response
   */
  protected function notFoundResponse(string $message = 'Resource not found'): JsonResponse
  {
    return $this->errorResponse($message, null, 404);
  }

  /**
   * Unauthorized response
   */
  protected function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
  {
    return $this->errorResponse($message, null, 401);
  }

  /**
   * Forbidden response
   */
  protected function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
  {
    return $this->errorResponse($message, null, 403);
  }
}
