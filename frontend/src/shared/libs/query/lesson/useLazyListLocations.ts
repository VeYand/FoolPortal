import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		listLocations: builder.query({
			queryFn: async () => await studentPortalApi.get().lessonApi.listLocations(),
		}),
	}),
})

export const {useLazyListLocationsQuery: useLazyListLocations} = api
