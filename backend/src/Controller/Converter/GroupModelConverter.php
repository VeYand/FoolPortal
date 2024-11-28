<?php
declare(strict_types=1);

namespace App\Controller\Converter;

use App\User\App\Query\Data\GroupData;
use OpenAPI\Server\Model\GroupData as ApiGroupData;

readonly class GroupModelConverter
{
	public static function convertGroupDataToApiGroupData(GroupData $group): ApiGroupData
	{
		return new ApiGroupData([
			'groupId' => $group->groupId,
			'name' => $group->name,
		]);
	}

	/**
	 * @param GroupData[] $groups
	 * @return ApiGroupData[]
	 */
	public static function convertGroupsToApiGroups(array $groups): array
	{
		return array_map(
			static fn(GroupData $group) => self::convertGroupDataToApiGroupData($group),
			$groups,
		);
	}
}