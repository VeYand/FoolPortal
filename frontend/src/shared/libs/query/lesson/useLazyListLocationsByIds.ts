import {ListLocationByIdsRequest, LocationsList} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		listLocations: builder.query<LocationsList, ListLocationByIdsRequest>({
			queryFn: async request => await studentPortalApi.get().lessonApi.listLocationsByIds(request),
		}),
	}),
})

export const {useLazyListLocationsQuery: useLazyListLocationsByIds} = api
