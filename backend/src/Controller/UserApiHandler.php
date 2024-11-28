<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Converter\GroupModelConverter;
use App\Controller\Converter\UserModelConverter;
use App\Controller\Exception\ExceptionHandler;
use App\User\Api\UserApiInterface;
use App\User\App\Query\Spec\ListUsersSpec;
use OpenAPI\Server\Api\UserApiInterface as UserApiHandlerInterface;
use OpenAPI\Server\Model\AddStudentsToGroupRequest;
use OpenAPI\Server\Model\CreateGroupRequest as ApiCreateGroupRequest;
use OpenAPI\Server\Model\CreateGroupResponse as ApiCreateGroupResponse;
use OpenAPI\Server\Model\DeleteGroupRequest;
use OpenAPI\Server\Model\GroupsList as ApiGroupsList;
use OpenAPI\Server\Model\ListUsersSpec as ApiListUsersSpec;
use OpenAPI\Server\Model\RemoveStudentsFromGroupRequest;
use OpenAPI\Server\Model\UpdateGroupRequest;
use OpenAPI\Server\Model\UsersList as ApiUsersList;

readonly class UserApiHandler implements UserApiHandlerInterface
{
	public function __construct(
		private UserApiInterface $userApi,
		private ExceptionHandler $exceptionHandler,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function listUsers(?ApiListUsersSpec $listUsersSpec, int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($listUsersSpec)
		{
			$users = $this->userApi->listUsers(new ListUsersSpec($listUsersSpec?->getGroupIds()));

			return new ApiUsersList([
				'users' => UserModelConverter::convertUsersToApiUsers($users),
			]);
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function listGroups(int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function ()
		{
			$groups = $this->userApi->listAllGroups();

			return new ApiGroupsList([
				'groups' => GroupModelConverter::convertGroupsToApiGroups($groups),
			]);
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function createGroup(ApiCreateGroupRequest $createGroupRequest, int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($createGroupRequest)
		{
			$groupId = $this->userApi->createGroup($createGroupRequest->getName());

			return new ApiCreateGroupResponse([
				'groupId' => $groupId,
			]);
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function deleteGroup(DeleteGroupRequest $deleteGroupRequest, int &$responseCode, array &$responseHeaders): void
	{
		$this->exceptionHandler->executeWithHandle(function () use ($deleteGroupRequest)
		{
			$this->userApi->deleteGroup($deleteGroupRequest->getGroupId());
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function updateGroup(UpdateGroupRequest $updateGroupRequest, int &$responseCode, array &$responseHeaders): void
	{
		$this->exceptionHandler->executeWithHandle(function () use ($updateGroupRequest)
		{
			$this->userApi->updateGroup($updateGroupRequest->getGroupId(), $updateGroupRequest->getName());
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function addStudentsToGroup(string $groupId, AddStudentsToGroupRequest $addStudentsToGroupRequest, int &$responseCode, array &$responseHeaders): void
	{
		$this->exceptionHandler->executeWithHandle(function () use ($groupId, $addStudentsToGroupRequest)
		{
			$this->userApi->addStudentsToGroup($groupId, $addStudentsToGroupRequest->getStudentIds());
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function removeStudentsFromGroup(string $groupId, RemoveStudentsFromGroupRequest $removeStudentsFromGroupRequest, int &$responseCode, array &$responseHeaders): void
	{
		$this->exceptionHandler->executeWithHandle(function () use ($groupId, $removeStudentsFromGroupRequest)
		{
			$this->userApi->removeStudentsFromGroup($groupId, $removeStudentsFromGroupRequest->getStudentIds());
		}, $responseCode, $responseHeaders);
	}
}