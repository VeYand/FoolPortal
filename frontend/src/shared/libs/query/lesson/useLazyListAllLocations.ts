import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		listAllLocations: builder.query({
			queryFn: async () => await studentPortalApi.get().lessonApi.listLocations(),
		}),
	}),
})

export const {useLazyListAllLocationsQuery: useLazyListAllLocations} = api
