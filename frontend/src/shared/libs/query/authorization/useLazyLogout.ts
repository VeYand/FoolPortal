import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		logout: builder.query({
			queryFn: async () => await studentPortalApi.get().authorizationApi.logout(),
		}),
	}),
})

export const {useLazyLogoutQuery: useLazyLogout} = api
