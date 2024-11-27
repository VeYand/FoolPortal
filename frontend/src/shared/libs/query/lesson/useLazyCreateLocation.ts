import {CreateLocationRequest} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		createLocation: builder.query<void, CreateLocationRequest>({
			queryFn: async request => await studentPortalApi.get().lessonApi.createLocation(request),
		}),
	}),
})

export const {useLazyCreateLocationQuery: useLazyCreateLocation} = api
