import {locationEntitySlice} from 'entities/location'
import {useCallback} from 'react'
import {useLazyListAllLocations} from 'shared/libs/query'
import {remapApiLocationsToLocationsList} from 'shared/libs/remmapers/remapApiLocationsToLocationsList'
import {useAppDispatch, useAppSelector} from 'shared/redux'

type UseInitializeLocations = {
	initialize: () => void,
	isLoading: boolean,
}

const useInitializeLocations = (): UseInitializeLocations => {
	const dispatch = useAppDispatch()
	const loadingState = useAppSelector(state => state.locationEntity.loading)
	const [listLocations, {isLoading, isFetching}] = useLazyListAllLocations()

	const initialize = useCallback(async () => {
		dispatch(locationEntitySlice.actions.setLoading(true))

		const response = await listLocations({})

		if (response.isError) {
			dispatch(locationEntitySlice.actions.setLoading(false))
			return
		}

		if (response.data) {
			const locations = remapApiLocationsToLocationsList(response.data.locations)
			dispatch(locationEntitySlice.actions.setLocations(locations))
		}
	}, [dispatch, listLocations])

	return {isLoading: isLoading || isFetching || loadingState, initialize}
}

export {
	useInitializeLocations,
}