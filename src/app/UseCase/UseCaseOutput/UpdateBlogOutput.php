<?php

namespace App\UseCase\UseCaseOutput;

final class UpdateBlogOutput
{
  private bool $success;
  private string $message;

  public function __construct(bool $success, string $message)
  {
    $this->success = $success;
    $this->message = $message;
  }

  public function success(): bool
  {
    return $this->success;
  }
  public function message(): string
  {
    return $this->message;
  }
}