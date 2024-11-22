import {apiSlice, foolPortalApi} from '../../redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		getLoggedUser: builder.query({
			queryFn: async () => await foolPortalApi.get().authorizationApi.getLoggedUser(),
		}),
	}),
})

export const {useLazyGetLoggedUserQuery: useLazyGetLoggedUser} = api