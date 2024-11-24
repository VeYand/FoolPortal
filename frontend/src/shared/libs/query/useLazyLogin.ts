import {LoginInput} from 'shared/api'
import {apiSlice, foolPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		login: builder.query<void, LoginInput>({
			queryFn: async input => await foolPortalApi.get().authorizationApi.login(input),
		}),
	}),
})

export const {useLazyLoginQuery: useLazyLogin} = api
