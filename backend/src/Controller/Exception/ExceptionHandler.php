<?php
declare(strict_types=1);

namespace App\Controller\Exception;


use App\Common\Logger\LoggerInterface;
use App\Lesson\Api\Exception\ApiException as LessonApiException;
use App\Session\Api\Exception\ApiException as SessionApiException;
use App\Subject\Api\Exception\ApiException as SubjectApiException;
use App\User\Api\Exception\ApiException as UserApiException;
use OpenAPI\Server\Model\BadResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class ExceptionHandler
{
	public function __construct(
		private LoggerInterface $logger,
	)
	{
	}

	public function executeWithHandle(callable $callback, int &$responseCode, array &$responseHeaders): mixed
	{
		try
		{
			$response = $callback();
			$responseCode = Response::HTTP_OK;
		}
		catch (\Throwable $exception)
		{
			return $this->handleException($exception, $responseCode, $responseHeaders);
		}

		return $response;
	}

	/**
	 * @throws \Throwable
	 */
	private function handleException(\Throwable $exception, int &$responseCode, array &$responseHeaders): ?BadResponse
	{
		try
		{
			if ($exception instanceof UserApiException)
			{
				return self::handleUserException($exception, $responseCode, $responseHeaders);
			}
			if ($exception instanceof SessionApiException)
			{
				return self::handleSessionException($exception, $responseCode, $responseHeaders);
			}
			if ($exception instanceof SubjectApiException)
			{
				return self::handleSubjectException($exception, $responseCode, $responseHeaders);
			}
			if ($exception instanceof LessonApiException)
			{
				return self::handleLessonException($exception, $responseCode, $responseHeaders);
			}
		}
		catch (\Throwable $e)
		{
			$this->logger->logError($e->getMessage());
		}

		throw $exception;
	}

	/**
	 * @throws \Exception
	 */
	private static function handleUserException(\Exception $exception, int &$responseCode, array &$responseHeaders): BadResponse
	{
		switch ($exception->getCode())
		{
			case UserApiException::INVALID_BASE_64_DATA:
			case UserApiException::UNSUPPORTED_IMAGE_FORMAT:
			case UserApiException::PASSWORD_IS_TOO_LONG:
			case UserApiException::EMAIL_IS_NOT_UNIQUE:
			case UserApiException::INVALID_USER_ROLE:
				return self::constructBadRequestResponse($exception->getMessage(), $responseCode, $responseHeaders);
			case UserApiException::USER_NOT_FOUND:
			case UserApiException::GROUP_NOT_FOUND:
				return self::constructNotFoundRequestResponse($exception->getMessage(), $responseCode, $responseHeaders);
			case UserApiException::GROUP_MEMBER_ALREADY_EXISTS:
				return self::constructConflictResponse($exception->getMessage(), $responseCode, $responseHeaders);
		}

		throw $exception;
	}

	/**
	 * @throws \Exception
	 */
	private static function handleSessionException(\Exception $exception, int &$responseCode, array &$responseHeaders): BadResponse
	{
		if ($exception->getCode() === SessionApiException::NOT_AUTHORIZED)
		{
			return self::constructUnauthorizedResponse($exception->getMessage(), $responseCode, $responseHeaders);
		}

		throw $exception;
	}

	/**
	 * @throws \Exception
	 */
	private static function handleSubjectException(\Exception $exception, int &$responseCode, array &$responseHeaders): BadResponse
	{
		switch ($exception->getCode())
		{
			case SubjectApiException::USER_IS_NOT_TEACHER:
				return self::constructBadRequestResponse($exception->getMessage(), $responseCode, $responseHeaders);
			case SubjectApiException::TEACHER_SUBJECT_NOT_FOUND:
			case SubjectApiException::SUBJECT_NOT_FOUND:
			case SubjectApiException::GROUP_NOT_EXISTS:
			case SubjectApiException::USER_NOT_EXISTS:
				return self::constructNotFoundRequestResponse($exception->getMessage(), $responseCode, $responseHeaders);
			case SubjectApiException::COURSE_ALREADY_EXISTS:
			case SubjectApiException::TEACHER_SUBJECT_ALREADY_EXISTS:
				return self::constructConflictResponse($exception->getMessage(), $responseCode, $responseHeaders);
		}

		throw $exception;
	}

	/**
	 * @throws \Exception
	 */
	private static function handleLessonException(\Exception $exception, int &$responseCode, array &$responseHeaders): BadResponse
	{
		switch ($exception->getCode())
		{
			case LessonApiException::INVALID_LESSON_DURATION:
			case LessonApiException::INVALID_LESSON_START_TIME:
			case LessonApiException::LESSON_DATE_ALREADY_PASSED:
			case LessonApiException::INVALID_BASE_64_DATA:
				return self::constructBadRequestResponse($exception->getMessage(), $responseCode, $responseHeaders);
			case LessonApiException::COURSE_NOT_EXISTS:
			case LessonApiException::LOCATION_NOT_FOUND:
			case LessonApiException::LESSON_NOT_FOUND:
			case LessonApiException::ATTACHMENT_NOT_FOUND:
				return self::constructNotFoundRequestResponse($exception->getMessage(), $responseCode, $responseHeaders);
			case LessonApiException::LESSON_ATTACHMENT_ALREADY_EXISTS:
				return self::constructConflictResponse($exception->getMessage(), $responseCode, $responseHeaders);
		}

		throw $exception;
	}

	private static function constructBadRequestResponse(?string $message, int &$responseCode, array &$responseHeaders): BadResponse
	{
		$responseCode = Response::HTTP_BAD_REQUEST;
		return self::constructBadResponse($message ?? Response::$statusTexts[$responseCode]);
	}

	private static function constructUnauthorizedResponse(?string $message, int &$responseCode, array &$responseHeaders): BadResponse
	{
		$responseCode = Response::HTTP_UNAUTHORIZED;
		return self::constructBadResponse($message ?? Response::$statusTexts[$responseCode]);
	}

	private static function constructNotFoundRequestResponse(?string $message, int &$responseCode, array &$responseHeaders): BadResponse
	{
		$responseCode = Response::HTTP_NOT_FOUND;
		return self::constructBadResponse($message ?? Response::$statusTexts[$responseCode]);
	}

	private static function constructConflictResponse(?string $message, int &$responseCode, array &$responseHeaders): BadResponse
	{
		$responseCode = Response::HTTP_CONFLICT;
		return self::constructBadResponse($message ?? Response::$statusTexts[$responseCode]);
	}

	private static function constructBadResponse(string $message): BadResponse
	{
		return new BadResponse([
			'message' => $message,
		]);
	}
}