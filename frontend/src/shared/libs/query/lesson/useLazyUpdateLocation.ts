import {EmptyResponse, UpdateLocationRequest} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		updateLocation: builder.query<EmptyResponse, UpdateLocationRequest>({
			queryFn: async request => await studentPortalApi.get().lessonApi.updateLocation(request),
		}),
	}),
})

export const {useLazyUpdateLocationQuery: useLazyUpdateLocation} = api
