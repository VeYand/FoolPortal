import {apiSlice, foolPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		logout: builder.query({
			queryFn: async () => await foolPortalApi.get().authorizationApi.logout(),
		}),
	}),
})

export const {useLazyLogoutQuery: useLazyLogout} = api
