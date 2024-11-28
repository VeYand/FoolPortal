import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		getLoggedUser: builder.query({
			queryFn: async () => await studentPortalApi.get().authorizationApi.getLoggedUser(),
		}),
	}),
})

export const {useLazyGetLoggedUserQuery: useLazyGetLoggedUser} = api