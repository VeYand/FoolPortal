import {LoginInput} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		login: builder.query<void, LoginInput>({
			queryFn: async input => await studentPortalApi.get().authorizationApi.login(input),
		}),
	}),
})

export const {useLazyLoginQuery: useLazyLogin} = api
