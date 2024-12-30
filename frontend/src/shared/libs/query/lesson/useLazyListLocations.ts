import {
	ListLocationsRequest as ApiListLocationsRequest,
	LocationsList as ApiLocationsList,
} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		listLocations: builder.query<ApiLocationsList, ApiListLocationsRequest>({
			queryFn: async request => await studentPortalApi.get().lessonApi.listLocations(request),
		}),
	}),
})

export const {useLazyListLocationsQuery: useLazyListLocations} = api
