import {DeleteLocationRequest} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		deleteLocation: builder.query<void, DeleteLocationRequest>({
			queryFn: async request => await studentPortalApi.get().lessonApi.deleteLocation(request),
		}),
	}),
})

export const {useLazyDeleteLocationQuery: useLazyDeleteLocation} = api