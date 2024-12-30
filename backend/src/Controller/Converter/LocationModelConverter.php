<?php
declare(strict_types=1);

namespace App\Controller\Converter;

use App\Lesson\App\Query\Data\LocationData;
use OpenAPI\Server\Model\LocationsList as ApiLocationsList;
use OpenAPI\Server\Model\LocationData as ApiLocationData;


readonly class LocationModelConverter
{
	/**
	 * @param LocationData[] $locations
	 */
	public static function convertAppLocationsToApiLocations(array $locations): ApiLocationsList
	{
		$apiLocations = array_map(
			static fn(LocationData $location) => self::convertAppLocationToApiLocation($location),
			$locations,
		);

		return new ApiLocationsList([
			'locations' => $apiLocations,
		]);
	}

	public static function convertAppLocationToApiLocation(LocationData $location): ApiLocationData
	{
		return new ApiLocationData([
			'locationId' => $location->locationId,
			'name' => $location->name,
		]);
	}
}