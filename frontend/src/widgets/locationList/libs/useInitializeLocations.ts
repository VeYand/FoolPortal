import {message} from 'antd'
import {useCallback, useEffect, useState} from 'react'
import {useLazyListLocations} from 'shared/libs/query'
import {remapApiLocationsToLocationsList} from 'shared/libs/remmapers/remapApiLocationsToLocationsList'
import {LocationData} from 'shared/types'

type UseInitializeLocations = {
	loading: boolean,
	locations: LocationData[],
	refetch: () => void,
}

const useInitializeLocations = (): UseInitializeLocations => {
	const [loading, setLoading] = useState(true)
	const [locations, setLocations] = useState<LocationData[]>([])
	const [listLocations] = useLazyListLocations()

	const fetch = useCallback(async () => {
		setLoading(true)
		const response = await listLocations({})

		if (!response.data || response.isError) {
			message.error('Не удалось получить список локаций. Повторите попытку позже.')
			return
		}

		setLocations(remapApiLocationsToLocationsList(response.data.locations))
		setLoading(false)
	}, [listLocations])

	useEffect(() => {
		setLoading(true)
		fetch()
	}, [fetch])

	return {loading: loading, locations, refetch: fetch}
}

export {
	useInitializeLocations,
}