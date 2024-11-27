import {LocationData as ApiLocationData} from 'shared/api'
import {LocationData} from 'shared/types'

const remapApiLocationToLocationData = (location: ApiLocationData): LocationData => ({
	locationId: location.locationId,
	name: location.name,
})

const remapApiLocationsToLocationsList = (locations: ApiLocationData[]): LocationData[] => locations.map(
	location => remapApiLocationToLocationData(location),
)

export {
	remapApiLocationsToLocationsList,
}