<?php
declare(strict_types=1);

namespace App\Controller;

use App\Common\Uuid\UuidProviderInterface;
use App\Controller\Converter\GroupModelConverter;
use App\Controller\Converter\UserModelConverter;
use App\Controller\Exception\ExceptionHandler;
use App\User\Api\UserApiInterface;
use App\User\App\Query\Spec\ListGroupsSpec;
use App\User\App\Query\Spec\ListUsersSpec;
use OpenAPI\Server\Api\UserApiInterface as UserApiHandlerInterface;
use OpenAPI\Server\Model\CreateGroupMembersRequest;
use OpenAPI\Server\Model\CreateGroupRequest as ApiCreateGroupRequest;
use OpenAPI\Server\Model\CreateGroupResponse as ApiCreateGroupResponse;
use OpenAPI\Server\Model\CreateUserResponse as ApiCreateUserResponse;
use OpenAPI\Server\Model\EmptyResponse as ApiEmptyResponse;
use OpenAPI\Server\Model\CreateUserRequest;
use OpenAPI\Server\Model\DeleteGroupMembersRequest;
use OpenAPI\Server\Model\DeleteGroupRequest;
use OpenAPI\Server\Model\DeleteUserRequest;
use OpenAPI\Server\Model\GroupsList as ApiGroupsList;
use OpenAPI\Server\Model\ListGroupsRequest;
use OpenAPI\Server\Model\ListUsers200Response as ApiListUsers200Response;
use OpenAPI\Server\Model\ListUsersSpec as ApiListUsersSpec;
use OpenAPI\Server\Model\UpdateGroupRequest;
use OpenAPI\Server\Model\UpdateUserRequest;
use OpenAPI\Server\Model\UsersList as ApiUsersList;

readonly class UserApiHandler implements UserApiHandlerInterface
{
	public function __construct(
		private UserApiInterface      $userApi,
		private ExceptionHandler      $exceptionHandler,
		private UuidProviderInterface $uuidProvider,
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
			$output = $this->userApi->listUsers(
				new ListUsersSpec(
					$this->uuidProvider->fromStringsToUuids($listUsersSpec?->getGroupIds()),
					$listUsersSpec?->getOrderField(),
					$listUsersSpec?->isAscOrder(),
					$listUsersSpec?->getPage(),
					$listUsersSpec?->getLimit(),
					UserModelConverter::convertApiRolesToUserRoles($listUsersSpec?->getRoles())
				),
			);

			return new ApiListUsers200Response([
				'users' => new ApiUsersList([
					'users' => UserModelConverter::convertUsersToApiUsers($output->users)
				]),
				'maxPage' => $output->maxPage,
			]);
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function listGroups(ListGroupsRequest $listGroupsRequest, int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($listGroupsRequest)
		{
			$groups = $this->userApi->listGroups(
				new ListgroupsSpec(
					$this->uuidProvider->fromStringsToUuids($listGroupsRequest->getGroupIds()),
				),
			);

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
				'groupId' => $groupId->toString(),
			]);
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function deleteGroup(DeleteGroupRequest $deleteGroupRequest, int &$responseCode, array &$responseHeaders): null|array|object
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($deleteGroupRequest)
		{
			$this->userApi->deleteGroup(
				$this->uuidProvider->fromStringToUuid($deleteGroupRequest->getGroupId()),
			);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function updateGroup(UpdateGroupRequest $updateGroupRequest, int &$responseCode, array &$responseHeaders): null|array|object
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($updateGroupRequest)
		{
			$this->userApi->updateGroup(
				$this->uuidProvider->fromStringToUuid($updateGroupRequest->getGroupId()),
				$updateGroupRequest->getName(),
			);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function createGroupMembers(CreateGroupMembersRequest $createGroupMembersRequest, int &$responseCode, array &$responseHeaders): null|array|object
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($createGroupMembersRequest)
		{
			$this->userApi->createGroupMembers(
				$this->uuidProvider->fromStringsToUuids($createGroupMembersRequest->getGroupIds()),
				$this->uuidProvider->fromStringsToUuids($createGroupMembersRequest->getUserIds()),
			);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function deleteGroupMembers(DeleteGroupMembersRequest $deleteGroupMembersRequest, int &$responseCode, array &$responseHeaders): null|array|object
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($deleteGroupMembersRequest)
		{
			$this->userApi->deleteGroupMembers(
				$this->uuidProvider->fromStringsToUuids($deleteGroupMembersRequest->getGroupIds()),
				$this->uuidProvider->fromStringsToUuids($deleteGroupMembersRequest->getUserIds()),
			);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function createUser(CreateUserRequest $createUserRequest, int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($createUserRequest)
		{
			$input = UserModelConverter::convertCreateUserRequestToCreateUserInput($createUserRequest);
			$userId = $this->userApi->createUser($input);

			return new ApiCreateUserResponse([
				'userId' => $userId->toString(),
			]);
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function deleteUser(DeleteUserRequest $deleteUserRequest, int &$responseCode, array &$responseHeaders): null|array|object
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($deleteUserRequest)
		{
			$this->userApi->deleteUser(
				$this->uuidProvider->fromStringToUuid($deleteUserRequest->getUserId()),
			);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function updateUser(UpdateUserRequest $updateUserRequest, int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($updateUserRequest)
		{
			$input = UserModelConverter::convertUpdateUserRequestToUpdateUserInput($updateUserRequest, $this->uuidProvider);
			$this->userApi->updateUser($input);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}
}